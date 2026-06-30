<div>

    <div x-data="{ open: @entangle('FormAdjustments') }">
        <x-kompass::offcanvas :w="'w-1/3'" class="p-8 grid gap-4">
            <x-slot name="button">
                <button class="btn btn-primary" wire:click="update('{{ $page->id }}')">
                    <div wire:loading>
                        <svg class="animate-spin h-5 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
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
                        <x-kompass::select wire:model="land" :searchable="false" label="{{ __('Language') }}"
                            :options="collect($available_locales)->map(
                                fn($l) => ['name' => strtoupper($l), 'id' => $l],
                            )">
                        </x-kompass::select>
                    @endif

                    <x-kompass::select wire:model="status" :searchable="false" label="{{ __('Status') }}"
                        placeholder="{{ __('Select a status') }}" :options="[
                            ['name' => __('published'), 'id' => 'published'],
                            ['name' => __('draft'), 'id' => 'draft'],
                        ]">
                    </x-kompass::select>

                </div>

                <x-kompass::select wire:model="layout" label="{{ __('Page Template') }}" :options="[
                    ['name' => __('Page'), 'id' => 'page'],
                    ['name' => __('Front Page'), 'id' => 'is_front_page'],
                ]" />

                <strong class="text-gray-600">SEO:</strong>
                <x-kompass::form.textarea wire:model="description" id="name" name="title"
                    label="{{ __('Description') }}" type="text" class="block w-full h-[10rem]" />
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div class=" grid-3-2 gap-y-0! items-center">

        <div class="relative flex items-center">

            <div class=" flex-auto">


                <div x-data="click_to_edit('updateTitle')">
                    <a @click.prevent @click="toggleEditingState" x-show="!isEditing"
                        class="flex items-center gap-2 select-none cursor-pointer" class="select-none cursor-pointer">
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
                            @click.away="handleClickAway" @keydown.enter="disableEditing"
                            @keydown.window.escape="disableEditing" />
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
                        <span class="flex gap-x-2 justify-end items-center text-md  text-base-content">

                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-[ping_3s_ease-in-out_infinite] absolute inline-flex h-full w-full rounded-full bg-teal-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                            </span>
                            {{ __('Live') }}
                        </span>
                    @break

                    @case('password')
                        <span class="flex gap-x-2 justify-end items-center text-md  text-base-content">
                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-[ping_3s_ease-in-out_infinite] absolute inline-flex h-full w-full rounded-full bg-purple-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-purple-500"></span>
                            </span>
                            {{ __('Password') }}
                        </span>
                    @break

                    @default
                        <span class="flex gap-x-2 justify-end items-center text-md border-gray-300 text-base-content mx-2">

                            <span class="relative flex h-3 w-3">

                                <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-500"></span>
                            </span>
                            {{ __('draft') }}
                        </span>
                @endswitch


                <button class="btn btn-primary" wire:click="update('{{ $page->id }}')">
                    <div wire:loading>
                        <svg class="animate-spin h-5 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <x-tabler-device-floppy class="icon-lg" wire:loading.remove />
                    {{ __('Save') }}
                </button>


                <button x-data="{ open: @entangle('FormAdjustments') }" class="btn btn-primary" @click="open = true">
                    <x-tabler-adjustments class="icon-lg" />

                </button>

            </span>

        </div>
        <div>
            @php
                $defaultLocale = config('app.locale', 'de');
                $langPrefix = empty($land) || $land == $defaultLocale ? '' : '/' . $land;
                $permalink =
                    $layout == 'is_front_page' ? url($langPrefix ?: '/') : url($langPrefix . '/' . $page->slug);
            @endphp
            <strong class="text-gray-400 text-xs">{{ __('Permalink') }}: </strong>
            <a class="text-gray-400 hover:text-blue-500 text-xs mt-4" href="{{ $permalink }}" target="_blank"
                rel="noopener noreferrer">{{ $permalink }}</a>

        </div>


    </div>
    <div class="divider"></div>
    <div class="ordre-1" x-data="{ dragging: false, allExpanded: false }"
        @dragstart.window="dragging = true"
        @dragend.window="dragging = false"
        @drop.window="dragging = false">

        <div wire:sort="handleSort" wire:sort:group="blocks" wire:sort:group-id="">

            @forelse ($blocks as $itemblocks)
                <div wire:sort:item="{{ $itemblocks->id }}">
                    <x-kompass::blocksgroup :itemblocks="$itemblocks" :fields="$itemblocks->datafield" :class="'itemblock border border-base-300 rounded-md shadow-sm mt-3'" />
                </div>

            @empty
                <div
                    class="grid place-content-center border-2 border-dashed border-gray-300 rounded-2xl h-60 text-gray-400">
                    {{ __('Click "Add" to create the layout') }}
                </div>
            @endforelse



        </div>
        <div class="flex justify-end items-center gap-3 my-6">
            @if (count($blocks))
                <button type="button"
                    @click="allExpanded = !allExpanded; $dispatch(allExpanded ? 'expand-all-blocks' : 'collapse-all-blocks')"
                    class="btn btn-outline gap-1.5 border-base-300 text-base-content/70 hover:bg-base-200 hover:border-primary hover:text-base-content">
                    <span x-show="!allExpanded" class="flex items-center gap-1.5">
                        <x-tabler-chevrons-down class="size-4" /> {{ __('Expand all') }}
                    </span>
                    <span x-show="allExpanded" x-cloak class="flex items-center gap-1.5">
                        <x-tabler-chevrons-up class="size-4" /> {{ __('Collapse all') }}
                    </span>
                </button>
            @endif
            <button class="btn btn-primary"
                wire:click="selectitem('addBlock',{{ $page->id }})">
                <x-tabler-category-plus class="size-5" />
                {{ __('Add') }}
            </button>
        </div>
    </div>

    <div class="relative z-50" x-cloak x-data="{ open: @entangle('FormMedia') }" id="FormMedia">
        <x-kompass::offcanvas class="text-base-content/70 p-4 m-4">
            <x-slot name="body">
                @livewire('medialibrary', ['fieldId' => $getId])
            </x-slot>
        </x-kompass::offcanvas>
    </div>
    <div class="relative z-40" x-cloak x-data="{ open: @entangle('FormEditBlock') }">
        <x-kompass::offcanvas :w="'w-3/4'">
            <x-slot name="body">

                @foreach ($datafield as $itemblocks)
                    <x-kompass::blocks-datafield :itemblocks="$itemblocks" :fields="$itemblocks->datafield" :cssclassname="$cssClassname"
                        :relationship-search="$relationshipSearch"
                        :class="'itemblock border-blue-400 shadow border-r-4 mt-3'" />
                @endforeach

                <div>
                    <button class="btn btn-primary" wire:click="update('{{ $page->id }}')">
                        <div wire:loading>
                            <svg class="animate-spin h-5 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
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
                    @foreach (block_registry()->palette() as $entry)
                        <x-kompass::blocks.palette-tile :entry="$entry" />
                    @endforeach
                </div>
            </x-slot>
        </x-kompass::offcanvas>
    </div>
