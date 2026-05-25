@props([
    'itemblocks' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
])

<div class="{{ $class }} @if ($itemblocks->subgroup) group-block border-purple-600 border-2 @endif" :class="'{{ $itemblocks->status }}' == 'published' ? 'opacity-100':'border-gray-200 shadow-inner'"

    @if ($itemblocks->subgroup) wire:sort:item="{{ $itemblocks->id }}" wire:key="group-{{ $itemblocks->id }}"
    @else
 wire:key="group-{{ $itemblocks->id }}" @endif
    x-data="{ expanded: false, dropdownOpen: false }">

    <div-nav-action class="@container flex items-center justify-between border-b border-gray-200 px-4"
        @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup') :class="'bg-slate-200 border-slate-600'" @endif>

        {{-- Left: drag + icon + name --}}
        <span class="flex items-center py-2 min-w-0 flex-1 overflow-hidden">
            <span wire:sort:handle class="shrink-0">
                <x-tabler-grip-vertical class="cursor-move stroke-current size-5 md:size-6 mr-1 text-gray-900" />
            </span>

            <span class="text-xs inline-flex items-center gap-1.5 py-1 px-1 capitalize rounded font-semibold text-gray-400 cursor-pointer shrink-0">
                @switch($itemblocks->type)
                    @case('group')
                        <x-tabler-template class="cursor-pointer stroke-current size-5 md:size-6 text-violet-600" />
                    @break
                    @case('accordiongroup')
                        <x-tabler-layout-list class="cursor-pointer stroke-current size-5 md:size-6 text-violet-600" />
                    @break
                    @default
                        @if ($itemblocks->iconclass)
                            @svg(str_starts_with($itemblocks->iconclass, 'tabler-') ? $itemblocks->iconclass : 'tabler-' . $itemblocks->iconclass, 'w-5')
                        @else
                            @svg('tabler-section', 'w-5')
                        @endif
                @endswitch
            </span>

            <span class="inline-block border-r border-gray-400 w-px h-5 ml-1 mr-2 shrink-0"></span>

            <span class="truncate min-w-0">
                <livewire:editable-name :itemblocks="$itemblocks" :key="'editable-block-name-'.$itemblocks->id" :size="'sm'" />
            </span>
        </span>

        {{-- Right: actions --}}
        <div class="flex items-center gap-1 shrink-0 ml-2">

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
                        class="absolute right-0 top-7 z-50 w-44 bg-white border border-neutral-200 rounded-md shadow-md py-1"
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
                        class="absolute right-0 top-7 z-50 w-44 bg-white border border-neutral-200 rounded-md shadow-md py-1"
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

    <div wire:sort="handleSort" wire:sort:group="blocks" wire:sort:group-id="{{ $itemblocks->id }}" class="bg-purple-700 grid grid-cols-{{ $itemblocks->layoutgrid }}" >
        <x-kompass::blocksgroupsub :childrensub="$itemblocks->children->sortBy('order')" :fields="$itemblocks->datafield" :page="$page" />
    </div>
</div>
