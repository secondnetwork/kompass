<div>


    <div x-data="{ open: @entangle('FormAdjustments') }">
        <x-kompass::offcanvas :w="'w-1/3'" class="p-8 grid gap-4">
            <x-slot name="button">
                <button class="flex btn gap-x-2 justify-end items-center text-md"
                    wire:click="update('{{ $page->id }}')">
                    <x-tabler-device-floppy class="icon-lg" />
                    {{ __('Save') }}
                </button>
            </x-slot>
            <x-slot name="body">


                <div>
                    <strong class="text-gray-600">{{ __('Page Attributes') }}</strong></br>
                    <strong class="text-gray-600">Letztes Update:</strong> {{ $page->updated_at }}</br>
  
                    <x-kompass::select wire:model="page.status" label="Status" placeholder="Select one status" :options="[
                        ['name' => __('published'), 'id' => 'published'],
                        ['name' => __('draft'), 'id' => 'draft'],
                    ]">
                    </x-kompass::select>

                </div>

                @if ($page->status == 'draft')
                        <button class="flex btn gap-x-2 items-center text-md"
                            wire:click="update('{{ $page->id }}','true')">
                            <x-tabler-send class="icon-lg" />
                            {{ __('published') }}
                        </button>
                @endif

                    <x-kompass::select wire:model="page.layout" label="Seite Template" :options="[
                        ['name' => __('Page'), 'id' => 'page'],
                        ['name' => __('Front Page'), 'id' => 'is_front_page'],
                    ]"  />

                <strong class="text-gray-600">SEO:</strong>
                <x-kompass::form.textarea wire:model="page.meta_description" id="name" name="title" label="Description" type="text" class="block w-full h-[10rem]" />
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::action-message class="" on="status" />
    <x-kompass::modal data="FormDelete" />

    <div class="border-b border-gray-200  py-5 grid-3-2 items-center">

        <div class="relative flex items-center">

            <div class=" flex-auto">
                <span class="text-gray-600 text-sm">{{ __('Page title') }}</span>


                <div x-data="click_to_edit()">
                    <a 
                    @click.prevent @click="toggleEditingState" x-show="!isEditing" 
                    class="flex items-center"
                        class="select-none cursor-pointer">
                        <h4 class="text-gray-600">{{ $page->title }} </h4><span>
                            <x-tabler-edit class="cursor-pointer stroke-current  text-gray-400 hover:text-blue-500" />
                        </span>
                    </a>

                    <input type="text" class="focus:outline-none focus:shadow-outline leading-normal"
                        wire:model="page.title" x-show="isEditing" @click.away="toggleEditingState"
                        @keydown.enter="disableEditing" @keydown.window.escape="disableEditing" x-ref="input">
                </div>
                <div class="col-span-6">

                </div>
                @if ($page->layout == 'is_front_page' || $page->layout == 'is_front_page')
                    <strong class="text-gray-400 text-xs">Permalink: </strong><a
                        class="text-gray-400 hover:text-blue-500 text-xs mt-4" href="{{ url('/') }}"
                        target="_blank" rel="noopener noreferrer">{{ url('/') }}</a>
                @else
                    <strong class="text-gray-400 text-xs">Permalink: </strong><a
                        class="text-gray-400 hover:text-blue-500 text-xs mt-4" href="{{ url('/' . $page->slug) }}"
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


                <button class="flex btn gap-x-2 justify-end items-center text-md"
                    wire:click="update('{{ $page->id }}')">
                    <x-tabler-device-floppy class="icon-lg" />
                    {{ __('Save') }}
                </button>


                <button x-data="{ open: @entangle('FormAdjustments') }"
                    class="flex btn gap-x-2 justify-end items-center text-md bg-violet-600 border-violet-600"
                    @click="open = true">
                    <x-tabler-adjustments class="icon-lg" />

                </button>

            </span>

        </div>

    </div>
    <div class="">

        <div class="ordre-1">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div wire:sortable="updateBlocksOrder" wire:sortable-group="updateItemsOrder"
                wire:sortable-group.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
                wire:sortable.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
                class="py-5  ">

                <span class="text-gray-600 text-sm block">Block Builder</span>
                
                @forelse ($blocks as $itemblocks)
                    <x-kompass::blocksgroup :itemblocks="$itemblocks" :fields="$fields" :page="$page" :class="'itemblock border-blue-400 shadow border-r-4 mt-3'" />

                @empty
                    <div
                        class="grid place-content-center border-2 border-dashed border-gray-300 rounded-2xl h-60 text-gray-400">
                        {{ __('Click "Add" to create the layout') }}</div>
                @endforelse
                <div class="flex  justify-end my-6">
                    <button class="btn"
                        wire:click="selectitem('addBlock',{{ $page->id }})">{{ __('Add') }}</button>
                </div>


            </div>

        </div>
    </div>

    <div x-cloak x-data="{ open: @entangle('FormMedia') }" id="FormMedia">
        <x-kompass::offcanvas class="text-gray-500 p-4 m-4">
            <x-slot name="body">
                @livewire('medialibrary', ['fieldId' => $getId])
            </x-slot>
        </x-kompass::offcanvas>
    </div>


    <div x-cloak x-data="{ open: @entangle('FormBlocks') }">
        <x-kompass::offcanvas :w="'w-2/4'">
            <x-slot name="body">
                <div class="grid grid-cols-4">
                    @foreach ($blocktemplates as $itemblock)
                        <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                            wire:click.defer="addBlock({{ $itemblock['id'] }},'{{ $itemblock['name'] }}','{{ $itemblock['type'] }}',{{ $itemblock['grid'] }})">
                            @if ($itemblock['icon_img_path'])
                                <img class=" w-full border-gray-200 border-solid border-2 rounded object-cover"
                                    src="{{ asset('storage/' . $itemblock['icon_img_path']) }}" alt="">
                            @endif
                            <span class="text-xs block mt-2">{{ $itemblock['name'] }}</span>
                        </div>
                    @endforeach
                    <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click.defer="addBlock('','Textblock','wysiwyg','blockquote')">
                        <img src="{{ kompass_asset('icons-blocks/default.png') }}" alt="">
                        <span class="text-xs block mt-2">Textblock</span>
                    </div>

                    <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                    wire:click.defer="addBlock('','Video','video','video')">
                    <img src="{{ kompass_asset('icons-blocks/videoplayer.png') }}" alt="">
                    <span class="text-xs block mt-2">Video</span>
                    </div>

                    <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click.defer="addBlock('','Gallery','gallery','photo')">
                        <img class="rounded" src="{{ kompass_asset('icons-blocks/gallery.png') }}" alt="">
                        <span class="text-xs block mt-2">Gallery</span>
                    </div>

                    <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click.defer="addBlock('','Layout Block','group')">
                        <img src="{{ kompass_asset('icons-blocks/group.png') }}" alt="">
                        <span class="text-xs block mt-2">Layout Block</span>
                    </div>

                    <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click.defer="addBlock('','Accordion Group','accordiongroup')">
                        <img src="{{ kompass_asset('icons-blocks/accordiongroup.png') }}" alt="">
                        <span class="text-xs block mt-2">Accordion</span>
                    </div>
                </div>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

</div>
