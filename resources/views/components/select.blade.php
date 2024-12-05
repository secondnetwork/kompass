@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'options' => [],
    'placeholder' => '',
])

@if ($label === '')
    @php
        //remove underscores from name
        $label = str_replace('_', ' ', $name);
        //detect subsequent letters starting with a capital 
        $label = preg_split('/(?=[A-Z])/', $label);
        //display capital words with a space
        $label = implode(' ', $label);
        //uppercase first letter and lower the rest of a word
        $label = ucwords(strtolower($label));
    @endphp
@endif

    <div x-data="{
        options: @js($options),
        isOpen: false,
        openedWithKeyboard: false,
        selectedOption: @entangle($attributes->wire('model')),

        setSelectedOption(option) {
            this.selectedOption = option
            this.selected = option.id
            this.isOpen = false
            this.openedWithKeyboard = false
            {{-- this.$refs.hiddenTextField.id = option.id --}}
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
    <label class="w-fit pl-0.5 text-sm text-slate-700 dark:text-slate-300">{{ $label }}</label>
    <div class="relative">
    
        <!-- trigger button  -->
        <button type="button" role="combobox" :class="{ 'border-blue-600' : isOpen, 'border-gray-300' : !isOpen}" class="inline-flex w-full items-center justify-between gap-2 whitespace-nowrap  bg-white h-10 px-4 py-2 text-sm font-medium capitalize tracking-wide text-slate-700 transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-300 dark:focus-visible:outline-blue-600 rounded-md border-2" aria-haspopup="listbox" aria-controls="industriesList" x-on:click="isOpen = ! isOpen" x-on:keydown.down.prevent="openedWithKeyboard = true" x-on:keydown.enter.prevent="openedWithKeyboard = true" x-on:keydown.space.prevent="openedWithKeyboard = true" x-bind:aria-label="selectedOption ? selectedOption.id : 'Please Select'" x-bind:aria-expanded="isOpen || openedWithKeyboard">
            
            <template x-for="item in options">
            <div x-show="selectedOption == item.id">                            
                <span x-show="null != item.display_name" class="block truncate text-sm" x-text="item.display_name"></span>
                <span x-show="null == item.display_name" class="block truncate text-sm" x-text="item.name"></span>
            </div>
                               
            </template>

            <div x-show="!selectedOption"  class="block truncate text-sm">                            
                <span class="block truncate">{{ __('Select') }}</span>
            </div>
     
            <!-- Chevron  -->
            <x-tabler-selector class="size-5" />
        </button>
    
        <!-- hidden input to grab the selected value  -->
        <input x-model="selectedOption" hidden type="text"  />
        
        <ul x-cloak x-show="isOpen || openedWithKeyboard" id="industriesList" class="absolute z-10 left-0 top-11 flex max-h-44 w-full flex-col overflow-hidden overflow-y-auto border-slate-300 bg-white py-1.5 dark:border-slate-700 dark:bg-slate-800 rounded-md border-2" role="listbox" aria-label="industries list" x-on:click.outside="isOpen = false, openedWithKeyboard = false" x-on:keydown.down.prevent="$focus.wrap().next()" x-on:keydown.up.prevent="$focus.wrap().previous()" x-transition x-trap="openedWithKeyboard">
            <template x-for="(item, index) in options" x-bind:key="item.id">   
                <li class="combobox-option inline-flex cursor-pointer justify-between gap-6 bg-white px-4 py-2 text-sm text-slate-700 hover:bg-slate-800/5 hover:text-black focus-visible:bg-slate-800/5 focus-visible:text-black focus-visible:outline-none dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-100/5 dark:hover:text-white dark:focus-visible:bg-slate-100/10 dark:focus-visible:text-white" role="option" x-on:click="setSelectedOption(item.id)" x-on:keydown.enter="setSelectedOption(item.id)" x-bind:id="'option-' + index" tabindex="0" >
                    <!-- Label  -->
                    <span x-bind:class="selectedOption == item.id ? 'font-bold' : null" x-text="item.name"></span>
                    
                    <!-- Screen reader 'selected' indicator  -->
                    <span class="sr-only" x-text="selectedOption == item.id ? 'selected' : null"></span>
                    <!-- Checkmark  -->
                    <x-tabler-check class="size-5" x-cloak x-show="selectedOption == item.id" />
 
                </li>
            </template>
        </ul>
    </div>
    </div>
    


