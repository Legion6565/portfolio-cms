import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'

const editors = new Map()

function getFormats(editor) {
    return {
        bold: editor.isActive('bold'),
        italic: editor.isActive('italic'),
    }
}

window.EditorRegistry = {

    mount(id, element, hiddenInput, initialContent = '', { onChange, onActiveStateChange } = {}) {

        if (editors.has(id)) {
            return
        }

        const editor = new Editor({

            element,

            extensions: [
                // Minimal formatting only: bold + italic.
                StarterKit.configure({
                    heading: false,
                    bulletList: false,
                    orderedList: false,
                    listItem: false,
                }),
            ],

            content: initialContent || '',

            editable: true,

            autofocus: false,

            injectCSS: false,

            editorProps: {
                attributes: {
                    class: 'ProseMirror',
                },
            },

            onUpdate({ editor }) {

                const html = editor.getHTML()

                hiddenInput.value = html

                onChange?.(html)
            },

            onTransaction({ editor }) {
                onActiveStateChange?.(getFormats(editor))
            },

            onSelectionUpdate({ editor }) {
                onActiveStateChange?.(getFormats(editor))
            },
        })

        editors.set(id, editor)

        hiddenInput.value = editor.getHTML()
        onActiveStateChange?.(getFormats(editor))
    },

    command(id, name) {

        const editor = editors.get(id)

        if (!editor) {
            return
        }

        const chain = editor.chain().focus()

        switch (name) {
            case 'bold':
                chain.toggleBold().run()
                break
            case 'italic':
                chain.toggleItalic().run()
                break
        }
    },

    focus(id) {

        const editor = editors.get(id)

        if (!editor) {
            return
        }

        editor.chain().focus().run()
    },

    destroy(id) {

        const editor = editors.get(id)

        if (!editor) {
            return
        }

        editor.destroy()
        editors.delete(id)
    },
}
