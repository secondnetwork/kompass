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
    x-data="{ expanded: false }">
    
    <div-nav-action class="flex items-center justify-between border-b border-gray-200 px-4" 
        @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup') :class="'bg-slate-200 border-slate-600'" @endif>
        <span class="flex items-center py-2 w-full ">
            @if ($itemblocks->subgroup)
                <span wire:sort:handle>
                    <x-tabler-grip-vertical class="cursor-move stroke-current h-6 w-6 mr-1 text-gray-900" />
                </span>
            @else
                <span wire:sort:handle>
                    <x-tabler-grip-vertical class="cursor-move stroke-current h-6 w-6 mr-1 text-gray-900" />
                </span>
            @endif

            <span @click="expanded = ! expanded"
                class="text-xs inline-flex items-center gap-1.5 py-1 px-1 capitalize rounded font-semibold  text-gray-400 cursor-pointer">
                @switch($itemblocks->type)
                    @case('group')
                        <x-tabler-template class="cursor-pointer stroke-current h-6 w-6 text-violet-600" />
                    @break

                    @case('accordiongroup')
                        <x-tabler-layout-list class="cursor-pointer stroke-current h-6 w-6 text-violet-600" />
                    @break

                    @default
                        @if ($itemblocks->iconclass)
                            @svg('tabler-' . $itemblocks->iconclass, 'w-5')
                        @else
                            @svg('tabler-section', 'w-5')
                        @endif
                @endswitch
            </span>

            <span class="inline-block border-r border-gray-400 w-px h-5 ml-1 mr-2"></span>

            <livewire:editable-name :itemblocks="$itemblocks" :key="'editable-block-name-'.$itemblocks->id" />

        </span>

        <div class="flex items-center gap-1">
            @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup')

                @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup')
                <span @click="expanded = ! expanded">
                    <x-tabler-adjustments class="cursor-pointer stroke-current h-6 w-6 text-stone-500" />
                </span>
                @endif

                <span wire:click="selectitem('addBlock', {{ $itemblocks->id }},'page',{{ $itemblocks->id }})">
                    <x-tabler-layout-grid-add class="cursor-pointer stroke-current h-6 w-6 text-blue-600" />
                </span>
                @if ($itemblocks->status == 'published')
                    <span wire:click="updatestatus({{ $itemblocks->id }}, 'draft')">
                        <x-tabler-eye class="cursor-pointer stroke-current h-6 w-6 text-gray-400" />
                    </span>
                @else
                    <span wire:click="updatestatus({{ $itemblocks->id }}, 'published')">
                        <x-tabler-eye-off class="cursor-pointer stroke-current h-6 w-6 text-red-500" />
                    </span>
                @endif
                <span wire:click="selectitem('deleteblock', {{ $itemblocks->id }})" class="flex justify-center">
                    <x-tabler-trash class="cursor-pointer stroke-current h-6 w-6 text-red-500" />
                </span>
            @else        
           
            <span wire:click="edit({{ $itemblocks->id }})" class="flex justify-center">
                <x-tabler-edit class="cursor-pointer stroke-current text-blue-500" />
            </span>

                @if ($itemblocks->status == 'published')
                    <button wire:click="updatestatus({{ $itemblocks->id }}, 'draft')">
                        <x-tabler-eye class="cursor-pointer stroke-current h-6 w-6 text-gray-400" />
                    </button>
                @else
                    <span wire:click="updatestatus({{ $itemblocks->id }}, 'published')">
                        <x-tabler-eye-off class="cursor-pointer stroke-current h-6 w-6 text-red-500" />
                    </span>
                @endif

                <span wire:click="clone({{ $itemblocks->id }})" class="flex justify-center">
                    <x-tabler-copy class="cursor-pointer  h-6 w-6  stroke-violet-500" />
                </span>

                <span wire:click="selectitem('deleteblock',{{ $itemblocks->id }})" class="flex justify-center">
                    <x-tabler-trash class="cursor-pointer stroke-current h-6 w-6 text-red-500" />
                </span>
                {{-- <div class="flex items-center gap-2">
                    <span :class="!expanded ? '' : 'rotate-180'" @click="expanded = ! expanded"
                        class="transform transition-transform duration-500">
                        <x-tabler-chevron-down class="cursor-pointer stroke-current h-6 w-6 text-gray-900 " />
                    </span>
                </div> --}}
            @endif
        </div>

    </div-nav-action>
    
    @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup')
    <nav x-show="expanded" x-collapse
    class="px-6 py-2 bg-gray-200 shadow-inner shadow-black/20 grid gap-4 @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup') border-b-4 border-purple-700 @endif">

    <x-kompass::nav-itemgroup :itemblocks="$itemblocks" />

    </nav>
    @endif

    <div wire:sort="handleSort" wire:sort:item-group="{{ $itemblocks->id }}" class="bg-purple-700 grid grid-cols-{{ $itemblocks->layoutgrid }}" >
        <x-kompass::blocksgroupsub :childrensub="$itemblocks->children->sortBy('order')" :fields="$itemblocks->datafield" :page="$page" />
    </div>
</div>
