@props([
    'name' => '',
    'icons' => '',
    'label' => '',
    'value' => '',
    'options' => [],
    'placeholder' => '',
])


    @php
        //remove underscores from name
    $name = $attributes->wire('model')->value();
    if ($label === '') {
        $label = str_replace('_', ' ', $name);
        $label = preg_replace('/(?=[A-Z])/', ' $0', $label);
        $label = ucwords(strtolower(trim($label)));
    }

        $icons = collect($options)
        ->filter(fn($option) => !empty($option['icon'])) // Nur Optionen mit einem 'icon'-Schlüssel
        ->pluck('icon')
        ->unique()
        ->mapWithKeys(function ($iconName) {
            // Nutzt den blade-ui-kit helper, um das SVG zu rendern
            return [$iconName => svg($iconName, 'size-5')->toHtml()];
        })
        ->all();
    @endphp


    <div x-data="{
        options: @js($options),
        filteredOptions: @js($options),
        icons: @js($icons),
        isOpen: false,
        openedWithKeyboard: false,
        searchQuery: '',
        selectedOption: @entangle($attributes->wire('model')),

         get selectedItem() {
            return this.options.find(opt => opt.id == this.selectedOption) || null;
        },

        setSelectedOption(item) {
            this.selectedOption = item.id;
            this.isOpen = false;
            this.openedWithKeyboard = false;
            this.searchQuery = '';
        },

        filterOptions() {
            if (!this.searchQuery) {
                this.filteredOptions = this.options;
            } else {
                this.filteredOptions = this.options.filter(opt =>
                    opt.name.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            }
        },

        highlightFirstMatchingOption(pressedKey) {
            const option = this.filteredOptions.find((item) =>
                item.name.toLowerCase().startsWith(pressedKey.toLowerCase()),
            )
            if (option) {
                const index = this.filteredOptions.indexOf(option)
                const allOptions = document.querySelectorAll('.combobox-option')
                if (allOptions[index]) {
                    allOptions[index].focus()
                }
            }
        },

        handleSearchKeydown(event) {
            const key = event.key;

            if (key === 'ArrowDown') {
                event.preventDefault();
                this.$nextTick(() => {
                    const firstOption = document.querySelector('.combobox-option');
                    if (firstOption) firstOption.focus();
                });
            }
        },

        init() {
            this.$watch('searchQuery', () => this.filterOptions());
        }
    }" class="w-full flex flex-col gap-1" x-on:keydown.esc.window="isOpen = false; openedWithKeyboard = false">
    <label class="w-fit pl-0.5 text-sm text-slate-700 ">{{ $label }}</label>
   <div class="relative">
        {{-- Trigger Button --}}
        <button
            type="button"
            role="combobox"
            class="inline-flex w-full items-center justify-between gap-2 whitespace-nowrap rounded-md border-2 bg-white h-10 px-4 py-2 text-sm font-medium tracking-wide text-slate-700 transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700"
            :class="{ 'border-blue-600': isOpen, 'border-slate-300': !isOpen }"
            aria-haspopup="listbox"
            aria-controls="industriesList"
            x-on:click="isOpen = !isOpen"
            x-on:keydown.down.prevent="openedWithKeyboard = true; $nextTick(() => { $focus.first() })"
            x-on:keydown.enter.prevent="openedWithKeyboard = true"
            x-on:keydown.space.prevent="openedWithKeyboard = true"
            :aria-label="selectedItem ? selectedItem.name : '{{ $placeholder }}'"
            :aria-expanded="isOpen || openedWithKeyboard">

            <div class="flex items-center gap-2">
                {{-- OPTIMIERTE ANZEIGE DES GEWÄHLTEN ITEMS --}}
                <template x-if="selectedItem && selectedItem.icon && icons[selectedItem.icon]">
                     <div x-html="icons[selectedItem.icon]"></div>
                </template>

                <span x-show="selectedItem" x-text="selectedItem ? (selectedItem.display_name || selectedItem.name) : ''" class="block truncate"></span>
                <span x-show="!selectedItem" class="block truncate text-slate-500">{{ $placeholder }}</span>
            </div>

            <x-tabler-selector class="size-5 text-slate-500" />
        </button>

        {{-- Dropdown Liste mit Suche --}}
        <ul
            x-cloak x-show="isOpen || openedWithKeyboard"
            id="industriesList"
            class="absolute z-10 left-0 top-11 max-h-80 w-full flex-col rounded-md border-2 border-slate-300 bg-white shadow-lg"
            role="listbox"
            aria-label="{{ $label }} list"
            x-on:click.outside="isOpen = false; openedWithKeyboard = false"
            x-transition
            x-trap.noscroll="openedWithKeyboard">

            {{-- Suchfeld --}}
            <div class="sticky top-0 bg-white border-b border-slate-200 p-2">
                <div class="relative">
                    <x-tabler-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-slate-400" />
                    <input
                        type="text"
                        x-model="searchQuery"
                        x-on:keydown="handleSearchKeydown($event)"
                        placeholder="{{ __('Search...') }}"
                        class="w-full pl-9 pr-3 py-2 text-sm border border-slate-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    />
                </div>
            </div>

            {{-- Optionen Liste --}}
            <div class="overflow-y-auto py-1.5 max-h-60">
                <template x-if="filteredOptions.length === 0">
                    <div class="px-4 py-3 text-sm text-slate-500 text-center">
                        {{ __('No results found') }}
                    </div>
                </template>

                <template x-for="item in filteredOptions" :key="item.id">
                    <li
                        class="combobox-option group inline-flex w-full cursor-pointer items-center justify-between gap-6 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 focus-visible:bg-slate-100 focus-visible:text-black focus-visible:outline-none"
                        role="option"
                        :class="{ 'bg-slate-100': selectedOption == item.id }"
                        :aria-selected="selectedOption == item.id"
                        x-on:click="setSelectedOption(item)"
                        x-on:keydown.enter.prevent="setSelectedOption(item)"
                        :id="'option-' + item.id"
                        tabindex="-1">

                        {{-- Label und Icon --}}
                        <div class="flex items-center gap-2">
                            <template x-if="item.icon && icons[item.icon]">
                                <div x-html="icons[item.icon]"></div>
                            </template>

                            <span :class="{ 'font-semibold': selectedOption == item.id }" x-text="item.name"></span>
                        </div>

                        {{-- Checkmark --}}
                        <span x-show="selectedOption == item.id">
                            <x-tabler-check class="size-5 text-blue-600" />
                        </span>
                    </li>
                </template>
            </div>
        </ul>
    </div>
    @error($attributes->wire('model')->value())
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    </div>
    


