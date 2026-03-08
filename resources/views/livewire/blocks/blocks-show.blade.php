<div>

    <x-kompass::modal data="FormDelete" />

    <div x-cloak x-data="{ open: @entangle('FormBlocks') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">
                <div>

                    <x-kompass::form.input type="text" name="iconSearch" wire:model.live="iconSearch"
                        placeholder="{{ __('Search icon...') }}" />
                    @if ($selectedIcon)
                        <div class="flex items-center gap-2 mt-2 p-2 bg-base-200 rounded">
                            <x-icon :name="$selectedIcon" class="w-5 h-5" />
                            <span class="text-sm flex-1">{{ $selectedIcon }}</span>
                            <button wire:click="resetIcon" class="btn btn-ghost btn-xs text-error">
                                <x-tabler-x class="w-4 h-4" />
                            </button>
                        </div>
                    @endif

                    @if (count($filteredIcons) > 0)
                        <div class="mt-2 max-h-40 overflow-y-auto border border-base-300 rounded bg-base-100">
                            <div class="grid grid-cols-5 gap-1 p-2">
                                @foreach ($filteredIcons as $iconItem)
                                    <button wire:click="selectIcon('{{ $iconItem['name'] }}')"
                                        class="p-2 hover:bg-base-200 rounded flex justify-center transition-colors">
                                        <x-kompass::icon :name="$iconItem['name']" class="w-6 h-6" />
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-4">
                            @if ($iconSearch)
                                {{ __('No icons found for: :search', ['search' => $iconSearch]) }}
                            @else
                                {{ __('No icons available') }}
                            @endif
                        </p>
                    @endif
                    <input type="hidden" wire:model="iconclass" />
                </div>



                <div x-data="{ photoName: null, photoPreview: null }" class="mt-4">
                    <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('Block Icon') }}</label>
                    <input type="file" class="hidden" wire:model="filestoredata" x-ref="photo"
                        x-on:change="
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                            };
                            reader.readAsDataURL($refs.photo.files[0]);
                        " />

                    <div class="mb-4 h-[10rem] aspect-[4/3] relative" x-show="photoPreview">
                        <span
                            class="block border-gray-200 border-solid border-2 rounded h-[10rem] aspect-[4/3] bg-cover bg-no-repeat bg-center"
                            x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>

                    @if ($icon_img_path)
                        <div class="mb-4 h-[10rem] aspect-[4/3] relative" x-show="! photoPreview">
                            <span class="absolute top-1 left-2 z-10 bg-white p-2 rounded-full cursor-pointer"
                                wire:click="removemedia({{ $blocktemplatesId }})">
                                <x-tabler-trash class="cursor-pointer stroke-current text-red-500" />
                            </span>
                            <img class="border-gray-200 border-solid border-2 rounded object-cover"
                                src="{{ asset('storage/' . $icon_img_path) }}" alt="">
                        </div>
                    @else
                        <div x-on:click.prevent="$refs.photo.click()" x-show="! photoPreview"
                            class="cursor-pointer grid place-content-center border-2 border-dashed border-gray-400 text-gray-400 rounded-2xl h-[10rem] aspect-[4/3]">
                            <x-tabler-photo-plus class="h-[3rem] w-[3rem] stroke-[1.5]" />
                        </div>
                    @endif
                </div>

                <button class="flex btn btn-primary gap-x-2 justify-center items-center mt-4"
                    wire:click="saveUpdate('{{ $blocktemplatesId }}')">
                    <x-tabler-device-floppy class="icon-lg" />{{ __('Save') }}
                </button>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::action-message class="" on="status" />


    <div class="border-b border-gray-200 py-5 grid-3-2 items-center">
        <div>
            <span class="text-gray-400 text-base">{{ __('Block Title') }}</span>

            <div x-data="click_to_edit()">
                <a @click.prevent @click="toggleEditingState" x-show="!isEditing" class="select-none cursor-pointer">
                    <h3>{{ $name }}</h3>
                </a>

                <x-kompass::input type="text" wire:model="name" x-show="isEditing" @click.away="toggleEditingState"
                    class="focus:outline-none focus:shadow-outline leading-normal" />
            </div>
        </div>
        <div class="flex justify-end items-center">
            <button class="btn btn-primary" wire:click="saveUpdate('{{ $blocktemplatesId }}')">
                <x-tabler-device-floppy class="icon-lg" />{{ __('Save') }}
            </button>
        </div>
    </div>

    <div class="py-2">
        <nav class=" flex items-center gap-6">
            <div class="flex items-center gap-2">


                <x-kompass::input type="text" wire:model.live="type" label="{{ __('Block Type') }}"
                    placeholder="Type" class=" " />

                <div class="w-36">
                    <x-kompass::select :searchable="false" wire:model.live="grid" label="Grid" :options="collect(range(1, 5))->map(fn($i) => ['name' => (string) $i, 'id' => (string) $i])" />
                </div>

                <div class="w-full">
                    <div class="block text-sm font-medium leading-6 text-gray-900">{{ __('Block Icon') }}</div>
                    <div class="cursor-pointer btn btn-md btn-primary py-2" wire:click="selectItem({{ $blocktemplatesId }}, 'addblock')">
                        @if ($iconclass)
                            <x-icon :name="$iconclass" class="w-6 h-6" />
                        @else
                            <x-tabler-photo-cog />
                        @endif
                    </div>
                </div>

            </div>
        </nav>

        <block-ltem wire:sort="handleSort"
            class="grid gap-4 my-4 p-2 border rounded-md border-dashed border-cyan-400 grid-cols-{{ $grid ?? '1' }}"
            wire:sort.options="{ animation: 100, ghostClass: 'sort-ghost', chosenClass: 'sort-chosen', dragClass: 'sort-drag', removeCloneOnHide: true }">

            @foreach ($fields as $field)
                <div wire:sort:item="{{ $field->id }}" wire:key="field-item-{{ $field->id }}"
                    class="col-span-1 md:col-span-{{ $field->grid ?? '1' }}">
                    @livewire('field-editor', ['fieldId' => $field->id], key('field-editor-' . $field->id))
                </div>
            @endforeach

        </block-ltem>

        <div class="mt-4 flex justify-end gap-4">
            <button class="btn btn-primary" wire:click="addNewField('{{ $blocktemplatesId }}')">
                <x-tabler-square-plus class="icon-lg" />{{ __('Add') }}
            </button>
            <button class="btn btn-primary" wire:click="saveUpdate('{{ $blocktemplatesId }}')">
                <x-tabler-device-floppy class="icon-lg" />{{ __('Save') }}
            </button>
        </div>
    </div>

</div>
