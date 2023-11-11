@props([
    'itemblocks' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
])

<div class="{{ $class }} @if ($itemblocks->subgroup) group-block  border-purple-600 @endif border-b-2 "
    wire:sortable.item="{{ $itemblocks->id }}"
    @if ($itemblocks->subgroup) wire:sortable-group.item="{{ $itemblocks->id }}" wire:key="group-{{ $itemblocks->id }}"
    @else
    wire:sortable.item="{{ $itemblocks->id }}" wire:key="group-{{ $itemblocks->id }}" @endif
    x-data="{ expanded: false }">

    <div-nav-action class="flex items-center justify-between border-b border-gray-200 px-4"
    
    @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup') :class="'bg-slate-200 border-slate-600'" @endif
    >
        <span class="flex items-center py-2 w-full ">
            @if ($itemblocks->subgroup)
                <span wire:sortable-group.handle>
                <x-tabler-grip-vertical 
                    class="cursor-move stroke-current h-6 w-6 mr-1 text-gray-900" />
                </span>
            @else
            <span wire:sortable.handle>
                <x-tabler-grip-vertical 
                    class="cursor-move stroke-current h-6 w-6 mr-1 text-gray-900" />
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
                    {{-- <span><x-tabler-edit class="cursor-pointer stroke-current h-6 w-6 text-gray-400 hover:text-blue-500" /></span> --}}
                </a>

                {{-- <input type="text" value="{{ $itemblocks->name }}" x-show="isEditing"
                    @click.away="toggleEditingState" @keydown.enter="disableEditing"
                    @keydown.window.escape="disableEditing" x-ref="input"> --}}


                <div x-show=isEditing class="flex items-center" x-data="{ id: '{{ $itemblocks->id }}', name: '{{ $itemblocks->name }}' }">

                    <input type="text" class="border border-gray-400 px-1 py-1 text-sm font-semibold" x-model="name" wire:model.lazy="newName"
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
            @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup')
            <span @click="expanded = ! expanded">
                <x-tabler-adjustments class="cursor-pointer stroke-current h-6 w-6 text-stone-500"/>
            </span>
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

    <div x-show="expanded" x-collapse>
        <nav class="px-6 py-2 bg-gray-200 shadow-inner shadow-black/20 flex items-center gap-6 @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup')  border-b-4 border-purple-700 @endif">

            <x-kompass::nav-item :itemblocks="$itemblocks" />
            
        </nav>
        <div class="grid gap-6 grid-cols-{{ $itemblocks->grid }} @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup') p-0 @else p-6 @endif" >


            @dump($fields)
            @dump($itemblocks->id)

            @switch($itemblocks->type)

            @case('video')
            <div>
                Das ist eine Hinweis
            </div>
            <div class="@container">
                <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4  gap-6">
                    @php
                    $notempty = '0';
                    @endphp
                    @foreach ($fields as $key => $itemfields)
                        @if ($itemblocks->id == $itemfields->block_id && $itemfields->type =='poster')
                        @php
                            $notempty = '1';
                        @endphp
                        <x-kompass::blocks key="{{ $key }}" type="{{ $itemfields->type }}"
                            name="{{ $itemfields->name }}" fields="{!! $fields[$key]['data'] !!}"
                            idField="{{ $fields[$key]['id'] }}" blockId="{{ $itemblocks->id }}">
                        </x-kompass::blocks>
                        @else
                        @php
                            $notempty = '0';
                        @endphp
                        @endif

                    @endforeach


                    {{ $notempty }}
                        @if ($notempty=='0')
                        <div>
                            Thumbnails        
                            <img-block wire:click="selectitem('addMedia',0,'poster',{{ $itemblocks->id }})"
                                class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-[4/3] ">
                                <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                            </img-block>
                        </div>
                        @endif
        
                    <div>
                        Video Datei
                        <img-block wire:click="selectitem('addMedia',0,'video',{{ $itemblocks->id }})"
                            class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-[4/3] ">
                            <x-tabler-video-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
                        </img-block>
                    </div>
                    <div>
                        <x-tabler-brand-youtube/> YouTube oder <x-tabler-brand-vimeo/>  Vimeo URL
                    
                        <x-kompass::form.input  type="text" />
                    </div>

                </div>
            </div>
        @break
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


