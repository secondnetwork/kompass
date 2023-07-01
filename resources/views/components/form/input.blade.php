@props([
    'type' => 'text',
    'name' => '',
    'label' => '',
    'value' => '',
])

{{-- @if ($label === '')
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
@endif --}}

<div>
    @if ($label !== '')<label for='{{ $name }}' class="text-md mb-1 block">{{ $label }}</label>@endif
    <input type='{{ $type }}' name='{{ $name }}' id='{{ $name }}' value='{{ $value }}'  {{ $attributes->merge(['class' => 'block p-2.5 w-full border-2 border-gray-300 text-base border-gray-300 rounded-md']) }}>
</div>