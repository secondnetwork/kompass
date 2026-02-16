<div
    x-data="tiptapEditor('{{ $editorId }}', {{ $readOnly ? 'true' : 'false' }}, '{{ $placeholder }}')"
    x-init="initEditor()"
    class="{{ $class }}"
    style="{{ $style }}"
    wire:ignore
>
    <div class="tiptap-toolbar flex flex-wrap gap-1 p-2 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-t-lg">
        <div class="flex flex-wrap gap-1">
            <button type="button" x-on:click="toggleBold()" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('bold') }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Bold">
                <x-tabler-bold class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="toggleItalic()" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('italic') }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Italic">
                <x-tabler-italic class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="toggleUnderline()" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('underline') }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Underline">
                <x-tabler-underline class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="toggleStrike()" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('strike') }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Strikethrough">
                <x-tabler-strikethrough class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="toggleCode()" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('code') }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Code">
                <x-tabler-code class="w-4 h-4" />
            </button>
        </div>

        <div class="w-px h-8 bg-gray-300 dark:bg-gray-600 mx-1"></div>

        <div class="flex flex-wrap gap-1">
            <button type="button" x-on:click="toggleHeading(1)" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('heading', { level: 1 }) }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Heading 1">
                <x-tabler-h-1 class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="toggleHeading(2)" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('heading', { level: 2 }) }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Heading 2">
                <x-tabler-h-2 class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="toggleHeading(3)" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('heading', { level: 3 }) }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Heading 3">
                <x-tabler-h-3 class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="toggleHeading(4)" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('heading', { level: 4 }) }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Heading 4">
                <x-tabler-h-4 class="w-4 h-4" />
            </button>
        </div>

        <div class="w-px h-8 bg-gray-300 dark:bg-gray-600 mx-1"></div>

        <div class="flex flex-wrap gap-1">
            <button type="button" x-on:click="toggleBulletList()" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('bulletList') }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Bullet List">
                <x-tabler-list class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="toggleOrderedList()" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('orderedList') }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Ordered List">
                <x-tabler-numbered-list class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="toggleBlockquote()" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('blockquote') }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Quote">
                <x-tabler-quote class="w-4 h-4" />
            </button>
        </div>

        <div class="w-px h-8 bg-gray-300 dark:bg-gray-600 mx-1"></div>

        <div class="flex flex-wrap gap-1">
            <button type="button" x-on:click="setLink()" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('link') }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Link">
                <x-tabler-link class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="addImage()" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Image">
                <x-tabler-photo class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="insertTable()" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('table') }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Table">
                <x-tabler-table class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="toggleCodeBlock()" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive('codeBlock') }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Code Block">
                <x-tabler-code-dots class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="setHorizontalRule()" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Horizontal Rule">
                <x-tabler-line-dashed class="w-4 h-4" />
            </button>
        </div>

        <div class="w-px h-8 bg-gray-300 dark:bg-gray-600 mx-1"></div>

        <div class="flex flex-wrap gap-1">
            <button type="button" x-on:click="setTextAlign('left')" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive({ textAlign: 'left' }) }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Align Left">
                <x-tabler-align-left class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="setTextAlign('center')" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive({ textAlign: 'center' }) }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Align Center">
                <x-tabler-align-center class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="setTextAlign('right')" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive({ textAlign: 'right' }) }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Align Right">
                <x-tabler-align-right class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="setTextAlign('justify')" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': isActive({ textAlign: 'justify' }) }" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Align Justify">
                <x-tabler-align-justified class="w-4 h-4" />
            </button>
        </div>

        <div class="w-px h-8 bg-gray-300 dark:bg-gray-600 mx-1"></div>

        <div class="flex flex-wrap gap-1">
            <button type="button" x-on:click="undo()" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Undo">
                <x-tabler-arrow-back-up class="w-4 h-4" />
            </button>
            <button type="button" x-on:click="redo()" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Redo">
                <x-tabler-arrow-forward-up class="w-4 h-4" />
            </button>
        </div>
    </div>

    <div id="tiptap-editor-{{ $editorId }}" class="tiptap-content prose prose-sm dark:prose-invert max-w-none p-4 min-h-[200px] focus:outline-none rounded-b-lg"></div>

    <input type="hidden" wire:model.live="data" id="hidden-data-{{ $editorId }}">
</div>
