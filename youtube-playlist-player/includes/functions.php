<?php
/*
 * Add default plugin options
 */
function ytpp_install() {
    add_option( 'ytpp_rel', 0 );
    add_option( 'ytpp_info', 0 );
    add_option( 'ytpp_controls', 1 );
    add_option( 'ytpp_privacy', 0 );
    add_option( 'ytpp_facade_notice', '' );

    add_option( 'ytppYouTubeApi', '' );
}

/*
 * Remove default plugin options on uninstall
 */
function ytpp_uninstall() {
    delete_option( 'ytpp_rel' );
    delete_option( 'ytpp_info' );
    delete_option( 'ytpp_controls' );
    delete_option( 'ytpp_privacy' );
    delete_option( 'ytpp_iframe_fix' );
    delete_option( 'ytpp_facade_notice' );

    delete_option( 'ytppYouTubeApi' );
}

/*
 * Add plugin options page
 */
function ytpp_admin() {
    add_options_page( __( 'Playlist Player for YouTube', 'youtube-playlist-player' ), __( 'Playlist Player for YouTube', 'youtube-playlist-player' ), 'manage_options', 'ytpp', 'ytpp_settings' );
}

/*
 * Extract YouTube video IDs (and optional start times) from IDs or URLs.
 *
 * @return array<int, array{id: string, start: int}>
 */
function ytpp_parse_videos( $input ) {
    $videos = [];

    foreach ( preg_split( '/[\s,]+/', (string) $input, -1, PREG_SPLIT_NO_EMPTY ) as $item ) {
        if ( ! preg_match( '~(?:v=|youtu\.be/|/embed/|/shorts/|/live/|^)([A-Za-z0-9_-]{11})(?:[^A-Za-z0-9_-]|$)~', $item, $match ) ) {
            continue;
        }

        $start = 0;

        if ( preg_match( '~[?&;](?:t|start)=(?:(\d+)h)?(?:(\d+)m)?(\d+)s?~', $item, $time ) ) {
            $start = (int) $time[1] * 3600 + (int) $time[2] * 60 + (int) $time[3];
        }

        $videos[] = [
            'id'    => $match[1],
            'start' => $start,
        ];
    }

    return $videos;
}

/*
 * Call a YouTube Data API v3 endpoint server-side, so the API key never reaches the browser.
 *
 * @return array Decoded "items" list, or an empty array on failure.
 */
function ytpp_api_get( $endpoint, $args ) {
    $api_key = sanitize_text_field( get_option( 'ytppYouTubeApi' ) );

    if ( $api_key === '' ) {
        return [];
    }

    $cache_key = 'ytpp_' . md5( $endpoint . wp_json_encode( $args ) );
    $cached    = get_transient( $cache_key );

    if ( is_array( $cached ) ) {
        return $cached;
    }

    $response = wp_remote_get(
        add_query_arg( array_merge( $args, [ 'key' => $api_key ] ), 'https://www.googleapis.com/youtube/v3/' . $endpoint ),
        [
            'timeout' => 10,
            'headers' => [
                'Referer' => home_url(),
            ],
        ]
    );

    $data  = is_wp_error( $response ) ? null : json_decode( wp_remote_retrieve_body( $response ), true );
    $items = $data['items'] ?? [];

    if ( $items ) {
        set_transient( $cache_key, $items, 12 * HOUR_IN_SECONDS );
    }

    return $items;
}

function ytpp_thumbnail( $snippet, $video_id ) {
    $thumbnails = $snippet['thumbnails'] ?? [];

    return $thumbnails['maxres']['url'] ?? $thumbnails['standard']['url'] ?? $thumbnails['high']['url'] ?? 'https://i.ytimg.com/vi/' . $video_id . '/hqdefault.jpg';
}

function ytpp_embed_base() {
    return (int) get_option( 'ytpp_privacy' ) === 1 ? 'https://www.youtube-nocookie.com/embed/' : 'https://www.youtube.com/embed/';
}

/*
 * Shared player markup. The iframe is only created on click (see js/ytpp-main.js).
 *
 * @param array $videos List of [ id, start, title, thumbnail, date ].
 * @param array $args   layout, autoadvance, schema.
 */
function ytpp_render( $videos, $args ) {
    if ( ! $videos ) {
        return '';
    }

    wp_enqueue_style( 'ytpp' );
    wp_enqueue_script_module( 'ytpp' );

    $layout = in_array( $args['layout'], [ 'below', 'side', 'grid' ], true ) ? $args['layout'] : 'below';
    $config = [
        'base'        => ytpp_embed_base(),
        'params'      => [
            'rel'      => (int) get_option( 'ytpp_rel' ),
            'controls' => (int) get_option( 'ytpp_controls' ),
        ],
        'autoadvance' => (bool) $args['autoadvance'],
    ];

    $items = '';

    foreach ( $videos as $index => $video ) {
        $label = $video['title'] !== ''
            /* translators: %s: video title */
            ? sprintf( __( 'Play %s', 'youtube-playlist-player' ), $video['title'] )
            /* translators: %d: video number */
            : sprintf( __( 'Play video %d', 'youtube-playlist-player' ), $index + 1 );

        $items .= '<li><button type="button" class="ytpp-item" data-video-id="' . esc_attr( $video['id'] ) . '" data-start="' . (int) $video['start'] . '" data-title="' . esc_attr( $video['title'] ) . '" aria-label="' . esc_attr( $label ) . '"' . ( $index === 0 && $layout !== 'grid' ? ' aria-current="true"' : '' ) . '>
            <img src="' . esc_url( $video['thumbnail'] ) . '" alt="" loading="lazy" decoding="async">' .
            ( $video['title'] !== '' ? '<span class="ytpp-item-title">' . esc_html( $video['title'] ) . '</span>' : '' ) .
        '</button></li>';
    }

    $out = '<div class="ytpp-main ytpp-layout-' . esc_attr( $layout ) . '" data-ytpp="' . esc_attr( wp_json_encode( $config ) ) . '">';

    if ( $layout === 'grid' ) {
        $out .= '<dialog class="ytpp-dialog" aria-label="' . esc_attr__( 'Video player', 'youtube-playlist-player' ) . '">
            <div class="ytpp-stage"></div>
            <button type="button" class="ytpp-dialog-close" aria-label="' . esc_attr__( 'Close video', 'youtube-playlist-player' ) . '">&times;</button>
        </dialog>';
    } else {
        $first = $videos[0];

        $out .= '<div class="ytpp-stage">
            <button type="button" class="ytpp-facade" data-video-id="' . esc_attr( $first['id'] ) . '" data-start="' . (int) $first['start'] . '" data-title="' . esc_attr( $first['title'] ) . '" aria-label="' . esc_attr( $first['title'] !== '' ? sprintf( /* translators: %s: video title */ __( 'Play %s', 'youtube-playlist-player' ), $first['title'] ) : __( 'Play video', 'youtube-playlist-player' ) ) . '">
                <img src="' . esc_url( $first['thumbnail'] ) . '" alt="" decoding="async">
                <span class="ytpp-play" aria-hidden="true"></span>
            </button>
        </div>';
    }

    $notice = (string) apply_filters( 'ytpp_facade_notice', (string) get_option( 'ytpp_facade_notice' ) );

    if ( $notice !== '' ) {
        $out .= '<p class="ytpp-notice">' . wp_kses_post( $notice ) . '</p>';
    }

    if ( count( $videos ) > 1 || $layout === 'grid' ) {
        $out .= '<ul class="ytpp-list" role="list">' . $items . '</ul>';
    }

    $out .= '</div>';

    if ( ! empty( $args['schema'] ) ) {
        $out .= ytpp_video_schema( $videos );
    }

    return $out;
}

function ytpp_video_schema( $videos ) {
    $graph = [];

    foreach ( $videos as $video ) {
        // Google requires name, thumbnailUrl and uploadDate; skip videos without API data.
        if ( $video['title'] === '' || $video['date'] === '' ) {
            continue;
        }

        $graph[] = [
            '@type'        => 'VideoObject',
            'name'         => $video['title'],
            'description'  => $video['description'] !== '' ? $video['description'] : $video['title'],
            'thumbnailUrl' => $video['thumbnail'],
            'uploadDate'   => $video['date'],
            'embedUrl'     => 'https://www.youtube.com/embed/' . $video['id'],
        ];
    }

    if ( ! $graph ) {
        return '';
    }

    return '<script type="application/ld+json">' . wp_json_encode(
        [
            '@context' => 'https://schema.org',
            '@graph'   => $graph,
        ],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
    ) . '</script>';
}

function ytpp_shortcode_atts( $atts, $extra = [] ) {
    return shortcode_atts(
        array_merge(
            [
                'mainid'      => '',
                'vdid'        => '',
                'start'       => 0,
                'autoadvance' => 0,
                'layout'      => 'below',
                'schema'      => 0,
            ],
            $extra
        ),
        $atts
    );
}

/*
 * Ordered video list: main video first, then the playlist without duplicates.
 */
function ytpp_collect_videos( $atts ) {
    $videos = array_merge( ytpp_parse_videos( $atts['mainid'] ), ytpp_parse_videos( $atts['vdid'] ) );
    $unique = [];

    foreach ( $videos as $video ) {
        $unique[ $video['id'] ] ??= $video;
    }

    $unique = array_values( $unique );

    if ( $unique && (int) $atts['start'] > 0 ) {
        $unique[0]['start'] = (int) $atts['start'];
    }

    return $unique;
}

/*
 * Static player/playlist (no API key needed)
 *
 * @return string
 */
function ytpp_player_show( $atts ) {
    $atts   = ytpp_shortcode_atts( $atts );
    $videos = array_map(
        fn( $video ) => $video + [
            'title'       => '',
            'description' => '',
            'date'        => '',
            'thumbnail'   => 'https://i.ytimg.com/vi/' . $video['id'] . '/hqdefault.jpg',
        ],
        ytpp_collect_videos( $atts )
    );

    return ytpp_render( $videos, $atts );
}

/*
 * Dynamic player/playlist with titles, using YouTube Data API v3 (fetched server-side).
 *
 * @return string
 */
function ytpp_apiplayer_show( $atts ) {
    $atts   = ytpp_shortcode_atts( $atts );
    $videos = ytpp_collect_videos( $atts );
    $data   = [];

    foreach ( array_chunk( array_column( $videos, 'id' ), 50 ) as $chunk ) {
        foreach ( ytpp_api_get( 'videos', [ 'part' => 'snippet', 'id' => implode( ',', $chunk ) ] ) as $item ) {
            $data[ $item['id'] ] = $item['snippet'] ?? [];
        }
    }

    foreach ( $videos as &$video ) {
        $snippet = $data[ $video['id'] ] ?? [];

        $video += [
            'title'       => (string) ( $snippet['title'] ?? '' ),
            'description' => wp_html_excerpt( (string) ( $snippet['description'] ?? '' ), 300 ),
            'date'        => (string) ( $snippet['publishedAt'] ?? '' ),
            'thumbnail'   => ytpp_thumbnail( $snippet, $video['id'] ),
        ];
    }
    unset( $video );

    return ytpp_render( $videos, $atts );
}

/*
 * Latest videos from one or more channels.
 *
 * @return array
 */
function ytpp_get_channel_videos( $channel_ids, $max_results = 9, $offset = 0 ) {
    $videos = [];

    foreach ( $channel_ids as $channel_id ) {
        $items = ytpp_api_get(
            'search',
            [
                'part'       => 'snippet',
                'type'       => 'video',
                'channelId'  => sanitize_text_field( trim( $channel_id ) ),
                // The API caps maxResults at 50.
                'maxResults' => min( 50, $max_results + $offset ),
                'order'      => 'date',
            ]
        );

        foreach ( $items as $item ) {
            $video_id = $item['id']['videoId'] ?? '';

            if ( $video_id === '' ) {
                continue;
            }

            $videos[] = [
                'id'          => $video_id,
                'start'       => 0,
                'title'       => (string) ( $item['snippet']['title'] ?? '' ),
                'description' => wp_html_excerpt( (string) ( $item['snippet']['description'] ?? '' ), 300 ),
                'date'        => (string) ( $item['snippet']['publishedAt'] ?? '' ),
                'thumbnail'   => ytpp_thumbnail( $item['snippet'] ?? [], $video_id ),
            ];
        }
    }

    return array_slice( $videos, $offset, $max_results );
}

function ytpp_feed_youtube( $atts ) {
    $atts = ytpp_shortcode_atts(
        $atts,
        [
            'channels' => '',
            'results'  => 9,
            'offset'   => 0,
            'layout'   => 'grid',
        ]
    );

    $videos = ytpp_get_channel_videos( explode( ',', $atts['channels'] ), max( 1, (int) $atts['results'] ), max( 0, (int) $atts['offset'] ) );

    return ytpp_render( $videos, $atts );
}
