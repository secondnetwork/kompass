@props([
    'key' => '',
    'item' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
])


<div class="{{ $class }}" x-data="{ expanded: false }"
    wire:sortable.item="{{ $item->id }}"
    @if ($item->subgroup)
    wire:sortable-group.item="{{ $item->id }}" wire:key="group-{{ $item->id }}"
    @else
    wire:sortable.item="{{ $item->id }}" wire:key="group-{{ $item->id }}" 
    @endif
    >
    
    <div-nav-action class="flex items-center justify-between border-b border-gray-200 px-4">
        <span class="flex items-center py-2 w-full ">
            @if ($item->subgroup)
            <x-tabler-grip-vertical wire:sortable-group.handle class="cursor-move stroke-current h-4 w-4 text-gray-900" />
            @else
            <div wire:sortable.handle><x-tabler-grip-vertical class="cursor-move stroke-current h-4 w-4 text-gray-900" /></div>
            
            @endif
            
            <div x-data="click_to_edit()" class="w-11/12 flex items-center">
                <a @click.prevent @click="toggleEditingState" x-show="!isEditing" class="flex items-center select-none cursor-pointer" x-on:keydown.escape="isEditing = false">
                    @if (!$item->subgroup)
                    <x-tabler-stack class="cursor-pointer stroke-current h-6 w-6 text-green-600" />
                    @endif
                    <span class="text-sm font-semibold">{{ $item->title }}</span>
                    
                    
                    {{-- <span><x-tabler-edit class="cursor-pointer stroke-current h-6 w-6 text-gray-400 hover:text-blue-500" /></span> --}}
                </a>  
                <a x-show="!isEditing" class="text-sm ml-4 bg-gray-100 py-1 px-2 rounded" target="_black" href="{{ $item->url }}">{{ $item->url }}</a>
                {{-- <input titemblock shadow border-r-4 border-b  border-purple-500    group-block  border-purple-600  border-b-2ype="text" value="{{ $item->title }}" x-show="isEditing"
                    @click.away="toggleEditingState" @keydown.enter="disableEditing"
                    @keydown.window.escape="disableEditing" x-ref="input"> --}}


                    <div x-show=isEditing class="flex items-center" x-data="{id: '{{ $item->id}}', name: '{{ $item->title }}'}">
                                
                            <input
                                type="text"
                                class="px-1 border border-gray-400"                 
                                x-model="name"
                                wire:model.lazy="newName" x-ref="input"
                                x-on:keydown.enter="isEditing = false"
                                x-on:keydown.escape="isEditing = false"
                                {{-- @keydown.window.escape="disableEditing"  --}}
                                x-on:click.away="isEditing = false"
                                wire:keydown.enter="savename({{ $item->id }})"
                            >
                            <span wire:click="savename({{ $item->id }})" x-on:click="isEditing = false">
                                <x-tabler-square-check class="cursor-pointer stroke-current h-6 w-6 text-green-600" />
                            </span>
                            <span x-on:click="isEditing = false">
                                <x-tabler-square-x class="cursor-pointer stroke-current h-6 w-6 text-red-600" />
                            </span>
                 
                    </div>
            </div>

        </span>

        <div class="flex items-center gap-2">
            <span wire:click="selectItem({{ $item->id }}, 'update')" class="flex  justify-center "><x-tabler-edit class="cursor-pointer stroke-blue-500"/></span>
        
            <span wire:click="selectItem({{ $item->menu_id }}, 'additem',{{ $item->id }})">
                <x-tabler-subtask class="cursor-pointer stroke-current h-6 w-6 text-blue-600" />
            </span>
                {{-- <span wire:click="clone({{ $item->id }})" class="flex justify-center">
                    <x-tabler-copy class="cursor-pointer  h-6 w-6  stroke-violet-500" />
                </span> --}}
            
            <span wire:click="selectItem({{ $item->id }}, 'deleteblock')" class="flex justify-center">
                <x-tabler-trash class="cursor-pointer stroke-current h-6 w-6 text-red-500" />
            </span>

     
        </div>

    </div-nav-action>


</div>

<div wire:sortable-group.item-group="{{ $item->id }}" class="pl-8 bg-purple-600">
    <x-kompass::menugroupsub :childrensub="$item['children']->sortBy('order')"/>
</div>