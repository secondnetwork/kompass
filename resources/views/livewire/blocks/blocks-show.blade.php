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
                    {{ $data->slug }}
                </div>
        </div>
        <div class="flex justify-end items-center">
            <div class="flex justify-end gap-4">

        <button class="flex btn gap-x-2   justify-center items-center"
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
            @foreach ($this->fields as $key => $item)
                <div wire:sortable.item="{{ $item->id }}" wire:key="task-{{ $item->id }}"
                    class="col-span-{{ $item->grid }} ">
                    <div class="flex justify-between bg-slate-300 rounded-t-md p-2">
                        <div wire:sortable.handle>
                            <x-tabler-grip-horizontal class="cursor-move stroke-current  text-gray-900" />
                        </div>


                        <span wire:click="selectItem({{ $fields[$key]->id }}, 'delete')" class="flex justify-end">
                            <x-tabler-trash class="cursor-pointer stroke-current  text-red-500" />
                        </span>

                    </div>
                    <div class="p-4 flex flex-col gap-4 bg-blue-100 rounded-b-md" wire:key="field-{{ $item->id }}">
                        <x-kompass::form.input wire:model="fields.{{ $key }}.name" label="Feldbeschriftung" type="text" />
                        <div>
                            Feldname: <strong>{{ $fields[$key]->slug }}</strong>
                        </div>
           

                    <x-kompass::select label="Gird" :options="[
                        ['name' => '1', 'id' => '1'],
                        ['name' => '2', 'id' => '2'],
                        ['name' => '3', 'id' => '3'],
                        ['name' => '4', 'id' => '4'],


                    ]" option-label="name"
                        option-value="id" wire:model="fields.{{ $key }}.grid" />



                        {{-- <x-kompass::form.input wire:model="fields.{{ $key }}.slug" label="Feldname" type="text" disabled />
            {{$fields[$key]->slug}} --}}


                        {{-- wire:model="fields.{{ $key }}.slug" --}}
                        {{-- <x-kompass::form.input wire:model="fields.{{ $key }}.type" label="Feldtyp" type="text" /> --}}
                        <select class="form-select block w-full pl-3 pr-10 py-2 text-base
                rounded-md border bg-white focus:ring-1 focus:outline-none
                border-secondary-300 focus:ring-primary-500 focus:border-primary-500" wire:model="fields.{{ $key }}.type" label="Feldtyp"
                            data-placeholder="{{__('Select')}}">
                            <option value="">{{__('Select')}}</option>
                            <optgroup label="{{__('Basis')}}">
                                <option value="text">Text einzeilig</option>
                                {{-- <option value="textarea">Text mehrzeilig</option> --}}
                                {{-- <option value="number">Numerisch</option>
                <option value="range">Numerischer Bereich</option>
                <option value="email">E-Mail</option>
                <option value="url">URL</option>
                <option value="password">Passwort</option> --}}
                            </optgroup>
                            <optgroup label="{{__('Contents')}}">
                                <option value="image">{{__('Image')}}</option>
                                {{-- <option value="file">Datei</option> --}}
                                <option value="wysiwyg">WYSIWYG-Editor</option>
                                <option value="oembed">oEmbed</option>
                                {{-- <option value="gallery">Galerie</option> --}}
                            </optgroup>
                            <optgroup label="{{__('Selection')}}">
                                {{-- <option value="select">Auswahl</option>
                <option value="checkbox">Checkbox</option>
                <option value="radio">Radio-Button</option> --}}
                                {{-- <option value="button_group">Button-Gruppe</option> --}}
                                <option value="true_false">{{__('True / False')}}</option>
                            </optgroup>
                            {{-- <optgroup label="Relational">
                <option value="link">Link</option>
                <option value="post_object">Beitrags-Objekt</option>
                <option value="page_link">Seiten-Link</option>
                <option value="relationship">Beziehung</option>
            </optgroup> --}}
                            {{-- <optgroup label="jQuery">
                <option value="google_map">Google Maps</option>
                <option value="date_picker">Datumsauswahl</option>
                <option value="date_time_picker">Datums- und Zeitauswahl</option>
                <option value="time_picker">Zeitauswahl</option>
                <option value="color_picker">Farbauswahl</option>
            </optgroup> --}}
                            {{-- <optgroup label="Layout">
                <option value="message">Mitteilung</option>
                <option value="accordion">Akkordeon</option>
                <option value="tab">Tab</option>
                <option value="group">Gruppe</option>
                <option value="repeater">Wiederholung</option>
                <option value="flexible_content">Flexible Inhalte</option>
                <option value="clone">Klon</option>
            </optgroup> --}}
                        </select>
                    </div>
                    {{-- draggable="true"
           <input type="hidden" wire:model="fields.{{ $key }}.id" value="{{ $key }}">
           <input type="text" wire:model="fields.{{ $key }}.name" />
           <input type="text" wire:model="fields.{{ $key }}.slug" /> --}}

                </div>
            @endforeach
        </block-ltem>
        <button class="flex btn gap-x-2   justify-center items-center"
            wire:click="addNewField('{{ $blocktemplatesId }}')">
            <x-tabler-square-plus class="icon-lg" />{{ __('Add') }}
        </button>

        {{-- <x-kompass::form.input wire:model="fields.name" label="Name" type="text" />
        <x-kompass::form.input wire:model="fields.slug" label="Slug" type="text" />
        <x-kompass::form.input wire:model="fields.type" label="Type" type="text" /> --}}

    </div>



</div>
