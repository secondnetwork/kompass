@php
    $iconClass = 'w-4 h-4 stroke-2';
    $blockTypes = [
        ['id' => 'p',          'label' => __('Paragraph'),     'icon' => svg('tabler-pilcrow',          $iconClass)->toHtml(), 'desc' => __('Plain text paragraph')],
        ['id' => 'preline',    'label' => __('Preline'),       'icon' => svg('tabler-letter-p',         $iconClass)->toHtml(), 'desc' => __('Pre-headline')],
        ['id' => 'h1',         'label' => __('Heading 1'),     'icon' => svg('tabler-h-1',              $iconClass)->toHtml(), 'desc' => __('Large section heading')],
        ['id' => 'h2',         'label' => __('Heading 2'),     'icon' => svg('tabler-h-2',              $iconClass)->toHtml(), 'desc' => __('Medium section heading')],
        ['id' => 'h3',         'label' => __('Heading 3'),     'icon' => svg('tabler-h-3',              $iconClass)->toHtml(), 'desc' => __('Small section heading')],
        ['id' => 'h4',         'label' => __('Heading 4'),     'icon' => svg('tabler-h-4',              $iconClass)->toHtml(), 'desc' => __('Extra small heading')],
        ['id' => 'h5',         'label' => __('Heading 5'),     'icon' => svg('tabler-h-5',              $iconClass)->toHtml(), 'desc' => __('Tiny heading')],
        ['id' => 'h6',         'label' => __('Heading 6'),     'icon' => svg('tabler-h-6',              $iconClass)->toHtml(), 'desc' => __('Subtle heading')],
        ['id' => 'subtitle',   'label' => __('Subtitle'),      'icon' => svg('tabler-text-caption',     $iconClass)->toHtml(), 'desc' => __('Descriptive subline')],
        ['id' => 'li',         'label' => __('Bulleted List'), 'icon' => svg('tabler-list',             $iconClass)->toHtml(), 'desc' => __('Simple bulleted list')],
        ['id' => 'oli',        'label' => __('Numbered List'), 'icon' => svg('tabler-list-numbers',     $iconClass)->toHtml(), 'desc' => __('Numbered list')],
        ['id' => 'blockquote', 'label' => __('Quote'),         'icon' => svg('tabler-blockquote',       $iconClass)->toHtml(), 'desc' => __('Capture a quote')],
    ];
    $prelinePlaceholder = __('PRELINE TEXT');
@endphp

<div class="editor-container border border-base-300 rounded w-full py-4">
<div
    wire:ignore
    x-data="{
        ...window.kompassEditorFactory({
            blockTypes: @js($blockTypes),
            prelinePlaceholder: @js($prelinePlaceholder),
        }),
        blocks: @entangle('blocks').live,
        placeholder: @js($placeholder),
        readOnly: @js($readOnly),
    }"
>
    <div class="relative">
        <template x-for="(block, index) in blocks" :key="block.id">
            <div
                class="relative group flex items-start ml-16 transition-all duration-200"
                :class="{
                    'opacity-40 border-l-2 border-base-300 bg-base-200/50': dragIndex === index,
                    'border-t-2 border-base-300': dropTargetIndex === index && dragIndex !== index,
                    'not-oli': block.type !== 'oli'
                }"
                @dragover.prevent="dropTargetIndex = index"
                @dragleave="if (dropTargetIndex === index) dropTargetIndex = null"
                @drop="dragDrop(index)"
            >
                {{-- Side Actions (Plus + Drag Handle / Tune) --}}
                <div class="flex items-center opacity-0 group-hover:opacity-100 transition-opacity pr-2 py-1.5 w-20 -ml-20 justify-end" x-show="!readOnly">
                    <div class="relative">
                        <button
                            type="button"
                            @click="togglePlusMenu(index)"
                            class="p-1 hover:bg-base-200 rounded text-base-content transition"
                            title="{{ __('Neuer Block mit Format') }}"
                        >
                            <x-tabler-plus class="w-4 h-4 stroke-2" />
                        </button>

                        <div
                            x-show="showAddMenu && addMenuIndex === index"
                            x-transition
                            class="absolute z-[80] top-full left-0 mt-1 w-64 bg-base-100 border border-base-300 rounded-xl shadow-xl py-2 max-h-60 overflow-y-auto kompass-fade-in"
                            @click.outside="showAddMenu = false"
                            style="display: none;"
                        >
                            <span class="block px-3 py-1 text-[10px] font-bold text-base-content/60 uppercase tracking-wider">{{ __('Neu erstellen') }}</span>
                            <template x-for="type in blockTypes" :key="type.id">
                                <button
                                    type="button"
                                    @click="addNewBlockWithType(index, type.id)"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-left hover:bg-base-200 transition"
                                >
                                    <span class="w-8 h-8 flex items-center justify-center rounded border border-base-300 bg-base-200 text-base-content" x-html="type.icon"></span>
                                    <div>
                                        <span class="block text-xs font-semibold text-base-content" x-text="type.label"></span>
                                        <span class="block text-[10px] text-base-content/60" x-text="type.desc"></span>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div
                        class="p-1 cursor-grab active:cursor-grabbing text-base-content transition relative"
                        title="{{ __('Verschieben oder Einstellen') }}"
                        draggable="true"
                        @dragstart="dragStart(index, $event)"
                        @dragend="dragEnd()"
                        @click="toggleTuneMenu(index)"
                    >
                        <x-tabler-grip-vertical class="w-4 h-4" />

                        <div
                            x-show="showTuneMenu && tuneIndex === index"
                            x-transition
                            class="absolute z-[70] top-full left-0 mt-1 w-48 bg-base-100 border border-base-300 rounded-xl shadow-xl py-2 kompass-fade-in text-base-content"
                            @click.outside="showTuneMenu = false"
                            style="display: none;"
                        >
                            <template x-if="block.type === 'li' || block.type === 'oli'">
                                <div>
                                    <button type="button" @click.stop="selectBlockType(index, 'li')" class="w-full flex items-center gap-3 px-3 py-2 text-left hover:bg-base-200 transition" :class="block.type === 'li' ? 'text-primary bg-primary/10' : ''">
                                        <x-tabler-list class="w-4 h-4 stroke-2" />
                                        <span class="text-xs font-medium">{{ __('Unordered') }}</span>
                                    </button>
                                    <button type="button" @click.stop="selectBlockType(index, 'oli')" class="w-full flex items-center gap-3 px-3 py-2 text-left hover:bg-base-200 transition" :class="block.type === 'oli' ? 'text-primary bg-primary/10' : ''">
                                        <x-tabler-list-numbers class="w-4 h-4 stroke-2" />
                                        <span class="text-xs font-medium">{{ __('Ordered') }}</span>
                                    </button>
                                    <div class="h-px bg-base-200 my-1"></div>
                                </div>
                            </template>

                            <button type="button" @click.stop="showDropdown = true; dropdownIndex = index; showTuneMenu = false" class="w-full flex items-center justify-between px-3 py-2 text-left hover:bg-base-200 transition">
                                <div class="flex items-center gap-3">
                                    <x-tabler-transform class="w-4 h-4 stroke-2" />
                                    <span class="text-xs font-medium">{{ __('Convert to') }}</span>
                                </div>
                                <x-tabler-chevron-right class="w-3 h-3 stroke-2" />
                            </button>

                            <div class="h-px bg-base-200 my-1"></div>

                            <button type="button" @click.stop="moveBlock(index, -1)" class="w-full flex items-center gap-3 px-3 py-2 text-left hover:bg-base-200 transition">
                                <x-tabler-chevron-up class="w-4 h-4 stroke-2" />
                                <span class="text-xs font-medium">{{ __('Move up') }}</span>
                            </button>
                            <button type="button" @click.stop="deleteBlock(index)" class="w-full flex items-center gap-3 px-3 py-2 text-left hover:bg-base-200 transition text-error">
                                <x-tabler-x class="w-4 h-4 stroke-2" />
                                <span class="text-xs font-medium">{{ __('Delete') }}</span>
                            </button>
                            <button type="button" @click.stop="moveBlock(index, 1)" class="w-full flex items-center gap-3 px-3 py-2 text-left hover:bg-base-200 transition">
                                <x-tabler-chevron-down class="w-4 h-4 stroke-2" />
                                <span class="text-xs font-medium">{{ __('Move down') }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Block content --}}
                <div
                    :id="block.id"
                    :contenteditable="readOnly ? 'false' : 'true'"
                    class="w-full py-1 prose prose-zinc max-w-none focus:outline-none"
                    :class="{
                        'text-3xl font-bold tracking-tight text-base-content mb-2': block.type === 'h1',
                        'text-2xl font-bold tracking-tight text-base-content mb-2': block.type === 'h2',
                        'text-xl font-semibold text-base-content mb-1': block.type === 'h3',
                        'text-lg font-semibold text-base-content mb-1': block.type === 'h4',
                        'text-base font-semibold text-base-content mb-1': block.type === 'h5',
                        'text-sm font-semibold text-base-content tracking-wide mb-1': block.type === 'h6',
                        'text-sm font-medium text-base-content uppercase leading-relaxed mb-4': block.type === 'subtitle',
                        'text-base font-bold uppercase mb-1': block.type === 'preline',
                        'text-base text-base-content/70 leading-relaxed': block.type === 'p',
                        'list-item li-item list-disc ml-8 text-base-content/70': block.type === 'li',
                        'list-item oli-item ml-8 text-base-content/70 mb-1': block.type === 'oli',
                        'border-l-4 border-base-300 pl-4 italic text-base-content my-2': block.type === 'blockquote'
                    }"
                    :data-placeholder="placeholder"
                    x-init="$el.innerHTML = block.content"
                    @input="updateBlock(index, $event.target)"
                    @keydown.enter.prevent="addBlock(index)"
                    @keydown.backspace="removeBlock(index, $event)"
                    @keydown.escape="showDropdown = false"
                    @mouseup="handleSelection"
                    @keyup="handleSelection"
                    @paste.prevent="handlePaste(index, $event)"
                ></div>

                {{-- Slash menu --}}
                <div
                    x-show="showDropdown && dropdownIndex === index"
                    x-transition
                    class="absolute z-50 top-full left-0 mt-1 w-64 bg-base-100 border border-base-300 rounded-xl shadow-xl py-2 max-h-60 overflow-y-auto kompass-fade-in"
                    @click.outside="showDropdown = false"
                    style="display: none;"
                >
                    <span class="block px-3 py-1 text-[10px] font-bold text-base-content/60 uppercase tracking-wider">{{ __('Format') }}</span>
                    <template x-for="type in blockTypes" :key="type.id">
                        <button
                            type="button"
                            @mousedown.prevent="selectBlockType(index, type.id)"
                            class="w-full flex items-center gap-3 px-3 py-2 text-left hover:bg-base-200 transition"
                            :class="{ 'bg-primary/10 text-primary': block.type === type.id }"
                        >
                            <span class="w-8 h-8 flex items-center justify-center rounded border border-base-300 bg-base-200" :class="block.type === type.id ? 'text-primary border-primary' : 'text-base-content'" x-html="type.icon"></span>
                            <div>
                                <span class="block text-xs font-semibold" :class="block.type === type.id ? 'text-primary' : 'text-base-content'" x-text="type.label"></span>
                                <span class="block text-[10px]" :class="block.type === type.id ? 'text-primary/60' : 'text-base-content/60'" x-text="type.desc"></span>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </template>
    </div>

    {{-- Bubble menu --}}
    <div
        x-show="showBubbleMenu && !readOnly"
        x-ref="bubbleMenu"
        class="fixed z-[60] flex items-center bg-neutral text-neutral-content rounded-lg shadow-2xl p-1 border border-neutral kompass-fade-in"
        :style="`top: ${bubblePos.top}px; left: ${bubblePos.left}px; transform: translate(-50%, -120%);`"
        @click.outside="if (!showLinkPopover) showBubbleMenu = false"
        style="display: none;"
    >
        <button type="button" @click="format('bold')" class="p-2 hover:bg-neutral/70 rounded transition" title="{{ __('Bold') }}">
            <x-tabler-bold class="w-4 h-4 stroke-2" />
        </button>
        <button type="button" @click="format('italic')" class="p-2 hover:bg-neutral/70 rounded transition" title="{{ __('Italic') }}">
            <x-tabler-italic class="w-4 h-4 stroke-2" />
        </button>
        <button type="button" @click="format('underline')" class="p-2 hover:bg-neutral/70 rounded transition" title="{{ __('Underline') }}">
            <x-tabler-underline class="w-4 h-4 stroke-2" />
        </button>
        <button type="button" @click="format('strikeThrough')" class="p-2 hover:bg-neutral/70 rounded transition" title="{{ __('Strikethrough') }}">
            <x-tabler-strikethrough class="w-4 h-4 stroke-2" />
        </button>
        <div class="w-px h-4 bg-neutral-content/20 mx-1"></div>

        <button type="button" @click="openLinkPopover" class="p-2 hover:bg-neutral/70 rounded transition" title="{{ __('Link') }}">
            <x-tabler-link class="w-4 h-4 stroke-2" />
        </button>

        {{-- Link popover --}}
        <div
            x-show="showLinkPopover"
            x-transition
            class="absolute top-full left-0 mt-2 w-72 bg-base-100 rounded-xl shadow-2xl border border-base-300 p-4 text-base-content kompass-fade-in"
            @click.outside="showLinkPopover = false"
            style="display: none;"
        >
            <div class="space-y-3">
                <div>
                    <label class="text-[10px] font-bold text-base-content/60 uppercase">{{ __('Link (URL)') }}</label>
                    <input x-model="linkData.url" type="text" placeholder="https://..." class="w-full mt-1 px-3 py-1.5 bg-base-200 border border-base-300 rounded-md text-sm focus:ring-2 focus:ring-base-300 focus:outline-none">
                </div>
                <div class="flex items-center gap-2">
                    <input x-model="linkData.newTab" type="checkbox" id="kompass-newtab" class="rounded border-base-300 text-base-content focus:ring-primary">
                    <label for="kompass-newtab" class="text-xs text-base-content/70">{{ __('Open in a new tab?') }}</label>
                </div>
                <button
                    type="button"
                    @click="applyLink"
                    class="w-full py-2 bg-neutral text-neutral-content rounded-lg text-sm font-semibold hover:bg-neutral/80 transition"
                >
                    {{ __('Apply Now') }}
                </button>
            </div>
        </div>
    </div>
</div>
</div>

@assets
<style>
    .editor-container [contenteditable=true]:empty:before {
        content: attr(data-placeholder);
        color: color-mix(in oklab, var(--color-base-content) 40%, transparent);
        pointer-events: none;
        display: block;
    }
    .editor-container ::selection {
        background-color: var(--color-base-300);
        color: var(--color-base-content);
    }
    .editor-container [contenteditable] {
        outline: none !important;
        box-shadow: none !important;
    }
    .kompass-fade-in {
        animation: kompassFadeIn 0.15s ease-out;
    }
    @keyframes kompassFadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .editor-container {
        counter-reset: oli-counter;
    }
    .editor-container .not-oli {
        counter-reset: oli-counter;
    }
    .editor-container .oli-item {
        counter-increment: oli-counter;
    }
    .editor-container .li-item::marker {
        font-size: 0.75rem;
        color: color-mix(in oklab, var(--color-base-content) 40%, transparent);
    }
    .editor-container .oli-item::marker {
        content: counter(oli-counter) ". ";
        font-weight: 600;
        font-size: 0.875rem;
        color: color-mix(in oklab, var(--color-base-content) 60%, transparent);
    }
</style>
@endassets

@script
<script>
    window.kompassEditorFactory = function (init) {
        const initData = init || {};
        return {
            // i18n-injected labels (PHP → __())
            blockTypes: initData.blockTypes || [],
            prelinePlaceholder: initData.prelinePlaceholder || 'PRELINE TEXT',

            // Menu state
            showDropdown: false,
            dropdownIndex: null,
            showAddMenu: false,
            addMenuIndex: null,
            showTuneMenu: false,
            tuneIndex: null,

            // Bubble menu
            showBubbleMenu: false,
            bubblePos: { top: 0, left: 0 },
            savedRange: null,

            // Link popover
            showLinkPopover: false,
            linkData: { text: '', url: '', newTab: true },

            // Drag & drop
            dragIndex: null,
            dropTargetIndex: null,

            newBlockId() {
                return 'block-' + Math.random().toString(36).substr(2, 9);
            },

            togglePlusMenu(index) {
                this.addMenuIndex = index;
                this.showAddMenu = !this.showAddMenu;
            },

            addNewBlockWithType(index, type) {
                const newId = this.newBlockId();
                let content = '';
                if (type === 'preline') {
                    content = this.prelinePlaceholder;
                }
                this.blocks.splice(index + 1, 0, { id: newId, type: type, content: content });
                this.showAddMenu = false;
                this.$nextTick(() => {
                    const el = document.getElementById(newId);
                    if (el) this.focusAndSetCaret(el);
                });
            },

            toggleTuneMenu(index) {
                this.tuneIndex = index;
                this.showTuneMenu = !this.showTuneMenu;
            },

            moveBlock(index, direction) {
                const newIndex = index + direction;
                if (newIndex < 0 || newIndex >= this.blocks.length) return;
                const block = this.blocks.splice(index, 1)[0];
                this.blocks.splice(newIndex, 0, block);
                this.showTuneMenu = false;
            },

            deleteBlock(index) {
                if (this.blocks.length > 1) {
                    this.blocks.splice(index, 1);
                } else {
                    this.blocks[0].content = '';
                    this.blocks[0].type = 'p';
                    const el = document.getElementById(this.blocks[0].id);
                    if (el) el.innerHTML = '';
                }
                this.showTuneMenu = false;
            },

            dragStart(index, event) {
                this.dragIndex = index;
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', index);
            },

            dragEnd() {
                this.dragIndex = null;
                this.dropTargetIndex = null;
            },

            dragDrop(index) {
                if (this.dragIndex === null || this.dragIndex === index) {
                    this.dragEnd();
                    return;
                }
                const blockToMove = this.blocks[this.dragIndex];
                this.blocks.splice(this.dragIndex, 1);
                this.blocks.splice(index, 0, blockToMove);
                this.dragEnd();
            },

            updateBlock(index, el) {
                this.blocks[index].content = el.innerHTML;
                const text = el.innerText.replace(/[\n\r\u200B]/g, '').trim();
                if (text === '/') {
                    this.showDropdown = true;
                    this.dropdownIndex = index;
                } else if (this.dropdownIndex === index && !this.showDropdown) {
                    this.showDropdown = false;
                }
            },

            selectBlockType(index, type) {
                this.blocks[index].type = type;
                const el = document.getElementById(this.blocks[index].id);
                if (el) {
                    let currentHtml = el.innerHTML.replace(/^\//, '').trim();
                    if (type === 'preline') {
                        const t = el.innerText.replace(/^\//, '').trim().toUpperCase();
                        currentHtml = t || this.prelinePlaceholder;
                    }
                    el.innerHTML = currentHtml;
                    this.blocks[index].content = currentHtml;
                }
                this.showDropdown = false;
                this.$nextTick(() => {
                    const el2 = document.getElementById(this.blocks[index].id);
                    if (el2) this.focusAndSetCaret(el2);
                });
            },

            addBlock(index) {
                this.showDropdown = false;
                this.showBubbleMenu = false;

                const currentBlock = this.blocks[index];
                const el = document.getElementById(currentBlock.id);
                const isEmpty = el ? el.innerText.replace(/[\n\r\u200B]/g, '').trim() === '' : true;

                // Smart-list: empty list item + Enter → convert to paragraph.
                if (isEmpty && (currentBlock.type === 'li' || currentBlock.type === 'oli')) {
                    currentBlock.type = 'p';
                    return;
                }

                const newId = this.newBlockId();
                const newType = (currentBlock.type === 'li' || currentBlock.type === 'oli')
                    ? currentBlock.type
                    : 'p';

                this.blocks.splice(index + 1, 0, { id: newId, type: newType, content: '' });

                this.$nextTick(() => {
                    const el2 = document.getElementById(newId);
                    if (el2) el2.focus();
                });
            },

            removeBlock(index, event) {
                this.showDropdown = false;
                const el = event.target;
                if (el.innerText.trim() === '' && this.blocks.length > 1) {
                    event.preventDefault();
                    const prevBlock = this.blocks[index - 1];
                    this.blocks.splice(index, 1);
                    this.$nextTick(() => {
                        if (prevBlock) {
                            const elPrev = document.getElementById(prevBlock.id);
                            if (elPrev) this.focusAndSetCaret(elPrev);
                        }
                    });
                }
            },

            handlePaste(index, event) {
                const data = event.clipboardData.getData('text/plain');
                if (!data) return;

                const lines = data.split(/\r?\n/).filter(line => line.trim() !== '');

                if (lines.length <= 1) {
                    document.execCommand('insertText', false, data);
                    this.updateBlock(index, event.target);
                    return;
                }

                const newBlocks = [];
                lines.forEach(line => {
                    let type = 'p';
                    let content = line.trim();

                    if (content.startsWith('npm ') || content.startsWith('curl ') || content.startsWith('git ') || content.startsWith('pi ') || content.startsWith('sh ') || content.startsWith('./')) {
                        // Code-ish line — no `code` block in blockTypes, keep as `p`
                        // so paste behaviour matches the new editor's surface area.
                        type = 'p';
                    } else if (content.startsWith('# ')) {
                        type = 'h1';
                        content = content.substring(2);
                    } else if (content.startsWith('## ')) {
                        type = 'h2';
                        content = content.substring(3);
                    } else if (content.startsWith('### ')) {
                        type = 'h3';
                        content = content.substring(4);
                    } else if (content.startsWith('- ') || content.startsWith('* ')) {
                        type = 'li';
                        content = content.substring(2);
                    } else if (/^\d+\. /.test(content)) {
                        type = 'oli';
                        content = content.replace(/^\d+\. /, '');
                    } else if (content.startsWith('> ')) {
                        type = 'blockquote';
                        content = content.substring(2);
                    }

                    newBlocks.push({
                        id: this.newBlockId(),
                        type: type,
                        content: content,
                    });
                });

                const currentEl = document.getElementById(this.blocks[index].id);
                if (currentEl && currentEl.innerText.trim() === '') {
                    this.blocks.splice(index, 1, ...newBlocks);
                } else {
                    this.blocks.splice(index + 1, 0, ...newBlocks);
                }
            },

            handleSelection() {
                const selection = window.getSelection();
                if (selection.rangeCount > 0 && !selection.isCollapsed) {
                    const range = selection.getRangeAt(0);
                    const rect = range.getBoundingClientRect();
                    this.bubblePos = {
                        top: rect.top + window.scrollY,
                        left: rect.left + window.scrollX + (rect.width / 2),
                    };
                    this.showBubbleMenu = true;
                    this.savedRange = range.cloneRange();
                } else if (!this.showLinkPopover) {
                    this.showBubbleMenu = false;
                }
            },

            // execCommand is deprecated but remains the only practical way to
            // toggle nested bold/italic/underline across arbitrary ranges
            // without re-implementing a full DOM transformation utility.
            format(command) {
                document.execCommand(command, false, null);
                this.syncCurrentBlock();
            },

            changeBlockType(type) {
                const selection = window.getSelection();
                if (!selection.rangeCount) return;
                let node = selection.anchorNode;
                while (node && node.parentElement && !node.id) {
                    node = node.parentElement;
                }
                if (node && node.id) {
                    const index = this.blocks.findIndex(b => b.id === node.id);
                    if (index !== -1) {
                        this.blocks[index].type = type;
                        this.showBubbleMenu = false;
                    }
                }
            },

            openLinkPopover() {
                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    const range = selection.getRangeAt(0);
                    this.savedRange = range.cloneRange();

                    let parent = range.commonAncestorContainer;
                    if (parent.nodeType === 3) parent = parent.parentNode;
                    const existingLink = parent.closest ? parent.closest('a') : null;

                    if (existingLink) {
                        this.linkData.text = existingLink.innerText;
                        this.linkData.url = existingLink.getAttribute('href');
                        this.linkData.newTab = existingLink.getAttribute('target') === '_blank';

                        const newRange = document.createRange();
                        newRange.selectNode(existingLink);
                        selection.removeAllRanges();
                        selection.addRange(newRange);
                        this.savedRange = newRange.cloneRange();
                    } else {
                        this.linkData.text = selection.toString();
                        this.linkData.url = '';
                    }
                    this.showLinkPopover = true;
                }
            },

            applyLink() {
                if (!this.linkData.url) return;

                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(this.savedRange);

                const a = document.createElement('a');
                a.href = this.linkData.url;
                a.innerText = this.linkData.text || this.linkData.url;
                a.className = 'text-primary underline cursor-pointer';
                if (this.linkData.newTab) a.target = '_blank';

                this.savedRange.deleteContents();
                this.savedRange.insertNode(a);

                this.syncCurrentBlock();
                this.showLinkPopover = false;
                this.showBubbleMenu = false;

                selection.removeAllRanges();
            },

            syncCurrentBlock() {
                this.blocks.forEach((block, idx) => {
                    const el = document.getElementById(block.id);
                    if (el && (document.activeElement === el || el.contains(document.activeElement))) {
                        this.updateBlock(idx, el);
                    }
                });
            },

            focusAndSetCaret(el) {
                el.focus();
                const range = document.createRange();
                const sel = window.getSelection();
                range.selectNodeContents(el);
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);
            },
        };
    };
</script>
@endscript
