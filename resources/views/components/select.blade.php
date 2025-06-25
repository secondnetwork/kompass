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
        icons: @js($icons),
        isOpen: false,
        openedWithKeyboard: false,
        selectedOption: @entangle($attributes->wire('model')),

         get selectedItem() {
            return this.options.find(opt => opt.id == this.selectedOption) || null;
        },

        setSelectedOption(item) {
            this.selectedOption = item.id; 
            this.isOpen = false;
            this.openedWithKeyboard = false;
        },

        highlightFirstMatchingOption(pressedKey) {
            const option = this.options.find((item) =>
                item.name.toLowerCase().startsWith(pressedKey.toLowerCase()),
            )
            if (option) {
                const index = this.options.indexOf(option)
                const allOptions = document.querySelectorAll('.combobox-option')
                if (allOptions[index]) {
                    allOptions[index].focus()
                }
            }
        },
    }" class="w-full flex flex-col gap-1" x-on:keydown="highlightFirstMatchingOption($event.key)" x-on:keydown.esc.window="isOpen = false, openedWithKeyboard = false">
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

        {{-- Hidden Input ist nicht mehr nötig, da @entangle die Synchronisierung mit Livewire übernimmt --}}
        
        {{-- Dropdown Liste --}}
        <ul 
            x-cloak x-show="isOpen || openedWithKeyboard" 
            id="industriesList" 
            class="absolute z-10 left-0 top-11 max-h-60 w-full flex-col overflow-y-auto rounded-md border-2 border-slate-300 bg-white py-1.5 shadow-lg" 
            role="listbox" 
            aria-label="{{ $label }} list" 
            x-on:click.outside="isOpen = false; openedWithKeyboard = false" 
            x-on:keydown.down.prevent="$focus.wrap().next()" 
            x-on:keydown.up.prevent="$focus.wrap().previous()" 
            x-transition 
            x-trap.noscroll="openedWithKeyboard">
            
            <template x-for="item in options" :key="item.id">
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
                        {{-- TEIL 2: DYNAMISCHES ICON RENDERING MIT x-html --}}
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
        </ul>
    </div>
    </div>
    


