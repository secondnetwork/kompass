<div>

    <x-kompass::modal data="FormDelete" />

    <div x-cloak x-data="{ open: @entangle('FormBlocks') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">
                <div>

            <div>
             <x-kompass::form.input type="text" label="Iconclass Name" label="Iconclass" wire:model="data.iconclass" />
                 <p class="text-xs text-gray-400">{{__('Find class name at')}} <a class="text-blue-400" href="https://tabler-icons.io/" target="_blank">tabler-icons.io</a></p>
            </div>        
       
            
            <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6">
                Block Icon
                <input type="file" class="hidden" wire:model="filestoredata" x-ref="photo"
                    x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                " />


                <!-- New Profile Photo Preview -->
                <div class="mb-4 h-[10rem] aspect-[4/3] relative" x-show="photoPreview">
                    <span
                        class="block border-gray-200 border-solid border-2 rounded h-[10rem] aspect-[4/3] bg-cover bg-no-repeat bg-center"
                        x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>
            @if ($data->icon_img_path)
                        <div class="mb-4 h-[10rem] aspect-[4/3] relative" x-show="! photoPreview">
                                        <span class="absolute top-1 left-2 z-10 bg-white p-2 rounded-full"
                            wire:click="removemedia({{ $data->id }})">
                            <x-tabler-trash class="cursor-pointer stroke-current text-red-500 " />
                        </span>
                    <img class="border-gray-200 border-solid border-2 rounded object-cover"
                        src="{{ asset('storage/' . $data->icon_img_path) }}" alt="">
                </div>
            @else
            <img-block x-on:click.prevent="$refs.photo.click()" x-show="! photoPreview"
                class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 text-gray-400 rounded-2xl h-[10rem]  aspect-[4/3] ">
                <x-tabler-photo-plus class="h-[3rem] w-[3rem] stroke-[1.5]" />
            </img-block>

            @endif


                <x-kompass::input-error for="photo" class="mt-2" />
            </div>
</div>

<button class="flex btn gap-x-2   justify-center items-center"
wire:click="saveUpdate('{{ $blocktemplatesId }}')">
<x-tabler-device-floppy class="icon-lg" />{{ __('Save') }}
</button>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::action-message class="fixed bottom-8 right-8 alert text-white bg-gray-800 w-[35rem] p-8  flex"
        on="status">
        <div class=" mr-4 ">
            <x-tabler-circle-check class="stroke-green-500" />
        </div>
        <div>
            <h4 class="pb-4 text-white">{{ __('Saved.') }}</h4>
            <p class="text-md">{{__('successfully updated')}}</p>
        </div>
    </x-kompass::action-message>

    <div class="border-b border-gray-200  py-5 grid-3-2 items-center">
        <div>
                            <span class="text-gray-400 text-base">Block Titel</span>


                <div x-data="click_to_edit()">
                    <a @click.prevent @click="toggleEditingState" x-show="!isEditing" class="select-none cursor-pointer">
                        <h3>{{ $name }}</h3>
                    </a>

                    <input type="text" class="focus:outline-none focus:shadow-outline leading-normal"
                        wire:model="data.name" x-show="isEditing" @click.away="toggleEditingState"
                        @keydown.enter="disableEditing" @keydown.window.escape="disableEditing" x-ref="input">
                </div>
                <div class="col-span-6 text-md">
                    {{ $data->name }}
                </div>
        </div>
        <div class="flex justify-end items-center">
            <div class="flex justify-end gap-4">

        <button class="btn btn-primary"
            wire:click="saveUpdate('{{ $blocktemplatesId }}')">
            <x-tabler-device-floppy class="icon-lg" />{{ __('Save') }}
        </button>
            </div>
        </div>
    </div>

    <div class="py-8 ">


        <nav class="px-4 py-2 bg-gray-200 shadow-inner flex items-center gap-6">
            {{-- <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-yellow-900 text-yellow-300">Dev</span> --}}
@php
    $layout = $data->grid ?? '';
    $alignment = $data->set->alignment ?? '';
    $slider = $data->set->slider ?? '';
    $type = $data->set->type ?? '';
@endphp

        <nav-item class="flex items-center gap-2" wire:model="data.grid">
            <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Grid Layout</span>
                <span class="cursor-pointer" x-data wire:click="updateGrid({{ $data->id }}, '1')">
                    @if ($layout == '1')
                    <x-tabler-square-number-1 class="stroke-blue-500"/>
                    @else
                    <x-tabler-square-number-1/>
                    @endif
                </span>
                <span class="cursor-pointer" wire:click="updateGrid({{ $data->id }}, '2')">
                    @if ($layout == '2')
                    <x-tabler-square-number-2 class="stroke-blue-500"/>
                    @else
                    <x-tabler-square-number-2/>
                    @endif
                </span>
                <span class="cursor-pointer" wire:click="updateGrid({{ $data->id }}, '3')">
                    @if ($layout == '3')
                    <x-tabler-square-number-3 class="stroke-blue-500"/>
                    @else
                    <x-tabler-square-number-3/>
                    @endif
                </span>
                <span class="cursor-pointer" wire:click="updateGrid({{ $data->id }}, '4')">
                    @if ($layout == '4')
                    <x-tabler-square-number-4 class="stroke-blue-500"/>
                    @else
                    <x-tabler-square-number-4/>
                    @endif
                </span>
                <span class="cursor-pointer" wire:click="updateGrid({{ $data->id }}, '5')">
                    @if ($layout == '5')
                    <x-tabler-square-number-5 class="stroke-blue-500"/>
                    @else
                    <x-tabler-square-number-5/>
                    @endif
                </span>


            </nav-item>


        <nav-item class="flex items-center gap-2">
            <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Block Icon</span>
                <span class="cursor-pointer" wire:click="selectItem({{ $data->id }}, 'addblock')">

                    <x-tabler-photo-cog/>

                </span>


            </nav-item>
        </nav>



        <block-ltem wire:sortable="updateOrder" class="grid gap-4 my-4 p-2 border rounded-md border-dashed border-cyan-400  grid-cols-{{ $data->grid }}"
            wire:sortable.options="{ animation: 100, ghostClass: 'sort-ghost' , chosenClass: 'sort-chosen' ,dragClass: 'sort-drag', removeCloneOnHide: true }">
            
            {{-- @dump($fields->toArray()) --}}
            {{-- Loop through fields --}}
            {{-- Use wire:sortable.item to make the items sortable --}}
            {{-- Use wire:key to ensure each item has a unique key --}}

            @foreach ($fields as $field)
            <div wire:sortable.item="{{ $field->id }}" wire:key="field-item-{{ $field->id }}" class="col-span-1 md:col-span-{{ $field->grid ?? '1' }} ">
                 @livewire('field-editor', ['fieldId' => $field->id], key('field-editor-'.$field->id))
            </div>
        @endforeach
 
        </block-ltem>

        <div class="mt-4 flex justify-end gap-4">
        <button class="btn btn-primary"
            wire:click="addNewField('{{ $blocktemplatesId }}')">
            <x-tabler-square-plus class="icon-lg" />{{ __('Add') }}
        </button>
        <button class="btn btn-primary"
            wire:click="saveUpdate('{{ $blocktemplatesId }}')">
            <x-tabler-device-floppy class="icon-lg" />{{ __('Save') }}
        </button>
        </div>
{{-- Der globale Speicher-Button bleibt hier und triggert saveUpdate im Parent --}}
{{-- <div class="mt-4 flex justify-end">
    <button wire:click="saveUpdate" type="button" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
       Alle Änderungen speichern
   </button>
</div> --}}

        {{-- <x-kompass::form.input wire:model="fields.name" label="Name" type="text" />
        <x-kompass::form.input wire:model="fields.slug" label="Slug" type="text" />
        <x-kompass::form.input wire:model="fields.type" label="Type" type="text" /> --}}

    </div>


</div>
