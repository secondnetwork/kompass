@props([
    'keyblock' => '',
    'itemblocks' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
])


<div  class="{{ $class }} @if ($itemblocks->type == 'group') bg-white  @endif  @if ($itemblocks->subgroup) group-block  border-purple-600 @endif border-b-2 " 
    wire:sortable.item="{{ $itemblocks->id }}"
    @if ($itemblocks->subgroup)
    wire:sortable-group.item="{{ $itemblocks->id }}" wire:key="group-{{ $itemblocks->id }}"
    @else
    wire:sortable.item="{{ $itemblocks->id }}" wire:key="group-{{ $itemblocks->id }}" 
    @endif
x-data="{ expanded: false }" >
    
    <div-nav-action class="flex items-center justify-between border-b border-gray-200 px-4">
        <span class="flex items-center py-3 w-full ">
            @if ($itemblocks->subgroup)
            <x-tabler-grip-vertical wire:sortable-group.handle class="cursor-move stroke-current h-6 w-6 mr-1 text-gray-900" />
            @else
            <x-tabler-grip-vertical wire:sortable.handle class="cursor-move stroke-current h-6 w-6 mr-1 text-gray-900" />
            @endif
            <div x-data="click_to_edit()" class="w-11/12 flex items-center">
                <a @click.prevent @click="toggleEditingState" x-show="!isEditing" class="flex items-center select-none cursor-pointer" x-on:keydown.escape="isEditing = false">
                    @if ($itemblocks->type == 'group')
                    <x-tabler-stack class="cursor-pointer stroke-current h-6 w-6 text-green-600" />
                    @endif
                    <span class="text-md font-semibold">{{ $itemblocks->name }}</span>
                    {{-- <span><x-tabler-edit class="cursor-pointer stroke-current h-6 w-6 text-gray-400 hover:text-blue-500" /></span> --}}
                </a>
      
                {{-- <input type="text" value="{{ $itemblocks->name }}" x-show="isEditing"
                    @click.away="toggleEditingState" @keydown.enter="disableEditing"
                    @keydown.window.escape="disableEditing" x-ref="input"> --}}


                    <div x-show=isEditing class="flex items-center" x-data="{id: '{{ $itemblocks->id}}', name: '{{ $itemblocks->name }}'}">
                                
                            <input
                                type="text"
                                class="px-1 border border-gray-400"                 
                                x-model="name"
                                wire:model.lazy="newName" x-ref="input"
                                x-on:keydown.enter="isEditing = false"
                                x-on:keydown.escape="isEditing = false"
                                {{-- @keydown.window.escape="disableEditing"  --}}
                                x-on:click.away="isEditing = false"
                                wire:keydown.enter="savename({{ $itemblocks->id }})"
                            >
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
            @if ($itemblocks->type == 'group')
                <span wire:click="selectItem({{ $page->id }}, 'addBlock', {{ $itemblocks->id }})">
                    <x-tabler-layout-grid-add class="cursor-pointer stroke-current h-6 w-6 text-blue-600" />
                </span>
                <span wire:click="selectItem({{ $itemblocks->id }}, 'deleteblock')" class="flex justify-center">
                  <x-tabler-trash class="cursor-pointer stroke-current h-6 w-6 text-red-500" />
              </span>
            @endif
            @if ($itemblocks->type != 'group')
                @if ($itemblocks->status == 'public')
                    <span wire:click="status({{ $itemblocks->id }}, 'unpublish')">
                        <x-tabler-eye class="cursor-pointer stroke-current h-6 w-6 text-gray-400" />
                    </span>
                @else
                    <span wire:click="status({{ $itemblocks->id }}, 'public')">
                        <x-tabler-eye-off class="cursor-pointer stroke-current h-6 w-6 text-red-500" />
                    </span>
                @endif

                <span wire:click="clone({{ $itemblocks->id }})" class="flex justify-center">
                    <x-tabler-copy class="cursor-pointer  h-6 w-6  stroke-violet-500" />
                </span>
            
            <span wire:click="selectItem({{ $itemblocks->id }}, 'deleteblock')" class="flex justify-center">
                <x-tabler-trash class="cursor-pointer stroke-current h-6 w-6 text-red-500" />
            </span>
            <div class="flex items-center gap-2">
                <span :class="!expanded ? '' : 'rotate-180'" @click="expanded = ! expanded" class="transform transition-transform duration-500">
                    <x-tabler-chevron-down class="cursor-pointer stroke-current h-6 w-6 text-gray-900 " />
                </span>
            </div>
            @endif
        </div>

    </div-nav-action>

    <div x-show="expanded" x-collapse >
        <nav class="px-6 py-2 bg-gray-200 shadow-inner flex items-center gap-6">
            {{-- <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-yellow-900 text-yellow-300">Dev</span> --}}
@php
    $layout = $itemblocks->set->layout ?? '';
    $alignment = $itemblocks->set->alignment ?? '';
    $slider = $itemblocks->set->slider ?? '';
    $type = $itemblocks->set->type ?? '';
@endphp

            <nav-item class="flex items-center gap-2">
            <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Grid Layout</span>
                <span class="cursor-pointer" wire:click="set({{ $itemblocks->id }},'layout', '')">
                    @if ($layout == '')
                    <x-tabler-columns-3 class="stroke-blue-500"/>
                    @else
                    <x-tabler-columns-3/>
                    @endif
                </span>    
                <span class="cursor-pointer" wire:click="set({{ $itemblocks->id }},'layout', 'popout')">
                    @if ($layout == 'popout')
                    <x-tabler-carousel-vertical class="stroke-blue-500"/>
                    @else
                    <x-tabler-carousel-vertical/>  
                    @endif
                </span>    
                <span class="cursor-pointer" wire:click="set({{ $itemblocks->id }},'layout', 'full')">
                    @if ($layout == 'full')
                    <x-tabler-arrow-autofit-width class="stroke-blue-500"/>
                    @else
                    <x-tabler-arrow-autofit-width />    
                    @endif
                </span>    
                
               
            </nav-item>
     
            <nav-item class="flex items-center gap-2">
                <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300 ">{{__('Alignment')}}</span>
                <span class="cursor-pointer" wire:click="set({{ $itemblocks->id }},'alignment', '')">
                    @if ($alignment == '')
                    <x-tabler-box-align-left class="stroke-blue-500"/>
                    @else
                    <x-tabler-box-align-left/>
                    @endif
                </span>    
                <span class="cursor-pointer" wire:click="set({{ $itemblocks->id }},'alignment', 'right')">
                    @if ($alignment == 'right')
                    <x-tabler-box-align-right  class="stroke-blue-500"/>
                    @else
                    <x-tabler-box-align-right />  
                    @endif
                </span>    

            </nav-item>

            @if ($type == 'gallery')
            <nav-item class="flex items-center gap-2">
                <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300 ">Slider</span>
                <span class="cursor-pointer" wire:click="set({{ $itemblocks->id }},'slider', '')">
                    @if ($slider == '')
                    <x-tabler-layout-dashboard class="stroke-blue-500 rotate-90"/>
                    @else
                    <x-tabler-layout-dashboard class="rotate-90"/>
                    @endif
                </span>    
                <span class="cursor-pointer" wire:click="set({{ $itemblocks->id }},'slider', 'true')">
                    @if ($slider == 'true')
                    <x-tabler-carousel-horizontal  class="stroke-blue-500"/>
                    @else
                    <x-tabler-carousel-horizontal />  
                    @endif
                </span>    
            </nav-item>
            @endif
            {{-- <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" value="" class="sr-only peer">
                <div class=" w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                <span class="ml-3 text-sm font-medium text-gray-900 ">Small toggle</span>
              </label>

              <div>
     
                <select id="small" class="text-sm font-medium px-2 py-1 pr-1 rounded text-gray-900 bg-gray-300">
                  <option selected>Choose a country</option>
                  <option value="US">United States</option>
                  <option value="CA">Canada</option>
                  <option value="FR">France</option>
                  <option value="DE">Germany</option>
                </select>
              </div> --}}


        </nav>
        
        <div class="grid gap-6 p-6 grid-cols-{{ $itemblocks->grid }}">

        @if ($itemblocks->set->type ?? '' == 'gallery')
        <div class="@container">
        <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4  gap-6" >

        @foreach ($fields as $key => $itemfields)
            @if ($itemblocks->id == $itemfields->block_id)
                
                    <x-kompass::blocks key="{{ $key }}" type="{{ $itemfields->type }}"
                        name="{{ $itemfields->name }}" fields="{!! $fields[$key]['data'] !!}"
                        idField="{{ $fields[$key]['id'] }}" blockId="{{$itemblocks->id}}">
                    </x-kompass::blocks>

            @endif
        @endforeach
            <img-block wire:click="selectItem(0, 'addMedia' ,{{$itemblocks->id}})"
            class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 rounded-2xl w-full text-gray-400 aspect-[4/3] ">
            <x-tabler-photo-plus class="h-[4rem] w-[4rem] stroke-[1.5]" />
            </img-block>
        </div>
        </div>
        @else
        
        @foreach ($fields as $key => $itemfields)
            @if ($itemblocks->id == $itemfields->block_id)
                <div class="col-span-{{ $itemfields->grid }}" style="order: {{ $itemfields->order }} ">

                    <x-kompass::blocks key="{{ $key }}" type="{{ $itemfields->type }}"
                        name="{{ $itemfields->name }}" fields="{!! $fields[$key]['data'] !!}"
                        idField="{{ $fields[$key]['id'] }}" blockId="{{$itemblocks->id}}">
                    </x-kompass::blocks>

                </div>
            @endif
        @endforeach
        @endif

    </div>
    </div>

</div>

<div wire:sortable-group.item-group="{{ $itemblocks->id }}" class="pl-8 bg-purple-100">
    <x-kompass::blocksgroupsub :childrensub="$itemblocks['children']->sortBy('order')" :fields="$fields" :page="$page"/>
</div>