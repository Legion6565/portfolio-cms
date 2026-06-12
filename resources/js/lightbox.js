import Alpine from 'alpinejs'

/**
 * Fullscreen image viewer for the public case-study page.
 *
 * Each clickable image carries `data-lb-src` (full-size URL) and
 * `data-lb-group` (images sharing a group navigate together — a gallery,
 * or a composition's stacked images). Opening collects the group from the
 * DOM, so no image data has to be threaded through Blade.
 */
Alpine.data('lightbox', () => ({

    shown: false,
    images: [],
    index: 0,

    open(el) {
        const group = el.dataset.lbGroup
        const nodes = Array.from(
            this.$root.querySelectorAll(`[data-lb-group="${group}"]`)
        )
        this.images = nodes.map(n => n.dataset.lbSrc)
        this.index = Math.max(0, nodes.indexOf(el))
        this.shown = true
        document.body.style.overflow = 'hidden'
        this.preload()
    },

    close() {
        this.shown = false
        document.body.style.overflow = ''
    },

    next() {
        if (this.images.length < 2) return
        this.index = (this.index + 1) % this.images.length
        this.preload()
    },

    prev() {
        if (this.images.length < 2) return
        this.index = (this.index - 1 + this.images.length) % this.images.length
        this.preload()
    },

    get current() {
        return this.images[this.index] || ''
    },

    get hasMultiple() {
        return this.images.length > 1
    },

    // Warm the browser cache for the neighbouring images.
    preload() {
        [this.index - 1, this.index + 1].forEach(i => {
            if (!this.images.length) return
            const src = this.images[(i + this.images.length) % this.images.length]
            if (src) {
                const img = new Image()
                img.src = src
            }
        })
    },
}))
