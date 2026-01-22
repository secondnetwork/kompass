<div>

    <div x-data="{ open: @entangle('FormAdjustments') }">
        <x-kompass::offcanvas :w="'w-1/3'" class="p-8 grid gap-4">
            <x-slot name="button">
                <button class="btn btn-primary"
                    wire:click="update('{{ $page->id }}')">
                    <div wire:loading>
                        <svg class="animate-spin h-5 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <x-tabler-device-floppy class="icon-lg" wire:loading.remove />
                    {{ __('Save') }}
                </button>
            </x-slot>
            <x-slot name="body">
                <div>
                    <strong class="text-gray-600">{{ __('Page Attributes') }}</strong></br>
                    <strong class="text-gray-600">Letztes Update:</strong> {{ $page->updated_at }}</br>
  
                    <x-kompass::select wire:model="status" label="Status" placeholder="Select one status" :options="[
                        ['name' => __('published'), 'id' => 'published'],
                        ['name' => __('draft'), 'id' => 'draft'],
                    ]">
                    </x-kompass::select>

                </div>

                {{-- @if ($status == 'draft')
                        <button class="btn btn-primary"
                            wire:click="update('{{ $page->id }}','true')">
                            <x-tabler-send class="icon-lg" />
                            {{ __('published') }}
                        </button>
                @endif --}}

                    <x-kompass::select wire:model="layout" label="Seite Template" :options="[
                        ['name' => __('Page'), 'id' => 'page'],
                        ['name' => __('Front Page'), 'id' => 'is_front_page'],
                    ]"  />

                <strong class="text-gray-600">SEO:</strong>
                <x-kompass::form.textarea wire:model="description" id="name" name="title" label="Description" type="text" class="block w-full h-[10rem]" />
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div class=" grid-3-2 items-center">

        <div class="relative flex items-center">

            <div class=" flex-auto">


                <div x-data="click_to_edit()">
                    <a 
                    @click.prevent @click="toggleEditingState" x-show="!isEditing" 
                    class="flex items-center"
                        class="select-none cursor-pointer">
                        <h4 class="text-gray-600">{{ $title }} </h4><span>
                            <x-tabler-edit class="cursor-pointer stroke-current  text-gray-400 hover:text-blue-500" />
                        </span>
                    </a>

                    <input type="text" class="focus:outline-none focus:shadow-outline leading-normal"
                        wire:model.live="title" x-show="isEditing" @click.away="toggleEditingState"
                        @keydown.enter="disableEditing" @keydown.window.escape="disableEditing" x-ref="input">
                </div>
                <div class="col-span-6">

                </div>
                <strong class="text-gray-400 text-xs">Permalink: </strong>
                @if ($layout == 'is_front_page' || $layout == 'is_front_page')
                    <a class="text-gray-400 hover:text-blue-500 text-xs mt-4" href="{{ url('/') }}"
                        target="_blank" rel="noopener noreferrer">{{ url('/') }}</a>
                @else
                    <a class="text-gray-400 hover:text-blue-500 text-xs mt-4" href="{{ url('/' . $page->slug) }}"
                        target="_blank" rel="noopener noreferrer">{{ url('/' . $page->slug) }}</a>
                @endif
            </div>


        </div>

        

        <div class="flex gap-4 justify-end items-center">



            <span x-data="{ open: false }" class="relative transition-all flex gap-4">

                @switch($page->status)
                    @case('published')
                        <span class="flex gap-x-2 justify-end items-center text-md  text-gray-900">

                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-[ping_3s_ease-in-out_infinite] absolute inline-flex h-full w-full rounded-full bg-teal-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                            </span>
                            Live
                        </span>
                    @break

                    @case('password')
                        <span class="flex gap-x-2 justify-end items-center text-md  text-gray-900">
                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-[ping_3s_ease-in-out_infinite] absolute inline-flex h-full w-full rounded-full bg-purple-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-purple-500"></span>
                            </span>
                            Passwort
                        </span>
                    @break

                    @default
                        <span class="flex gap-x-2 justify-end items-center text-md border-gray-300 text-gray-900 mx-2">

                            <span class="relative flex h-3 w-3">

                                <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-500"></span>
                            </span>
                            {{ __('draft') }}
                        </span>
                @endswitch


                <button class="btn btn-primary"
                    wire:click="update('{{ $page->id }}')">
                    <div wire:loading>
                        <svg class="animate-spin h-5 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <x-tabler-device-floppy class="icon-lg" wire:loading.remove />
                    {{ __('Save') }}
                </button>


                <button x-data="{ open: @entangle('FormAdjustments') }"
                    class="btn btn-primary"
                    @click="open = true">
                    <x-tabler-adjustments class="icon-lg" />

                </button>

            </span>

        </div>
    
    </div>
    <div class="divider"></div>
    <div class="ordre-1">

        <div wire:sortable="updateBlocksOrder" wire:sortable-group="updateItemsOrder"
            wire:sortable-group.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
            wire:sortable.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
            class="">

            @forelse ($blocks as $itemblocks)

                <x-kompass::blocksgroup :itemblocks="$itemblocks" :fields="$itemblocks->datafield" :class="'itemblock border-blue-400 shadow border-r-4 mt-3'" />

            @empty
                <div
                    class="grid place-content-center border-2 border-dashed border-gray-300 rounded-2xl h-60 text-gray-400">
                    {{ __('Click "Add" to create the layout') }}
                </div>
            @endforelse
            <div class="flex  justify-end my-6">
                <button class="btn btn-primary"
                    wire:click="selectitem('addBlock',{{ $page->id }})">{{ __('Add') }}</button>
            </div>


        </div>

    </div>
    
    <div class="relative z-50" x-cloak x-data="{ open: @entangle('FormMedia') }" id="FormMedia">
        <x-kompass::offcanvas class="text-base-content/70 p-4 m-4">
            <x-slot name="body">
                @livewire('medialibrary', ['fieldId' => $getId])
            </x-slot>
        </x-kompass::offcanvas>
    </div>
    <div class="relative z-40" x-cloak x-data="{ open: @entangle('FormEditBlock')}">
        <x-kompass::offcanvas :w="'w-3/4'">
            <x-slot name="body">

                @foreach  ($datafield as $itemblocks)

                <x-kompass::blocks-datafield :itemblocks="$itemblocks" :fields="$itemblocks->datafield" :cssclassname="$cssClassname" :class="'itemblock border-blue-400 shadow border-r-4 mt-3'" />
                

                {{-- @foreach ($itemblocks->datafield as $itemfields)
                <livewire:datafield-item :datafield="$itemfields" :key="$itemfields->id" />
                @endforeach
                <x-kompass::nav-item :itemblocks="$itemblocks" /> --}}
                @endforeach

        <div>
            <button class="btn btn-primary"
            wire:click="update('{{ $page->id }}')">
            <div wire:loading>
                <svg class="animate-spin h-5 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <x-tabler-device-floppy class="icon-lg" wire:loading.remove />
            {{ __('Save') }}
            </button>
        </div>

                
            </x-slot>
        </x-kompass::offcanvas>
        
    </div>

    <x-kompass::action-message class="" on="status" />
    <x-kompass::modal data="FormDelete" />

    <div x-cloak x-data="{ open: @entangle('FormBlocks') }">
        <x-kompass::offcanvas :w="'w-2/4'">
            <x-slot name="body">

                <div class="grid grid-cols-4">

                    <div class="border-blue-600 border-2 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click="addBlock('','Textblock','wysiwyg','blockquote')">
                        <img src="{{ kompass_asset('icons-blocks/default.png') }}" alt="">
                        <span class="text-xs block mt-2">Textblock</span>
                    </div>

                    <div class=" border-purple-600 border-2 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click="addBlock('','Layout Block','group')">
                        <img src="{{ kompass_asset('icons-blocks/group.png') }}" alt="">
                        <span class="text-xs block mt-2">Layout Block</span>
                    </div>

                    <div class=" border-purple-600 border-2 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click="addBlock('','Accordion Group','accordiongroup')">
                        <img src="{{ kompass_asset('icons-blocks/accordiongroup.png') }}" alt="">
                        <span class="text-xs block mt-2">Accordion</span>
                    </div>


                    {{-- <div class="border-blue-600 border-2 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click="addBlock('','Button','button','box-model-2')">
                        <img src="{{ kompass_asset('icons-blocks/button.png') }}" alt="">
                        <span class="text-xs block mt-2">Button</span>
                    </div>

                    <div class="border-blue-600 border-2 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click="addBlock('','Download','download','download')">
                        <img src="{{ kompass_asset('icons-blocks/download.png') }}" alt="">
                        <span class="text-xs block mt-2">Download</span>
                    </div> --}}

                    <div class="border-blue-600 border-2 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click="addBlock('','Video','video','video')">
                        <img src="{{ kompass_asset('icons-blocks/videoplayer.png') }}" alt="">
                        <span class="text-xs block mt-2">Video</span>
                    </div>

                    <div class="border-blue-600 border-2 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click="addBlock('','Gallery','gallery','photo')">
                        <img class="rounded" src="{{ kompass_asset('icons-blocks/gallery.png') }}" alt="">
                        <span class="text-xs block mt-2">Images and Gallery</span>
                    </div>

                    {{-- <div class="border-blue-600 border-2 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click="addBlock('','Anchormenu','anchormenu','anchor')">
                        <img src="{{ kompass_asset('icons-blocks/anchormenu.png') }}" alt="">
                        <span class="text-xs block mt-2">Anchor menu</span>
                    </div> --}}



                    @foreach ($blocktemplates as $itemblock)
                    <div class="border-gray-400 border-2 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click="addBlock({{ $itemblock['id'] }},'{{ $itemblock['name'] }}','{{ $itemblock['type'] }}','{{ $itemblock['iconclass'] }}')">
                        @if ($itemblock['icon_img_path'])
                            <img class=" w-full border-gray-200 border-solid border-2 rounded object-cover"
                                src="{{ asset('storage/' . $itemblock['icon_img_path']) }}" alt="">
                        @endif
                        <span class="text-xs block mt-2">{{ $itemblock['name'] }}</span>
                    </div>

     
                @endforeach
                </div>
            </x-slot>
        </x-kompass::offcanvas>
</div>
