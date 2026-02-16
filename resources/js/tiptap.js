import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Placeholder from '@tiptap/extension-placeholder';
import TextAlign from '@tiptap/extension-text-align';
import Table from '@tiptap/extension-table';
import TableRow from '@tiptap/extension-table-row';
import TableCell from '@tiptap/extension-table-cell';
import TableHeader from '@tiptap/extension-table-header';
import CodeBlock from '@tiptap/extension-code-block';

document.addEventListener('alpine:init', () => {
    Alpine.data('tiptapEditor', (editorId, readOnly = false, placeholder = 'Write something...') => {
        let editor = null;
        let livewireData = null;

        return {
            editor: null,
            readOnly: readOnly,
            placeholder: placeholder,
            editorId: editorId,

            initEditor() {
                const element = document.getElementById(`tiptap-editor-${this.editorId}`);
                const hiddenInput = document.getElementById(`hidden-data-${this.editorId}`);

                if (!element) return;

                // Get initial content from hidden input
                const initialContent = hiddenInput?.value || '';

                editor = new Editor({
                    element: element,
                    extensions: [
                        StarterKit.configure({
                            codeBlock: false,
                        }),
                        Underline,
                        Link.configure({
                            openOnClick: false,
                            HTMLAttributes: {
                                class: 'text-blue-600 underline hover:text-blue-800',
                            },
                        }),
                        Image.configure({
                            HTMLAttributes: {
                                class: 'max-w-full h-auto rounded-lg',
                            },
                        }),
                        Placeholder.configure({
                            placeholder: this.placeholder,
                        }),
                        TextAlign.configure({
                            types: ['heading', 'paragraph'],
                        }),
                        Table.configure({
                            resizable: true,
                        }),
                        TableRow,
                        TableCell,
                        TableHeader,
                        CodeBlock.configure({
                            HTMLAttributes: {
                                class: 'bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto',
                            },
                        }),
                    ],
                    content: initialContent ? initialContent : '',
                    editable: !this.readOnly,
                    onUpdate: ({ editor }) => {
                        const html = editor.getHTML();
                        hiddenInput.value = html;
                        hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    },
                });

                this.editor = editor;
            },

            toggleBold() {
                editor?.chain().focus().toggleBold().run();
            },

            toggleItalic() {
                editor?.chain().focus().toggleItalic().run();
            },

            toggleUnderline() {
                editor?.chain().focus().toggleUnderline().run();
            },

            toggleStrike() {
                editor?.chain().focus().toggleStrike().run();
            },

            toggleCode() {
                editor?.chain().focus().toggleCode().run();
            },

            toggleHeading(level) {
                editor?.chain().focus().toggleHeading({ level: level }).run();
            },

            toggleBulletList() {
                editor?.chain().focus().toggleBulletList().run();
            },

            toggleOrderedList() {
                editor?.chain().focus().toggleOrderedList().run();
            },

            toggleBlockquote() {
                editor?.chain().focus().toggleBlockquote().run();
            },

            toggleCodeBlock() {
                editor?.chain().focus().toggleCodeBlock().run();
            },

            setLink() {
                const previousUrl = editor?.getAttributes('link').href;
                const url = window.prompt('URL', previousUrl);

                if (url === null) return;

                if (url === '') {
                    editor?.chain().focus().extendMarkRange('link').unsetLink().run();
                    return;
                }

                editor?.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
            },

            addImage() {
                const url = window.prompt('Image URL');

                if (url) {
                    editor?.chain().focus().setImage({ src: url }).run();
                }
            },

            insertTable() {
                editor?.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run();
            },

            setHorizontalRule() {
                editor?.chain().focus().setHorizontalRule().run();
            },

            setTextAlign(align) {
                editor?.chain().focus().setTextAlign(align).run();
            },

            undo() {
                editor?.chain().focus().undo().run();
            },

            redo() {
                editor?.chain().focus().redo().run();
            },

            isActive(name, attributes = {}) {
                return editor?.isActive(name, attributes) || false;
            },

            destroy() {
                editor?.destroy();
            },
        };
    });
});
