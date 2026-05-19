/**
 * A lightweight Vimeo embed. Still should feel the same to the user, just MUCH faster to initialize and paint.
 *
 * Ported from https://github.com/paulirish/lite-youtube-embed
 * Based on https://github.com/slightlyoff/lite-vimeo
 */
class LiteVimeoEmbed extends HTMLElement {
    connectedCallback() {
        this.videoId = encodeURIComponent(this.getAttribute('videoid') || '');

        const poster = this.getAttribute('poster');
        if (poster) {
            this.style.backgroundImage = `url("${poster}")`;
        } else {
            this.initThumbnailFromApi();
        }

        let playBtnEl = this.querySelector('.ltv-playbtn');
        if (!playBtnEl) {
            playBtnEl = document.createElement('button');
            playBtnEl.type = 'button';
            playBtnEl.classList.add('ltv-playbtn');
            this.append(playBtnEl);
        }

        this.addEventListener('pointerover', LiteVimeoEmbed.warmConnections, { once: true });
        this.addEventListener('click', () => this.addIframe());
    }

    async initThumbnailFromApi() {
        LiteVimeoEmbed.addPrefetch('preconnect', 'https://i.vimeocdn.com/');

        // Uses oEmbed API — v2 API is shut down
        const apiUrl = `https://vimeo.com/api/oembed.json?url=https://vimeo.com/${this.videoId}`;
        try {
            const apiResponse = await (await fetch(apiUrl)).json();
            const tnLarge = apiResponse.thumbnail_url;
            const imgId = (tnLarge.substr(tnLarge.lastIndexOf('/') + 1)).split('_')[0];
            const webpUrl = `https://i.vimeocdn.com/video/${imgId}.webp?mw=1100&mh=619&q=70`;
            const jpgUrl = `https://i.vimeocdn.com/video/${imgId}.jpg?mw=1100&mh=619&q=70`;

            const img = new Image();
            img.onload = () => { this.style.backgroundImage = `url("${webpUrl}")`; };
            img.onerror = () => { this.style.backgroundImage = `url("${jpgUrl}")`; };
            img.src = webpUrl;
        } catch (e) {
            // Thumbnail fetch failed silently — video still playable on click
        }
    }

    addIframe() {
        if (this.classList.contains('ltv-activated')) return;
        this.classList.add('ltv-activated');

        const srcUrl = new URL(`https://player.vimeo.com/video/${this.videoId}`);
        srcUrl.searchParams.set('dnt', '1');
        srcUrl.searchParams.set('autoplay', '1');

        const videoHash = this.getAttribute('videohash');
        if (videoHash) { srcUrl.searchParams.set('h', encodeURIComponent(videoHash)); }

        const startAt = this.getAttribute('start');
        if (startAt) { srcUrl.hash = `t=${startAt}`; }

        const iframeEl = document.createElement('iframe');
        iframeEl.setAttribute('frameborder', '0');
        iframeEl.setAttribute('allow', 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture');
        iframeEl.setAttribute('allowfullscreen', '');
        iframeEl.src = srcUrl.toString();
        this.append(iframeEl);
        iframeEl.focus();
    }

    static addPrefetch(kind, url, as) {
        const linkEl = document.createElement('link');
        linkEl.rel = kind;
        linkEl.href = url;
        if (as) { linkEl.as = as; }
        document.head.append(linkEl);
    }

    static warmConnections() {
        if (LiteVimeoEmbed.preconnected) return;
        LiteVimeoEmbed.addPrefetch('preconnect', 'https://player.vimeo.com');
        LiteVimeoEmbed.addPrefetch('preconnect', 'https://i.vimeocdn.com');
        LiteVimeoEmbed.addPrefetch('preconnect', 'https://f.vimeocdn.com');
        LiteVimeoEmbed.preconnected = true;
    }
}

LiteVimeoEmbed.preconnected = false;
customElements.define('lite-vimeo', LiteVimeoEmbed);
