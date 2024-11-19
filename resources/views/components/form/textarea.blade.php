@props([
    'name'  => '',
    'label' => '',
    'value' => ''
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

<div>
    <label for='{{ $name }}' class="block text-sm font-medium leading-6 text-gray-900 ">{{ $label }}</label>
    <textarea name='{{ $name }}' id='{{ $name }}' {{ $attributes->merge(['class' => 'bg-white block mt-1.5 p-2.5 w-full border-2 border-gray-300 text-sm border-gray-300 rounded-md']) }}>{{ $slot }}</textarea>
</div>