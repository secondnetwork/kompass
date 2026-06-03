@props([
    'itemblocks' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
])

@php
    $isContainer = in_array($itemblocks->type, ['group', 'accordiongroup']);
    $hasChildren = $itemblocks->children->isNotEmpty();
    $showNest = $isContainer || $hasChildren;

    // Divi-style colour coding by hierarchy: layout = indigo, accordion = emerald, module = slate.
    $style = match ($itemblocks->type) {
        'group' => ['rail' => 'border-l-indigo-500', 'badge' => 'bg-indigo-500', 'bar' => 'bg-indigo-500/10', 'accent' => 'text-indigo-600'],
        'accordiongroup' => ['rail' => 'border-l-emerald-500', 'badge' => 'bg-emerald-500', 'bar' => 'bg-emerald-500/10', 'accent' => 'text-emerald-600'],
        default => ['rail' => 'border-l-slate-400', 'badge' => 'bg-slate-500', 'bar' => 'bg-base-200', 'accent' => 'text-slate-500'],
    };
@endphp

<div class="{{ $class }} border-l-4 {{ $style['rail'] }} @if ($itemblocks->subgroup) group-block @endif" :class="'{{ $itemblocks->status }}' == 'published' ? 'opacity-100':'border-base-300 shadow-inner'"

    @if ($itemblocks->subgroup) wire:sort:item="{{ $itemblocks->id }}" wire:key="group-{{ $itemblocks->id }}"
    @else wire:key="group-{{ $itemblocks->id }}" @endif
    x-data="{ expanded: false, dropdownOpen: false }"
    @expand-all-blocks.window="expanded = true"
    @collapse-all-blocks.window="expanded = false">

    <div-nav-action class="@container flex items-center justify-between border-b border-base-300 px-4 {{ $style['bar'] }}">

        {{-- Left: drag + icon + name --}}
        <span class="flex items-center py-2 min-w-0 flex-1 overflow-hidden">
            <span wire:sort:handle class="shrink-0">
                <x-tabler-grip-vertical class="cursor-move stroke-current size-5 md:size-6 mr-1" />
            </span>

            @if ($hasChildren)
                <button type="button" @click="expanded = !expanded"
                    class="shrink-0 mr-1 {{ $style['accent'] }} transition-transform duration-200"
                    :class="expanded ? 'rotate-90' : ''" title="{{ __('Expand / collapse') }}">
                    <x-tabler-chevron-right class="size-5" />
                </button>
            @endif

            <span class="shrink-0 mr-2 flex items-center justify-center size-7 rounded-md {{ $style['accent'] }}">
                @switch($itemblocks->type)
                    @case('group')
                        <x-tabler-template class="stroke-current size-6" />
                    @break
                    @case('accordiongroup')
                        <x-tabler-layout-list class="stroke-current size-6" />
                    @break
                    @default
                        @if ($itemblocks->iconclass)
                            @svg(str_starts_with($itemblocks->iconclass, 'tabler-') ? $itemblocks->iconclass : 'tabler-' . $itemblocks->iconclass, 'size-5')
                        @else
                            @svg('tabler-section', 'size-6')
                        @endif
                @endswitch
            </span>

            <span class="truncate min-w-0">
                <livewire:editable-name :itemblocks="$itemblocks" :key="'editable-block-name-'.$itemblocks->id" :size="'sm'" />
            </span>
        </span>

        {{-- Right: actions --}}
        <div class="flex items-center gap-1 shrink-0 ">

            @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup')

                {{-- Inline icons: visible when container >= 380px --}}
                <div class="hidden @[280px]:flex items-center gap-1">
                    <x-kompass::nav-itemgroup :itemblocks="$itemblocks" />
                    <span wire:click="selectitem('addBlock', {{ $itemblocks->id }},'page',{{ $itemblocks->id }})">
                        <x-tabler-layout-grid-add class="cursor-pointer stroke-current size-5 md:size-6 text-blue-600" />
                    </span>
                    @if ($itemblocks->status == 'published')
                        <span wire:click="updatestatus({{ $itemblocks->id }}, 'draft')">
                            <x-tabler-eye class="cursor-pointer stroke-current size-5 md:size-6 text-gray-400" />
                        </span>
                    @else
                        <span wire:click="updatestatus({{ $itemblocks->id }}, 'published')">
                            <x-tabler-eye-off class="cursor-pointer stroke-current size-5 md:size-6 text-red-500" />
                        </span>
                    @endif
                    <span wire:click="selectitem('deleteblock', {{ $itemblocks->id }})" class="flex justify-center">
                        <x-tabler-trash class="cursor-pointer stroke-current size-5 md:size-6 text-red-500" />
                    </span>
                </div>

                {{-- ··· Dropdown: visible when container < 380px --}}
                <div class="flex @[280px]:hidden items-center gap-1 relative" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false">
                    {{-- Settings always visible --}}
                    <x-kompass::nav-itemgroup :itemblocks="$itemblocks" />

                    <button @click="dropdownOpen = !dropdownOpen"
                        class="flex items-center justify-center size-6 rounded hover:bg-neutral-100 transition-colors"
                        :class="{ 'bg-neutral-100': dropdownOpen }">
                        <x-tabler-dots-vertical class="size-4 text-gray-500" />
                    </button>
                    <div x-show="dropdownOpen"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-7 z-50 w-44 bg-base-100 border border-neutral-200 rounded-md shadow-md py-1"
                        x-cloak>
                        <button wire:click="selectitem('addBlock', {{ $itemblocks->id }},'page',{{ $itemblocks->id }})" @click="dropdownOpen = false"
                            class="flex items-center gap-2 w-full px-3 py-1.5 text-xs text-gray-700 hover:bg-neutral-50">
                            <x-tabler-layout-grid-add class="size-4 text-blue-600 shrink-0" />
                            {{ __('Add Block') }}
                        </button>
                        <div class="h-px my-1 bg-neutral-100"></div>
                        @if ($itemblocks->status == 'published')
                            <button wire:click="updatestatus({{ $itemblocks->id }}, 'draft')" @click="dropdownOpen = false"
                                class="flex items-center gap-2 w-full px-3 py-1.5 text-xs text-gray-700 hover:bg-neutral-50">
                                <x-tabler-eye class="size-4 text-gray-400 shrink-0" />
                                {{ __('Set Draft') }}
                            </button>
                        @else
                            <button wire:click="updatestatus({{ $itemblocks->id }}, 'published')" @click="dropdownOpen = false"
                                class="flex items-center gap-2 w-full px-3 py-1.5 text-xs text-gray-700 hover:bg-neutral-50">
                                <x-tabler-eye-off class="size-4 text-red-400 shrink-0" />
                                {{ __('Publish') }}
                            </button>
                        @endif
                        <div class="h-px my-1 bg-neutral-100"></div>
                        <button wire:click="selectitem('deleteblock', {{ $itemblocks->id }})" @click="dropdownOpen = false"
                            class="flex items-center gap-2 w-full px-3 py-1.5 text-xs text-red-600 hover:bg-red-50">
                            <x-tabler-trash class="size-4 shrink-0" />
                            {{ __('Delete') }}
                        </button>
                    </div>
                </div>

            @else

                {{-- Inline icons: visible when container >= 380px --}}
                <div class="hidden @[280px]:flex items-center gap-1">
                    <span wire:click="edit({{ $itemblocks->id }})" class="flex justify-center">
                        <x-tabler-edit class="cursor-pointer stroke-current text-blue-500 size-5 md:size-6" />
                    </span>
                    <x-kompass::nav-item :itemblocks="$itemblocks" />
                    @if ($itemblocks->status == 'published')
                        <button wire:click="updatestatus({{ $itemblocks->id }}, 'draft')">
                            <x-tabler-eye class="cursor-pointer stroke-current size-5 md:size-6 text-gray-400" />
                        </button>
                    @else
                        <span wire:click="updatestatus({{ $itemblocks->id }}, 'published')">
                            <x-tabler-eye-off class="cursor-pointer stroke-current size-5 md:size-6 text-red-500" />
                        </span>
                    @endif
                    <span wire:click="clone({{ $itemblocks->id }})" class="flex justify-center">
                        <x-tabler-copy class="cursor-pointer size-5 md:size-6 stroke-violet-500" />
                    </span>
                    <span wire:click="selectitem('deleteblock',{{ $itemblocks->id }})" class="flex justify-center">
                        <x-tabler-trash class="cursor-pointer stroke-current size-5 md:size-6 text-red-500" />
                    </span>
                </div>

                {{-- ··· Dropdown: visible when container < 380px --}}
                <div class="flex @[280px]:hidden items-center gap-1 relative" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false">
                    {{-- Settings always visible --}}
                    <x-kompass::nav-item :itemblocks="$itemblocks" />

                    <button @click="dropdownOpen = !dropdownOpen"
                        class="flex items-center justify-center size-6 rounded hover:bg-neutral-100 transition-colors"
                        :class="{ 'bg-neutral-100': dropdownOpen }">
                        <x-tabler-dots-vertical class="size-4 text-gray-500" />
                    </button>
                    <div x-show="dropdownOpen"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-7 z-50 w-44 bg-base-100 border border-neutral-200 rounded-md shadow-md py-1"
                        x-cloak>
                        <button wire:click="edit({{ $itemblocks->id }})" @click="dropdownOpen = false"
                            class="flex items-center gap-2 w-full px-3 py-1.5 text-xs text-gray-700 hover:bg-neutral-50">
                            <x-tabler-edit class="size-4 text-blue-500 shrink-0" />
                            {{ __('Edit') }}
                        </button>
                        <div class="h-px my-1 bg-neutral-100"></div>
                        @if ($itemblocks->status == 'published')
                            <button wire:click="updatestatus({{ $itemblocks->id }}, 'draft')" @click="dropdownOpen = false"
                                class="flex items-center gap-2 w-full px-3 py-1.5 text-xs text-gray-700 hover:bg-neutral-50">
                                <x-tabler-eye class="size-4 text-gray-400 shrink-0" />
                                {{ __('Set Draft') }}
                            </button>
                        @else
                            <button wire:click="updatestatus({{ $itemblocks->id }}, 'published')" @click="dropdownOpen = false"
                                class="flex items-center gap-2 w-full px-3 py-1.5 text-xs text-gray-700 hover:bg-neutral-50">
                                <x-tabler-eye-off class="size-4 text-red-400 shrink-0" />
                                {{ __('Publish') }}
                            </button>
                        @endif
                        <button wire:click="clone({{ $itemblocks->id }})" @click="dropdownOpen = false"
                            class="flex items-center gap-2 w-full px-3 py-1.5 text-xs text-gray-700 hover:bg-neutral-50">
                            <x-tabler-copy class="size-4 text-violet-500 shrink-0" />
                            {{ __('Clone') }}
                        </button>
                        <div class="h-px my-1 bg-neutral-100"></div>
                        <button wire:click="selectitem('deleteblock', {{ $itemblocks->id }})" @click="dropdownOpen = false"
                            class="flex items-center gap-2 w-full px-3 py-1.5 text-xs text-red-600 hover:bg-red-50">
                            <x-tabler-trash class="size-4 shrink-0" />
                            {{ __('Delete') }}
                        </button>
                    </div>
                </div>

            @endif
        </div>

    </div-nav-action>

    @if ($isContainer && $hasChildren)
        {{-- Layout/Accordion container with children: the only droppable target --}}
        <div x-show="expanded" x-collapse class="border-l-2 {{ $style['rail'] }} bg-base-300/40 rounded-b-md p-1.5">
            <div wire:sort="handleSort" wire:sort:group="blocks" wire:sort:group-id="{{ $itemblocks->id }}"
                class="grid grid-cols-{{ $itemblocks->layoutgrid }} gap-2">
                <x-kompass::blocksgroupsub :childrensub="$itemblocks->children->sortBy('order')" :fields="$itemblocks->datafield" :page="$page" />
            </div>
        </div>
    @elseif ($isContainer)
        {{-- Empty container: always open (no collapse toggle) so it stays a drop target; expands only while dragging --}}
        <div class="border-l-2 {{ $style['rail'] }} rounded-b-md"
            :class="dragging ? 'p-1.5' : ''">
            <div wire:sort="handleSort" wire:sort:group="blocks" wire:sort:group-id="{{ $itemblocks->id }}"
                class="relative grid grid-cols-{{ $itemblocks->layoutgrid }} transition-[min-height] duration-200">
            </div>
        </div>
    @elseif ($hasChildren)

        <div x-show="expanded" x-collapse class="bg-base-300/40 rounded-b-md p-1.5">
            <div class="grid grid-cols-{{ $itemblocks->layoutgrid }} gap-2">
                <x-kompass::blocksgroupsub :childrensub="$itemblocks->children->sortBy('order')" :fields="$itemblocks->datafield" :page="$page" />
            </div>
        </div>
    @endif

</div>
