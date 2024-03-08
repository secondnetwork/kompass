@props([
    'itemblocks' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
])

<div class="{{ $class }} @if ($itemblocks->subgroup) group-block border-purple-600 border-2 @endif" :class="'{{ $itemblocks->status }}' == 'published' ? 'opacity-100':'border-gray-200 shadow-inner'"
    wire:sortable.item="{{ $itemblocks->id }}"
    @if ($itemblocks->subgroup) wire:sortable-group.item="{{ $itemblocks->id }}" wire:key="group-{{ $itemblocks->id }}"
    @else
    wire:sortable.item="{{ $itemblocks->id }}" wire:key="group-{{ $itemblocks->id }}" @endif
    x-data="{ expanded: false }">
    
    <div-nav-action class="flex items-center justify-between border-b border-gray-200 px-4" 
        @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup') :class="'bg-slate-200 border-slate-600'" @endif>
        <span class="flex items-center py-2 w-full ">
            @if ($itemblocks->subgroup)
                <span wire:sortable-group.handle>
                    <x-tabler-grip-vertical class="cursor-move stroke-current h-6 w-6 mr-1 text-gray-900" />
                </span>
            @else
                <span wire:sortable.handle>
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
            <div x-data="click_to_edit()" class="w-11/12 flex items-center">
                <a @click.prevent @click="toggleEditingState" x-show="!isEditing"
                    class="flex items-center select-none cursor-text" x-on:keydown.escape="isEditing = false">
   
                    <span class="text-sm font-semibold">{{ $itemblocks->name }}</span>
                    {{-- <span x-show="iconEditing"><x-tabler-edit class="cursor-pointer stroke-current h-5 w-5 text-gray-400 hover:text-blue-500" /></span> --}}
                </a>

                <div x-show=isEditing class="flex items-center" x-data="{ id: '{{ $itemblocks->id }}', name: '{{ $itemblocks->name }}' }">

                    <input type="text" class="border border-gray-400 px-1 py-1 text-sm font-semibold" x-model="name"
                        wire:model.lazy="newName" x-ref="input" x-on:keydown.enter="isEditing = false"
                        x-on:keydown.escape="isEditing = false"
                        x-on:click.away="isEditing = false" wire:keydown.enter="savename({{ $itemblocks->id }})">
                    <span wire:click="savename({{ $itemblocks->id }})" x-on:click="isEditing = false">
                        <x-tabler-square-check class="cursor-pointer stroke-current h-6 w-6 text-green-600" />
                    </span>
                    <span x-on:click="isEditing = false">
                        <x-tabler-square-x class="cursor-pointer stroke-current h-6 w-6 text-red-600" />
                    </span>

                </div>

            </div>

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
                    <span wire:click="status({{ $itemblocks->id }}, 'draft')">
                        <x-tabler-eye class="cursor-pointer stroke-current h-6 w-6 text-gray-400" />
                    </span>
                @else
                    <span wire:click="status({{ $itemblocks->id }}, 'published')">
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
                    <span wire:click="status({{ $itemblocks->id }}, 'draft')">
                        <x-tabler-eye class="cursor-pointer stroke-current h-6 w-6 text-gray-400" />
                    </span>
                @else
                    <span wire:click="status({{ $itemblocks->id }}, 'published')">
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


    <div wire:sortable-group.item-group="{{ $itemblocks->id }}" class="bg-purple-700 grid grid-cols-{{ $itemblocks->grid }}" >
        <x-kompass::blocksgroupsub :childrensub="$itemblocks->children->sortBy('order')" :fields="$itemblocks->datafield" :page="$page" />
    </div>
</div>
