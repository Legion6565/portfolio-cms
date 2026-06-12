import Alpine from 'alpinejs'

/**
 * Alpine component backing the project create/edit form + Live Preview.
 *
 * `compositions` mirrors resources/views/partials/case-study.blade.php:
 *   1. MANUAL grouping — contiguous sections sharing a non-empty meta.group form
 *      one composition; author picks media side via meta.layout (left|right).
 *   2. AUTO fallback — ungrouped sections compose automatically (title/text +
 *      image → showcase with alternating sides, etc.).
 *
 * Keep this logic and the class vocabulary in sync with the Blade renderer.
 */
Alpine.data('caseStudyForm', (init = {}) => ({

    previewTitle: init.title ?? '',
    previewDescription: init.description ?? '',
    previewImage: init.image ?? null,
    sections: init.sections ?? [],

    widthClass(w) {
        switch (w) {
            case 'narrow': return 'max-w-2xl mx-auto'
            case 'wide':   return 'max-w-6xl mx-auto'
            case 'full':   return 'max-w-none'
            default:       return 'max-w-4xl mx-auto'
        }
    },

    isDark(c)     { return c.meta?.theme === 'dark' },
    hasText(c)    { return !!(c.title || c.bodies.length) },
    hasMedia(c)   { return !!(c.images.length || c.gallery) },
    mediaLeft(c)  { return (c.layout || 'left') === 'left' },

    gallerySpan(i, count) {
        if (count === 1) return 'col-span-2 row-span-2 md:col-span-6'
        if (count === 2) return 'col-span-1 row-span-2 md:col-span-3'
        if (count === 3) return i === 0
            ? 'col-span-2 row-span-2 md:col-span-4'
            : 'col-span-1 md:col-span-2'
        const pattern = [
            'col-span-2 row-span-2 md:col-span-4',
            'col-span-1 md:col-span-2',
            'col-span-1 md:col-span-2',
            'col-span-1 md:col-span-3',
            'col-span-1 md:col-span-3',
        ]
        return pattern[i % pattern.length]
    },

    get compositions() {
        // ── Split into runs (manual groups vs auto runs) ──
        const runs = []
        for (const s of this.sections) {
            const g = (s.meta?.group || '').trim()
            const last = runs[runs.length - 1]
            if (g) {
                if (last && last.type === 'explicit' && last.group === g) last.sections.push(s)
                else runs.push({ type: 'explicit', group: g, sections: [s] })
            } else {
                if (last && last.type === 'auto') last.sections.push(s)
                else runs.push({ type: 'auto', sections: [s] })
            }
        }

        const comps = []
        let mediaCounter = 0
        const metaOf = (secs) =>
            secs.find(s => s && s.meta)?.meta ?? { width: 'normal', theme: 'light' }

        const make = (secs, explicitLayout) => {
            const title = secs.find(s => s.type === 'title') || null
            const bodies = secs.filter(s => s.type === 'text')
            const images = secs.filter(s => s.type === 'image')
            const gallery = secs.find(s => s.type === 'gallery') || null
            // Width/theme come from the group's leading section (original order).
            const meta = metaOf(secs)
            const hasMedia = images.length > 0 || gallery
            let layout
            if (explicitLayout) layout = explicitLayout
            else layout = hasMedia ? (mediaCounter % 2 === 0 ? 'left' : 'right') : 'left'
            if (hasMedia) mediaCounter++
            return { title, bodies, images, gallery, meta, layout }
        }

        for (const run of runs) {
            if (run.type === 'explicit') {
                // Orientation only comes from media sections (title/text never carry it).
                let layout = null
                for (const s of run.sections) {
                    if ((s.type === 'image' || s.type === 'gallery') && s.meta?.layout) {
                        layout = s.meta.layout
                        break
                    }
                }
                comps.push(make(run.sections, layout || 'left'))
            } else {
                let pending = []
                for (const s of run.sections) {
                    if (s.type === 'title' || s.type === 'text') {
                        if (s.type === 'title' && pending.length) { comps.push(make(pending, null)); pending = [] }
                        pending.push(s)
                        continue
                    }
                    if (s.type === 'image') {
                        if (pending.length) { pending.push(s); comps.push(make(pending, null)); pending = [] }
                        else comps.push(make([s], null))
                        continue
                    }
                    if (s.type === 'gallery') {
                        if (pending.length) { comps.push(make(pending, null)); pending = [] }
                        comps.push(make([s], null))
                    }
                }
                if (pending.length) comps.push(make(pending, null))
            }
        }
        return comps
    },
}))
