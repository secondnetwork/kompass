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
                    <strong class="text-gray-600">{{ __('Last update') }}:</strong> {{ $page->updated_at }}</br>

                    @if (setting('global.multilingual'))
                    <x-kompass::select wire:model="land" label="{{ __('Language') }}" :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])">
                    </x-kompass::select>
                    @endif

                    <x-kompass::select wire:model="status" label="{{ __('Status') }}" placeholder="{{ __('Select a status') }}" :options="[
                        ['name' => __('published'), 'id' => 'published'],
                        ['name' => __('draft'), 'id' => 'draft'],
                    ]">
                    </x-kompass::select>

                </div>

                    <x-kompass::select wire:model="layout" label="{{ __('Page Template') }}" :options="[
                        ['name' => __('Page'), 'id' => 'page'],
                        ['name' => __('Front Page'), 'id' => 'is_front_page'],
                    ]"  />

                <strong class="text-gray-600">SEO:</strong>
                <x-kompass::form.textarea wire:model="description" id="name" name="title" label="{{ __('Description') }}" type="text" class="block w-full h-[10rem]" />
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div class=" grid-3-2 gap-y-0! items-center">

        <div class="relative flex items-center">

            <div class=" flex-auto">


                <div x-data="click_to_edit('updateTitle')">
                    <a 
                    @click.prevent @click="toggleEditingState" x-show="!isEditing" 
                    class="flex items-center gap-2 select-none cursor-pointer"
                        class="select-none cursor-pointer">
                        @if ($page->layout == 'is_front_page')
                            <x-tabler-home class="w-5 h-5 text-amber-500" />
                        @endif
                        <h4 class="text-gray-600 font-bold">{{ $title }} </h4><span>
                            <x-tabler-edit class="cursor-pointer stroke-current  text-gray-400 hover:text-blue-500" />
                        </span>
                    </a>

                    <div x-show="isEditing" x-cloak>
                        <x-kompass::form.input type="text" wire:model.live="title" x-ref="input"
                            class="font-bold border-0 border-b-2 border-blue-500 focus:ring-0 px-0 py-0 bg-transparent text-gray-600 w-auto"
                            @click.away="handleClickAway"
                            @keydown.enter="disableEditing" @keydown.window.escape="disableEditing" />
                    </div>
                </div>
                <div class="col-span-6">

                </div>

            </div>


        </div>

                      
        <div class="flex gap-4 justify-end items-center">

            <span x-data="{ open: false }" class="relative transition-all flex gap-4 items-center">

                @if (setting('global.multilingual') && $page->land)
                    <span class="badge badge-sm border-blue-200 bg-blue-100 text-blue-800">
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        {{ strtoupper($page->land) }}
                    </span>
                @endif

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
        <div>
      @php
                    $defaultLocale = config('app.locale', 'de');
                    $langPrefix = ($land == $defaultLocale) ? '' : '/' . $land;
                    $permalink = ($layout == 'is_front_page') ? url($langPrefix ?: '/') : url($langPrefix . '/' . $page->slug);
                @endphp
                <strong class="text-gray-400 text-xs">Permalink: </strong>
                <a class="text-gray-400 hover:text-blue-500 text-xs mt-4" href="{{ $permalink }}"
                    target="_blank" rel="noopener noreferrer">{{ $permalink }}</a>

        </div>


    </div>
    <div class="divider"></div>
    <div class="ordre-1">

        <div wire:sort="handleSort">

            @forelse ($blocks as $itemblocks)
                <div wire:sort:item="{{ $itemblocks->id }}">
                    <x-kompass::blocksgroup :itemblocks="$itemblocks" :fields="$itemblocks->datafield" :class="'itemblock border-blue-400 shadow border-r-4 mt-3'" />
                </div>

            @empty
                <div
                    class="grid place-content-center border-2 border-dashed border-gray-300 rounded-2xl h-60 text-gray-400">
                    {{ __('Click "Add" to create the layout') }}
                </div>
            @endforelse



        </div>
            <div class="flex  justify-end my-6">
                <button class="btn btn-primary"
                    wire:click="selectitem('addBlock',{{ $page->id }})">{{ __('Add') }}</button>
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

    <x-kompass::icon-picker />

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
{{-- 
                    <div class="border-blue-600 border-2 rounded-lg p-2 m-2 cursor-pointer"
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
                        @elseif($itemblock['iconclass'])
                            <div class="flex items-center justify-center h-16">
                                @svg(str_starts_with($itemblock['iconclass'], 'tabler-') ? $itemblock['iconclass'] : 'tabler-' . $itemblock['iconclass'], 'w-10 h-10 text-blue-600')
                            </div>
                        @else
                            <img class="rounded" src="{{ kompass_asset('icons-blocks/contact.png') }}" alt="">
                        @endif
                        <span class="text-xs block mt-2">{{ $itemblock['name'] }}</span>
                    </div>

      
                @endforeach
                </div>
            </x-slot>
        </x-kompass::offcanvas>
</div>
