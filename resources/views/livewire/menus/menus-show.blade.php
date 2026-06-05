<div>
    <div class="flex items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3 min-w-0">
            <a wire:navigate href="/admin/menus"
                class="flex items-center justify-center size-9 rounded-lg border border-base-300 text-base-content/60 hover:bg-base-200 transition-colors shrink-0"
                title="{{ __('Back') }}">
                <x-tabler-arrow-left class="size-5" />
            </a>

            <div x-data="click_to_edit('renameMenu')" class="min-w-0">
                <a @click.prevent @click="toggleEditingState" x-show="!isEditing"
                    class="flex items-center gap-2 select-none cursor-pointer group min-w-0">
                    <h4 class="text-lg font-bold text-base-content truncate">{{ $menu->name }}</h4>
                    <x-tabler-pencil class="shrink-0 size-4 text-base-content/0 group-hover:text-base-content/40 transition-colors" />
                </a>
                <div x-show="isEditing" class="flex items-center" x-cloak>
                    <x-kompass::form.input type="text" wire:model.live="menuName" x-ref="input"
                        class="text-lg font-bold border-0 border-b-2 border-primary focus:ring-0 px-0 py-0 bg-transparent w-auto"
                        @keydown.enter="disableEditing"
                        @keydown.window.escape="disableEditing"
                        @click.away="handleClickAway" />
                </div>
            </div>

            @if (setting('global.multilingual') && $menu->land)
                <span class="badge badge-sm border-blue-200 bg-blue-100 text-blue-800">
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    {{ strtoupper($menu->land) }}
                </span>
            @endif
        </div>
        <div class="flex gap-2">
            <button class="btn btn-primary" wire:click="selectItem({{ $menu->id }}, 'additem')">
                <x-tabler-text-plus stroke-width="1.5" />{{ __('Add Menu') }}
            </button>
            <button class="btn btn-primary" wire:click="$set('FormAdjustments', true)">
                <x-tabler-adjustments class="icon-lg" />
            </button>
        </div>
    </div>

    <div x-cloak x-data="{ open: @entangle('FormAdjustments') }">
        <x-kompass::offcanvas :w="'w-1/3'">
            <x-slot name="body">
                <h3 class="text-lg font-bold mb-4">{{ __('Menu Settings') }}</h3>

                <x-kompass::form.input type="text" label="{{ __('Name') }}" wire:model="menuName" />

                @if (setting('global.multilingual'))
                <div class="mt-4">
                    <x-kompass::select wire:model="menuLand" :searchable="false" label="{{ __('Language') }}" :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])">
                    </x-kompass::select>
                </div>
                @endif

                <button wire:click="updateMenu" class="btn btn-primary mt-6">
                    <x-tabler-device-floppy class="icon-lg" />
                    {{ __('Save Settings') }}
                </button>
            </x-slot>
        </x-kompass::offcanvas>
    </div>
    
    <x-kompass::action-message class="" on="status" />

    <x-kompass::modal data="FormDelete" />

    <div x-cloak x-data="{ open: @entangle('FormEdit') }">
        <x-kompass::offcanvas :w="'w-2/4'">
            <x-slot name="body">

                <h3 class="text-lg font-bold mb-2">{{ __('Menu Item') }}</h3>

                <x-kompass::form.input type="text" label="{{ __('Title') }}" wire:model="title" />

                <div>
                    <x-kompass::select wire:model.live="page_id" :searchable="true" label="{{ __('Page') }}"
                        placeholder="{{ __('Select a page') }}" :options="$pages" />
                    <p class="text-xs text-gray-500 mt-1">{{ __('Select a page to auto-fill the URL') }}</p>
                </div>

                <x-kompass::form.input type="text" label="{{ __('URL') }}" wire:model="url" />
                <div class="mt-4">
                    <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('Icon') }}</label>
                    <x-kompass::form.input type="text" name="iconSearch" wire:model.live="iconSearch" placeholder="{{ __('Search icon...') }}" />
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
                            <div class="grid grid-cols-8 gap-1 p-2">
                                @foreach ($filteredIcons as $iconItem)
                                    <button wire:click="selectIcon('{{ $iconItem['name'] }}')"
                                        class="p-2 hover:bg-base-200 rounded flex justify-center transition-colors">
                                        <x-kompass::icon :name="$iconItem['name']" class="size-5 md:size-6" />
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-8">
                            @if ($iconSearch)
                                {{ __('No icons found for: :search', ['search' => $iconSearch]) }}
                            @else
                                {{ __('No icons available') }}
                            @endif
                        </p>
                    @endif
                </div>




                <input type="hidden" wire:model="iconclass" />

                <div>
                    <x-kompass::select wire:model="target" :searchable="false" label="{{ __('Open target') }}" :options="[
                        ['name' => __('Same tab'), 'id' => '_self'],
                        ['name' => __('New tab'), 'id' => '_blank'],
                    ]">
                    </x-kompass::select>
                </div>


                <button wire:click="addNew" class="btn btn-primary">
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
        </x-kompass::offcanvas>
    </div>


    <div wire:sort="handleSort" wire:sort:group="menuitems" wire:sort:group-id="">


        @forelse ($menuitem as $key => $item)
            <div wire:sort:item="{{ $item->id }}">
                <x-kompass::menugroup :item="$item" :fields="$menuitem" :key="$key" />
            </div>

        @empty
            <div class="flex flex-col items-center justify-center gap-3 border-2 border-dashed border-base-300 rounded-2xl py-16 text-base-content/50">
                <x-tabler-layout-navbar stroke-width="1.5" class="size-12 text-base-content/30" />
                <p class="text-sm">{{ __('Click "Add Menu" to create a new link') }}</p>
            </div>
        @endforelse

    </div>


</div>
