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
            <x-tabler-grip-vertical wire:sortable.handle class="cursor-move stroke-current h-4 w-4 text-gray-900" />
            @endif
            
            <div x-data="click_to_edit()" class="w-11/12 flex items-center">
                <a @click.prevent @click="toggleEditingState" x-show="!isEditing" class="flex items-center select-none cursor-pointer" x-on:keydown.escape="isEditing = false">
                    @if (!$item->subgroup)
                    <x-tabler-stack class="cursor-pointer stroke-current h-6 w-6 text-green-600" />
                    @endif
                    <span class="text-md font-semibold">{{ $item->title }}</span>
                    
                    
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
        
            <span wire:click="selectItem({{ $item->menu_id }}, 'additem',{{ $item->id }})">
                <x-tabler-subtask class="cursor-pointer stroke-current h-6 w-6 text-blue-600" />
            </span>
                {{-- <span wire:click="clone({{ $item->id }})" class="flex justify-center">
                    <x-tabler-copy class="cursor-pointer  h-6 w-6  stroke-violet-500" />
                </span> --}}
            
            <span wire:click="selectItem({{ $item->id }}, 'deleteblock')" class="flex justify-center">
                <x-tabler-trash class="cursor-pointer stroke-current h-6 w-6 text-red-500" />
            </span>
            <div class="flex items-center gap-2">
                <span :class="!expanded ? '' : 'rotate-180'" @click="expanded = ! expanded" class="transform transition-transform duration-500">
                    <x-tabler-chevron-down class="cursor-pointer stroke-current h-6 w-6 text-gray-900 " />
                </span>
            </div>
     
        </div>

    </div-nav-action>

    <div 
    {{-- x-show="expanded" x-collapse  --}}
    class="grid gap-6 p-6 grid-cols-{{ $item->grid }} ">
        

        <div x-data="{id: '{{ $item->id}}', url: '{{ $item->url }}', iconclass: '{{ $item->iconclass }}', color: '{{ $item->color }}', target: '{{ $item->target }}'}"
            >
            <label>URL</label>
            <input wire:model.lazy="url" x-on:blur="$wire.set('item.url', url)" x-model="url" type="text" class="form-control" />
            <input wire:model.lazy="iconclass" x-model="iconclass" type="text" class="form-control" />

            {{-- <label>icon_class</label>
            <input wire:model="icon_class" x-model="icon_class" type="text" class="form-control" />
            @if ($errors->has('icon_class'))
            <p style="color: red;">{{ $errors->first('icon_class') }}</p>
            @endif
            <label>color</label>
            <input wire:model="color"  x-model="color" type="text" class="form-control" />
            @if ($errors->has('color'))
            <p style="color: red;">{{ $errors->first('name') }}</p>
            @endif --}}
    
            <label>{{__('Open')}}</label>
            <select wire:model="target" x-model="target">
                <option value="_self">{{__('Same tab')}}</option>
                <option value="_blank">{{__('New tab')}}</option>
            </select>

            <div class="flex gap-x-2 pt-8 justify-end items-center">

            </div>
            <button class="flex gap-x-2   justify-end items-center"
            
            wire:click="updateitem({{ $item->id }})">
            <x-tabler-device-floppy class="icon-lg" />
            {{ __('Save') }}
            </button>
        </div>

 


    </div>

</div>

<div wire:sortable-group.item-group="{{ $item->id }}" class="pl-8 bg-purple-600">
    <x-kompass::menugroupsub :childrensub="$item['children']->sortBy('order')"/>
</div>