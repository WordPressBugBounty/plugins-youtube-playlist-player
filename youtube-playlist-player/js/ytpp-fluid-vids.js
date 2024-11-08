'use strict';

(function (window, document, undefined) {
    /**
     * Grab all iframes on the page or return
     */
    let iframes = document.getElementsByTagName('iframe');

    /**
     * Loop through the iframes array
     */
    for (let i = 0; i < iframes.length; i++) {
        /**
         * RegExp, extend this if you need more players
         */
        let iframe = iframes[i],
            players = /www.youtube.com|player.vimeo.com/;

        /**
         * If the RegExp pattern exists within the current iframe
         */
        if (iframe.src.search(players) > 0) {
            /**
             * Calculate the video ratio based on the iframe's w/h dimensions
             */
            let videoRatio = (iframe.height / iframe.width) * 100;

            /**
             * Replace the iframe's dimensions and position the iframe absolute,
             * this is the trick to emulate the video ratio
             */
            iframe.style.position = 'absolute';
            iframe.style.top = '0';
            iframe.style.left = '0';
            iframe.width = '100%';
            iframe.height = '100%';

            /**
             * Wrap the iframe in a new <div> which uses a dynamically fetched
             * padding-top property based on the video's w/h dimensions
             */
            let wrap = document.createElement('div');
            wrap.className = 'fluid-vids';
            wrap.style.width = '100%';
            wrap.style.position = 'relative';
            wrap.style.paddingTop = videoRatio + '%';

            /**
             * Add the iframe inside our newly created <div>
             */
            let iframeParent = iframe.parentNode;
            iframeParent.insertBefore(wrap, iframe);
            wrap.appendChild(iframe);
        }
    }
})(window, document);
