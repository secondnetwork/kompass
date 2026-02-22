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
    <label class="block font-medium text-base-content/70 mb-1" for='{{ $name }}'>{{ $label }}</label>
    <textarea name='{{ $name }}' id='{{ $name }}' {{ $attributes->merge(['class' => '']) }}>{{ $slot }}</textarea>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>