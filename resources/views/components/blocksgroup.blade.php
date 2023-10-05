@props([
    'itemblocks' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
])
@php

    $type = $itemblocks->set->type ?? '';
    
@endphp
<div class="{{ $class }} @if ($itemblocks->subgroup) group-block  border-purple-600 @endif border-b-2 "
    wire:sortable.item="{{ $itemblocks->id }}"
    @if ($itemblocks->subgroup) wire:sortable-group.item="{{ $itemblocks->id }}" wire:key="group-{{ $itemblocks->id }}"
    @else
    wire:sortable.item="{{ $itemblocks->id }}" wire:key="group-{{ $itemblocks->id }}" @endif
    x-data="{ expanded: false }">

    <div-nav-action class="flex items-center justify-between border-b border-gray-200 px-4">
        <span class="flex items-center py-2 w-full ">
            @if ($itemblocks->subgroup)
                <x-tabler-grip-vertical wire:sortable-group.handle
                    class="cursor-move stroke-current h-6 w-6 mr-1 text-gray-900" />
            @else
                <x-tabler-grip-vertical wire:sortable.handle
                    class="cursor-move stroke-current h-6 w-6 mr-1 text-gray-900" />
            @endif

            <span @click="expanded = ! expanded"
                class="text-xs inline-flex items-center gap-1.5 py-1 px-1 capitalize rounded font-semibold  text-gray-400 cursor-pointer">
                @if ($itemblocks->iconclass)
                    @svg('tabler-' . $itemblocks->iconclass, 'w-5')
                @else
                    @svg('tabler-section', 'w-5')
                @endif
                {{-- {{ $itemblocks->slug }} --}}

            </span>
            <span class="inline-block border-r border-gray-400 w-px h-5 ml-1 mr-2"></span>
            <div x-data="click_to_edit()" class="w-11/12 flex items-center">
                <a @click.prevent @click="toggleEditingState" x-show="!isEditing"
                    class="flex items-center select-none cursor-text" x-on:keydown.escape="isEditing = false">
                    @if ($type == 'group')
                        <x-tabler-stack class="cursor-pointer stroke-current h-6 w-6 text-green-600" />
                    @endif
                    <span class="text-sm font-semibold">{{ $itemblocks->name }}</span>
                    {{-- <span><x-tabler-edit class="cursor-pointer stroke-current h-6 w-6 text-gray-400 hover:text-blue-500" /></span> --}}
                </a>

                {{-- <input type="text" value="{{ $itemblocks->name }}" x-show="isEditing"
                    @click.away="toggleEditingState" @keydown.enter="disableEditing"
                    @keydown.window.escape="disableEditing" x-ref="input"> --}}


                <div x-show=isEditing class="flex items-center" x-data="{ id: '{{ $itemblocks->id }}', name: '{{ $itemblocks->name }}' }">

                    <input type="text" class="px-1 border border-gray-400" x-model="name" wire:model.lazy="newName"
                        x-ref="input" x-on:keydown.enter="isEditing = false" x-on:keydown.escape="isEditing = false"
                        {{-- @keydown.window.escape="disableEditing"  --}} x-on:click.away="isEditing = false"
                        wire:keydown.enter="savename({{ $itemblocks->id }})">
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
            @if ($type == 'group')
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
            @endif
            @if ($type != 'group')
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
                <div class="flex items-center gap-2">
                    <span :class="!expanded ? '' : 'rotate-180'" @click="expanded = ! expanded"
                        class="transform transition-transform duration-500">
                        <x-tabler-chevron-down class="cursor-pointer stroke-current h-6 w-6 text-gray-900 " />
                    </span>
                </div>
            @endif
        </div>

    </div-nav-action>

    <div @if ($type !== 'group') x-show="expanded" x-collapse @endif>
        <nav class="px-6 py-2 bg-gray-200 shadow-inner shadow-black/20 flex items-center gap-6 @if ($type == 'group')  border-b-4 border-purple-700 @endif">
            {{-- <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-yellow-900 text-yellow-300">Dev</span> --}}

            <x-kompass::nav-item :itemblocks="$itemblocks" />
            
        </nav>
        <div class="@if ($type !== 'group')grid gap-6 p-6 grid-cols-{{ $itemblocks->grid }} @endif" >

            @switch($type)
                @case('gallery')
                    <div class="@container">
                        <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4  gap-6">

                            @foreach ($fields as $key => $itemfields)
                                @if ($itemblocks->id == $itemfields->block_id)
                                    <x-kompass::blocks key="{{ $key }}" type="{{ $itemfields->type }}"
                                        name="{{ $itemfields->name }}" fields="{!! $fields[$key]['data'] !!}"
                                        idField="{{ $fields[$key]['id'] }}" blockId="{{ $itemblocks->id }}">
                                    </x-kompass::blocks>
                                @endif
                            @endforeach
                         
                            <img-block wire:click="selectitem('addMedia',0,'gallery',{{ $itemblocks->id }})"
                                class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-[4/3] ">
                                <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                            </img-block>
                        </div>
                    </div>
                @break

                @case('wysiwyg')
                    @foreach ($fields as $key => $itemfields)
                        @if ($itemblocks->id == $itemfields->block_id)
                            <div class="col-span-{{ $itemfields->grid }}" style="order: {{ $itemfields->order }} ">

                                @php
                                    $jsfield = json_decode($fields[$key]['data'], true);
                                    $gridtables = $fields[$key]['grid'];
                                @endphp

                                @livewire(
                                    'editorjs',
                                    [
                                        'editorId' => $fields[$key]['id'],
                                        'value' => $jsfield,
                                        'uploadDisk' => 'publish',
                                        'downloadDisk' => 'publish',
                                        'class' => 'cdx-input',
                                        'style' => '',
                                        // 'readOnly' => true,
                                        'placeholder' => __('write something...'),
                                    ],
                                    key($fields[$key]['id'])
                                )
                            </div>
                        @endif
                    @endforeach
                @break

                @default
                    @foreach ($fields as $key => $itemfields)
                        @if ($itemblocks->id == $itemfields->block_id)
                            <div class="col-span-{{ $itemfields->grid }}" style="order: {{ $itemfields->order }} ">

                                <x-kompass::blocks key="{{ $key }}" type="{{ $itemfields->type }}"
                                    name="{{ $itemfields->name }}" fields="{!! $fields[$key]['data'] !!}"
                                    idField="{{ $fields[$key]['id'] }}" blockId="{{ $itemblocks->id }}">
                                </x-kompass::blocks>

                            </div>
                        @endif
                    @endforeach
            @endswitch


        </div>

    </div>
<div wire:sortable-group.item-group="{{ $itemblocks->id }}" class="pl-4 bg-purple-700">
    <x-kompass::blocksgroupsub :childrensub="$itemblocks['children']->sortBy('order')" :fields="$fields" :page="$page" />
</div>
</div>


