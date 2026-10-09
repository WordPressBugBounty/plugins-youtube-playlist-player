const loadIframeApi = () => {
    if (window.YT?.Player) {
        return Promise.resolve(window.YT);
    }

    loadIframeApi.promise ??= new Promise((resolve) => {
        const previous = window.onYouTubeIframeAPIReady;

        window.onYouTubeIframeAPIReady = () => {
            previous?.();
            resolve(window.YT);
        };

        document.head.append(Object.assign(document.createElement('script'), { src: 'https://www.youtube.com/iframe_api' }));
    });

    return loadIframeApi.promise;
};

const initPlayer = (root) => {
    const config = JSON.parse(root.dataset.ytpp);
    const items = [...root.querySelectorAll('.ytpp-item')];
    const dialog = root.querySelector('.ytpp-dialog');
    const stage = root.querySelector('.ytpp-stage');
    let current = 0;

    const play = (index) => {
        const item = items[index] ?? root.querySelector('.ytpp-facade');
        const params = new URLSearchParams({
            ...config.params,
            autoplay: 1,
            playsinline: 1,
            start: item.dataset.start || 0,
            enablejsapi: config.autoadvance ? 1 : 0,
            origin: location.origin,
        });
        const iframe = Object.assign(document.createElement('iframe'), {
            src: `${config.base}${item.dataset.videoId}?${params}`,
            title: item.dataset.title || 'YouTube video',
            allow: 'autoplay; encrypted-media; picture-in-picture; fullscreen',
            referrerPolicy: 'strict-origin-when-cross-origin',
        });

        current = index;
        stage.replaceChildren(iframe);
        items.forEach((el, i) => el.toggleAttribute('aria-current', i === index && !dialog));

        if (dialog && !dialog.open) {
            dialog.showModal();
        }

        if (config.autoadvance && index < items.length - 1) {
            loadIframeApi().then((YT) => new YT.Player(iframe, {
                events: {
                    onStateChange: (event) => event.data === YT.PlayerState.ENDED && play(current + 1),
                },
            }));
        }
    };

    root.querySelector('.ytpp-facade')?.addEventListener('click', () => play(0));
    items.forEach((item, index) => item.addEventListener('click', () => play(index)));

    if (dialog) {
        dialog.querySelector('.ytpp-dialog-close').addEventListener('click', () => dialog.close());
        dialog.addEventListener('click', (event) => event.target === dialog && dialog.close());
        dialog.addEventListener('close', () => {
            stage.replaceChildren();
            items[current]?.focus();
        });
    }
};

document.querySelectorAll('.ytpp-main[data-ytpp]').forEach(initPlayer);
