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

{{-- <div> --}}
    {{-- <label for='{{ $name }}'>{{ $label }}</label>
    <select name='{{ $name }}' id='{{ $name }}' {{ $attributes }}>
    @if ($placeholder != '')
       <option value=''>{{ $placeholder }}</option>
    @endif
    {{ $slot }}
    </select> --}}

<div class="flex items-center cursor-pointer">
    
    <div 
    x-data="{open: false,
        selected: @entangle($attributes->wire('model')),
        init() {
            {{-- this.selected = ''; --}}
            this.query = '';
            this.filteredPeopleCount = this.options.length
        },
        options: @js($options),

            toggle() {
                this.open = !this.open
            },
            getName() {

            },
            filteredPeople() {
                return this.options.filter(
                    item => item.name
                    .toLowerCase()
                    .replace(/\s+/g, '')
                    .includes(this.query.toLowerCase().replace(/\s+/g, ''))
                );
            },

            countFilteredPeople() {
                this.filteredPeopleCount = this.options.filter(
                    item => item.name
                    .toLowerCase()
                    .replace(/\s+/g, '')
                    .includes(this.query.toLowerCase().replace(/\s+/g, ''))
                ).length;
            },
        }" 
        class="w-full mt-4 space-y-4 text-slate-800">

        <div class="w-full relative">
            {{ $label }}
            <div class="flex items-center cursor-pointer rounded-md border bg-white border-secondary-300 text-base ">
         
                   <template x-for="item in filteredPeople">
                        <div x-show="selected == item.id" @click="toggle"
                            class="relative cursor-pointer w-full select-none pl-3 pr-10 py-2 text-base">                            
                            <span class="block truncate" x-text="item.name"></span>
                        </div>
                                                
                    </template>
                    <div x-show="!selected"  @click="toggle" class="relative cursor-pointer w-full select-none pl-3 pr-10 py-2 text-base">                            
                        <span class="block truncate">{{ __('Select') }}</span>
                    </div>

                <button @click="toggle" class="px-1 h-auto absolute right-0">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.41 8.83984L12 13.4198L16.59 8.83984L18 10.2498L12 16.2498L6 10.2498L7.41 8.83984Z" fill="currentColor"></path></svg>
                </button>
                
            </div>

            <div class="absolute z-50 w-full">

                <div x-show="open === true" @click.outside="open = false"
                    class="flex flex-col justify-start w-full bg-white list-none py-2 rounded-lg shadow-md border border-gray-200">
                    <template x-if="filteredPeopleCount === 0 && query !== ''">
                        <div class="py-1 px-5">Nothing found...</div>
                    </template>

                    <template x-for="item in filteredPeople">
                        <li @click="selected = item.id, open = false, query = ''" tabindex="0" x-bind:class="{ 'bg-blue-100': selected == item.id }"
                            class="relative cursor-pointer hover:bg-gray-100 select-none py-1 pl-10 px-5">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        
                                <svg x-show="selected == item.id" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </span>
                            
                            <span class="block truncate" x-text="item.name"></span>
                        </li>
                    </template>

                    <input x-model="selected" hidden name="selectedinputname">

                </div>
                
            </div>
        </div>
    </div>

</div>



