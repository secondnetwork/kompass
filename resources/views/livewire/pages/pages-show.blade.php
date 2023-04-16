<div x-data="{ tab: window.location.hash ? window.location.hash.substring(1) : 'block-builder' }">

    <x-kompass::action-message  class="" on="status" />
    <x-kompass::modal data="FormDelete" />

    <div class="border-b border-gray-200  py-5 grid-3-2 items-center">
        <div id="tab_wrapper" class="relative">
            <!-- The tabs navigation -->
            <nav class="">
                <a class="text-base  border-b p-5"
                    :class="{ 'active  border-current text-blue-600': tab === 'block-builder' }"
                    @click.prevent="tab = 'block-builder'; window.location.hash = 'block-builder'" href="#">{{__('Block Builder')}}</a>
                <a class="text-base  border-b p-5"
                    :class="{ 'active  border-current text-blue-600': tab === 'attribute' }"
                    @click.prevent="tab = 'attribute'; window.location.hash = 'attribute'"
                    href="#">{{__('Page Attributes')}}</a>
                {{-- <a class="text-base text-gray-400 border-b p-[1.6rem]"
                    :class="{ 'active  border-current text-blue-600': tab === 'seo' }"
                    @click.prevent="tab = 'seo'; window.location.hash = 'seo'" href="#">SEO</a> --}}
            </nav>

        </div>
        <div class="flex justify-end items-center">
            <div class="flex justify-end gap-4">

                @if ($page->status == 'unpublish')
                <button class="flex gap-x-2 justify-end items-center text-md"
                    wire:click="update('{{ $page->id }}','true')">
                    <x-tabler-send class="icon-lg" />
                    {{ __('Publish') }}
                </button>
                @endif
                <button class="flex gap-x-2 justify-end items-center text-md"
                wire:click="update('{{ $page->id }}')">
                <x-tabler-device-floppy class="icon-lg" />
                {{ __('Save') }}
             </button>

                
            </div>
        </div>

    </div>
    <div x-show="tab === 'attribute'" class="p-10">
        Seite Template:
        <select wire:model="page.layout" name="layout">
            <option value="NULL">Page</option>
            <option value="is_front_page">Front Page</option>
            <option value="is_404">404 Page</option>
        </select>

    </div>

    <div x-show="tab === 'seo'" class="order-2 p-10">


        SEO:
        <x-kompass::form.textarea wire:model.defer="page.meta_description" id="name" name="title"
            label="Description" type="text" class="mt-1 block w-full h-[20rem]" />
        Thumbnails
        <img src="{{ $page->thumbnails }}" alt="">
        <pre>
                {{ $page->content }}
                {{ $page->layout }}
            </pre>
    </div>

    <div class="" x-show="tab === 'block-builder'">

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

            <div class="flex justify-center items-center py-10">
                <div class=" flex-auto">
                    <span class="text-gray-400 text-base">{{__('Page title')}}</span>


                    <div x-data="click_to_edit()">
                        <a @click.prevent @click="toggleEditingState" x-show="!isEditing" class="flex"
                            class="select-none cursor-pointer">
                            <h3>{{ $page->title }} </h3><span><x-tabler-edit class="cursor-pointer stroke-current h-8 w-8 text-gray-400 hover:text-blue-500" /></span>
                        </a>

                        <input type="text" class="focus:outline-none focus:shadow-outline leading-normal"
                            wire:model="page.title" x-show="isEditing" @click.away="toggleEditingState"
                            @keydown.enter="disableEditing" @keydown.window.escape="disableEditing" x-ref="input">
                    </div>
                    <div class="col-span-6">

                    </div>
                    @if ($page->layout == 'is_front_page' || $page->layout == 'is_front_page')
                    <strong class="text-gray-400 text-sm">Permalink: </strong><a class="text-gray-400 text-sm mt-4" href="{{ url('/') }}" target="_blank"
                            rel="noopener noreferrer">{{ url('/') }}</a>
                    @else
                        <strong class="text-gray-400 text-sm">Permalink: </strong><a class="text-gray-400 text-sm mt-4" href="{{ url('/' . $page->slug) }}" target="_blank"
                            rel="noopener noreferrer">{{ url('/' . $page->slug) }}</a>
                    @endif
                </div>

            </div>

         

            <div wire:sortable="updateBlocksOrder" 
                wire:sortable-group="updateItemsOrder"
                wire:sortable-group.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
                wire:sortable.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }"
                class="py-5  ">
                
                <span class="text-gray-400 text-base block">Block Builder</span>

                @forelse ($blocks as $keyblock => $itemblocks)

                        <x-kompass::blocksgroup :itemblocks="$itemblocks" :keyblock="$keyblock" :fields="$fields" :page="$page" :class="'itemblock border-blue-400 shadow border-r-4 mt-5'" />   
                                       
                @empty
                    <div class="grid place-content-center border-2 border-dashed border-gray-300 rounded-2xl h-60 text-gray-400">
                        {{__('Click "Add" to create the layout')}}</div>
                @endforelse
                <div class="flex justify-end my-6">
                    <button wire:click="selectItem({{ $page->id }}, 'addBlock')">{{__('Add')}}</button>
                </div>


            </div>

        </div>
    </div>

    
        <div x-cloak x-data="{ open: @entangle('FormMedia'), ids: @js($getIdField) }" id="FormMedia">
            <x-kompass::offcanvas class="text-gray-500 p-4 m-4">
                <x-slot name="body">
                    @livewire('medialibrary', ['fieldId' => $getIdField])
                </x-slot>
            </x-kompass::offcanvas>
        </div>
   



    <div x-cloak x-data="{ open: @entangle('FormBlocks') }">
        <x-kompass::offcanvas class="grid grid-cols-4">
            <x-slot name="body">

                @foreach ($blocktemplates as $itemblock)
                    <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                        wire:click.defer="addBlock({{ $page['id'] }},{{ $itemblock['id'] }},'{{ $itemblock['name'] }}','{{ $itemblock['slug'] }}',{{ $itemblock['grid'] }})">
                        @if ($itemblock['icon_img_path'])
                            <img class=" w-full border-gray-200 border-solid border-2 rounded object-cover"
                                src="{{ asset('storage/' . $itemblock['icon_img_path']) }}" alt="">
                        @endif
                        <span class="text-xs block mt-2">{{ $itemblock['name'] }}</span>
                    </div>

                @endforeach

                <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                wire:click.defer="addBlock({{ $page['id'] }},'','Group','group','1','group')">
                    <img src="{{ kompass_asset('icons-blocks/group.png')}}" alt="">
                    <span class="text-xs block mt-2">Group</span>
                </div>
                <div class="bg-gray-300 rounded-lg p-2 m-2 cursor-pointer"
                wire:click.defer="addBlock({{ $page['id'] }},'','Gallery','gallery','1','gallery')">
                    <img class="rounded" src="{{ kompass_asset('icons-blocks/gallery.png')}}" alt="">
                    <span class="text-xs block mt-2">Gallery</span>
                </div>
            </x-slot>
        </x-kompass::offcanvas>
    </div>
</div>
