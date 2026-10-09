=== Playlist Player for YouTube – Fast, Privacy-Friendly Playlists & Channel Feed ===
Contributors: butterflymedia
Donate link: https://buymeacoffee.com/wolffe
Tags: youtube, playlist, video, channel feed, lazy load
Requires at least: 6.5
Tested up to: 7.1.3
Requires PHP: 7.4
Stable tag: 4.9.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

For bloggers and businesses who want YouTube playlists and channel feeds that load fast and only contact YouTube when visitors press play.

== Description ==

Show a YouTube player with a playlist, or the latest videos from your channel, on any post or page with one shortcode. Videos are click-to-load: visitors see a lightweight thumbnail, and nothing is requested from YouTube until they press play. That keeps pages fast and makes it easier to respect cookie consent.

* Click-to-load players for faster pages and better privacy, with an optional notice under the player.
* Paste full YouTube URLs or video IDs. Add `?t=90` to a URL to start that video at 90 seconds.
* Three layouts: playlist below the player, beside it, or a grid that opens videos in an accessible lightbox.
* Auto-advance to the next video, several players on the same page, and keyboard-friendly playlists.
* Your YouTube API key stays on the server. Video titles and channel feeds are fetched and cached by WordPress.
* Optional `VideoObject` structured data for search engines.
* Privacy-enhanced (youtube-nocookie.com) mode.
* A shortcode generator in Settings, so you don't need to remember any attributes.

### Playlist (no API key needed)

Example: `[yt_playlist mainid="xcJtL7QggTI" vdid="xcJtL7QggTI, AheYbU8J5Tc, X0zGS4-UKgg, 74SZXCQb44s, 2M0XCH9q3YI"]`

### Playlist with video titles (YouTube Data API v3)

Example: `[yt_playlist_v3 mainid="xcJtL7QggTI" vdid="xcJtL7QggTI, AheYbU8J5Tc, X0zGS4-UKgg" layout="side" autoadvance="1"]`

### YouTube channel feed with lightbox player

Example: `[yt_feed channels="UCpVm7bg6pXKo1Pr6k5kxG9A" results="9" offset="0"]`

### Optional attributes

* `layout="below|side|grid"`
* `start="90"` (main video start time in seconds)
* `autoadvance="1"`
* `schema="1"` (VideoObject structured data, needs the API key)
* `offset="3"` (channel feed only)

Check out the [official Playlist Player for YouTube website](https://getbutterfly.com/wordpress-plugins/youtube-playlist-player/) and a [Playlist Player for YouTube demo](https://getbutterfly.com/wordpress-plugins/youtube-playlist-player/).

Check out a [Property Videos & Virtual Tours](https://kmproperty.ie/buy/videos-virtual-tours/) demo.

Check out more [WordPress plugins here](https://getbutterfly.com/wordpress-plugins/). Need cookie consent for YouTube? Try [WP Google Consent Platform (GCP)](https://getbutterfly.com/wordpress-plugins/wp-gcp-a-wordpress-plugin-for-google-consent-mode-v2/).

### From the same author

* [Active Contacts - WordPress CRM & Follow-up Plugin](https://getbutterfly.com/wordpress-plugins/active-contacts/)
* [LazyClone - Copy a WordPress Site Without FTP](https://getbutterfly.com/wordpress-plugins/lazyclone/)
* [Lighthouse - WordPress Performance & Speed Optimization Plugin](https://getbutterfly.com/wordpress-plugins/lighthouse/)
* [Active Analytics - Privacy-Friendly WordPress Analytics Plugin](https://getbutterfly.com/wordpress-plugins/active-analytics/)
* [ImagePress - WordPress Image Gallery & Community Photo Plugin](https://getbutterfly.com/wordpress-plugins/imagepress/)
* [eCards - WordPress eCard Plugin with Email Designer](https://getbutterfly.com/wordpress-plugins/wordpress-ecards-plugin/)
* [Repeater for Gravity Forms - Repeater Field Add-on](https://getbutterfly.com/wordpress-plugins/gravity-forms-repeater-plugin/)
* [Fixtures & Results - WordPress Sports League & GAA Club Plugin](https://getbutterfly.com/wordpress-plugins/fixtures-and-results/)
* [WP Google Consent Platform (GCP)](https://getbutterfly.com/wordpress-plugins/wp-gcp-a-wordpress-plugin-for-google-consent-mode-v2/)

== Installation ==

1. Upload to your plugins folder, usually `wp-content/plugins/`
2. Activate the plugin on the plugins screen.
3. Configure the plugin from Settings -> Playlist Player for YouTube.

== Screenshots ==

1. Front-end player #1
2. Front-end player #2
3. Dashboard
4. General Settings
5. YouTube API
6. Help/Usage

== Changelog ==
= 4.9.0 =
* SECURITY: The YouTube API key is no longer printed in the page; playlist titles are fetched and cached server-side
* FEATURE: Click-to-load players (no YouTube requests until a visitor presses play), with an optional notice
* FEATURE: Accept full YouTube URLs (watch, youtu.be, shorts, embed, live) and `?t=` start times
* FEATURE: New `layout`, `start`, `autoadvance` and `schema` attributes, and `offset` for channel feeds
* FEATURE: Several players on the same page
* FEATURE: Shortcode generator in Settings
* FIX: Channel feeds requested 100 results by default, above the YouTube API limit of 50; the default is now 9
* ACCESSIBILITY: Playlist items are buttons with labels, iframes have titles, the lightbox is a native dialog (Esc closes it, focus is kept inside)
* PERFORMANCE: One small ES module, loaded only on pages with a player
* REMOVED: The "fix for older browsers" option and its script
* UPDATE: Requires WordPress 6.5 (script modules) and PHP 7.4

= 4.8.3 =
* UPDATE: Tested up to WordPress 7.1
* DOCS: Add WordPress Plugins directory and donation link

= 4.8.2 =
* UPDATE: Tested up to WordPress 7.1
* DOCS: Add WordPress Plugins directory link


= 4.8.1 =
* FIX: Prevent caching of empty API responses to avoid persistent blank channel feeds
* FIX: Send `Referer` header with YouTube API requests for compatibility with referrer-restricted API keys

= 4.8.0 =
* PERFORMANCE: Cache YouTube API responses using transients to avoid API calls on every page load
* PERFORMANCE: Replace `file_get_contents()` with `wp_remote_get()` for remote API requests
* PERFORMANCE: Remove admin stylesheet and use native WordPress styles
* PERFORMANCE: Load admin assets only on the plugin settings page
* SECURITY: Escape YouTube API output in channel feed shortcode
* FIX: Add `allow="autoplay"` attribute to iframes for modern browser autoplay support
* FIX: Add autoplay to lightbox player in channel feed
* UPDATE: Add error handling for failed YouTube API requests

= 4.7.4 =
* FIX: Add `referrerpolicy="strict-origin-when-cross-origin"` to YouTube iframes to ensure HTTP Referer is sent per YouTube embedded player requirements
* UPDATE: Update WordPress compatibility

= 4.7.3 =
* UPDATE: Update WordPress compatibility

= 4.7.2 =
* UPDATE: Update WordPress compatibility
* SECURITY: Increase plugin security by sanitizing and unslashing options

= 4.7.1 =
* FIX: Fix a missing JavaScript dependency

= 4.7.0 =
* UPDATE: Update WordPress compatibility
* UPDATE: Update the channels shortcode to use Grid instead of Flexbox CSS
* UPDATE: Reduce the number of tags to 5

= 4.6.9 =
* UPDATE: Update WordPress compatibility

= 4.6.8 =
* FIX: Fix Cross Site Scripting (XSS) vulnerability (props yuyudhn via Patchstack)

= 4.6.7 =
* FIX: Fix missing scripts and styles for the new channel feed feature

= 4.6.6 =
* FEATURE: Add YouTube channel feeds
* UPDATE: Update WordPress compatibility

= 4.6.5 =
* FIX: Fix Cross Site Scripting (XSS) vulnerability (props Skalucy via Patchstack)
* UPDATE: Update PHP coding standards
* UPDATE: Update WordPress compatibility

= 4.6.4 =
* FIX: Fix Cross Site Scripting (XSS) vulnerability (props Yudha P. via Patchstack)
* UPDATE: Update copyright year
* UPDATE: Remove unused patterns from PHPCS ruleset

= 4.6.3 =
* UPDATE: Update author banner
* UPDATE: Update WordPress compatibility for pre-5.0 versions
* UPDATE: Update WPCS ruleset
* UPDATE: Replace back-end PNG image with inline SVG

= 4.6.2 =
* FIX: Fix wrong class in plugin documentation
* FIX: Clarify usage in plugin documentation and `readme.txt`
* UPDATE: Add cleanup routine after plugin uninstallation (delete 6 options)
* UPDATE: Update `readme.txt` with shortcodes and features

= 4.6.1 =
* FIX: Fix a content filtering issue with "Rate my post" plugin (props @sabelya)
* UPDATE: Update WordPress compatibility

= 4.6.0 =
* FIX: Remove a redundant variable
* UPDATE: Update WordPress compatibility
* UPDATE: Update codebase to conform to latest WordPress Coding Standards (WPCS) ruleset

= 4.5.9 =
* FIX: Fix documentation link
* UPDATE: Update WordPress compatibility

= 4.5.8 =
* UPDATE: Update PHP 8 compatibility
* UPDATE: Add lazy loading for iframes
* UPDATE: Implement strict use for JavaScript

= 4.5.7 =
* UPDATE: Update WordPress compatibility
* UPDATE: Remove old, unused code

= 4.5.6 =
* FIX: Fix aspect-ratio for Firefox and Safari (props @sabelya)
* UPDATE: Update PHP coding standards (function naming)
* UPDATE: Update plugin assets

= 4.5.5 =
* FIX: Sanitize URL parameter in back-end
* UPDATE: Combine and minify JavaScript
* UPDATE: Minify CSS
* UPDATE: Optimize DOM loaded functions
* PERFORMANCE: Remove `setInterval()` for detecting YouTube iframe
* PERFORMANCE: Add version number to CSS to break caching
* PERFORMANCE: Remove heavy JavaScript for detecting video aspect ratio
* PERFORMANCE: Implement modern CSS aspect-ratio for Core Web Vitals compatibility

= 4.5.4 =
* FIX: Fix YouTube API V3 demo
* FIX: Fix YouTube API V3 click event (switch to event delegation)
* UPDATE: Update classic playlist JavaScript to ES6
* UPDATE: Update `readme.txt` links
* UPDATE: Add donation link

= 4.5.3 =
* UPDATE: Update WordPress compatibility

= 4.5.2 =
* UPDATE: Update WordPress compatibility
* UPDATE: Update JavaScript to ES6
* FIX: Fix version number for enqueued scripts

= 4.5.1 =
* FIX: Fix issue with playlist not appearing
* FIX: Fix issue with playlist styling
* UPDATE: Refactor JS for less overhead
* UPDATE: Update WordPress compatibility
* UPDATE: Update demo link

= 4.5.0 =
* UPDATE: Update PHP requirements
* UPDATE: Update WordPress compatibility

= 4.4.1 =
* FIX: Fix a strict check
* FIX: Add spaces removal for V3 shortcode (main video)
* FIX: Add spaces removal for V3 shortcode (playlist)

= 4.4.0 =
* UPDATE: Update WordPress compatibility
* UPDATE: Remove jQuery dependency
* UPDATE: Force cache clearing for JavaScript actions

= 4.3.5 =
* UPDATE: Code quality fixes
* UPDATE: Update JavaScript DOM loading detection

= 4.3.4 =
* UPDATE: Update WordPress compatibility
* UPDATE: Mobile UI tweaks

= 4.3.3 =
* FIX: Fix localized issue not saving options

= 4.3.2 =
* UPDATE: Update WordPress compatibility
* UPDATE: Add new screenshots
* UPDATE: UI tweaks

= 4.3.1 =
* FIX: Remove old code
* UPDATE: Refactor and move player functions
* UPDATE: Add YouTube related options
* UPDATE: Remove unused option
* UPDATE: Add more documentation (+ YouTube API how-to)
* UPDATE: Add more/better YouTube branding

= 4.3 =
* FIX: Load JS/CSS assets only when shortcode is present
* FEATURE: Add YouTube API V3
* FEATURE: Add new settings screen
* FEATURE: Add new shortcode
* UPDATE: Add a bit of documentation
* UPDATE: Add more/better YouTube branding

= 4.2.4 =
* FIX: YouTube Branding fixes
* FIX: Author box layout fixes

= 4.2.3 =
* FIX: Regression fix for previous version (add interval checking)

= 4.2.2 =
* FIX: Fix player detection before being loaded

= 4.2.1 =
* FIX: Fix JS code being executed on all pages
* UPDATE: Update readme.txt

= 4.2 =
* FIX: Add PHP compatibility
* FIX: Fix/update old screenshots
* FIX: Remove jQuery dependency
* FIX: Fix JS codeflow
* UPDATE: Update WordPress compatibility
* UPDATE: Update readme.txt and general information

= 4.1.6 =
* FIX: Fix script being included before jQuery
* FIX: Fix duplicated variable assignment
* FIX: Fix strict variable assignment
* FIX: Remove unused colour picker script
* UPDATE: Update plugin usage details
* UPDATE: Small admin UI tweaks
* UPDATE: Remove `novd` argument and switch to internal count

= 4.1.5 =
* PERFORMANCE: Stop options from autoloading
* UPDATE: Update WordPress compatibility
* UPDATE: Better i18n options
* UPDATE: Remove unused colour option

= 4.1.4 =
* FIX: Remove version constant
* FIX: Better security tweaks
* UPDATE: Update admin menu name to reflect the plugin

= 4.1.3 =
* FIX: License update
* FIX: Official link update

= 4.1.2 =
* FIX: Fix color picker enqueue dependency
* UPDATE: Move all JS code to a separate file
* UPDATE: Change the main video playlist function (JS) to accept parameters

= 4.1.1 =
* FIX: Remove hardcoded background colour
* FIX: Remove hardcoded padding and increase margin
* FIX: Correctly enqueue style.css
* UPDATE: Update default height and add option autoloading
* UPDATE: Completely refactor YouTube Javascript
* UPDATE: Remove all Flash (SWFObject) dependencies

= 4.1.0 =
* FIX: Add `index.php` file to plugin root
* UPDATE: Update plugin URLs
* UPDATE: Update CSS styles for better compatibility

= 4.0.1 =
* UPDATE: Add getButterfly ad box

= 4.0.0 =
* FIX: Change all HTTP links to HTTPS
* FIX: Update YouTube API and remove all deprecated functions and parameters
* FIX: Remove parameters with same values as the default ones
* FIX: Clean up the code (slight performance increase)
* FIX: Fix rare cases of line ending issues
* UI: Remove background color for better theme integration

= 3.2.0 =
* FIX: Fix IFRAME name target
* FEATURE: Add responsiveness

= 3.1.0 =
* FIX: Fix a PHP warning
* FIX: Remove deprecated options nonce
* FEATURE: Add usage details on the plugin page
* PROMOTION: Add link to premium version on CodeCanyon

= 3.0.2 =
* Add license link
* Add donate link
* Add default options
* Fix wrong internal version

= 3.0.1 =
* Add CSS vendor prefixes

= 3.0.0 =
* Initial release

