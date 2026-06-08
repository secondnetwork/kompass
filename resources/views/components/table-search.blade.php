@props([
    'placeholder' => null,
])

{{-- Search field with a leading icon, matching the media library search. --}}
<div class="relative group w-full">
    <div class="absolute z-10 inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <x-tabler-search class="w-5 h-5 opacity-40 group-focus-within:opacity-100 transition-opacity" />
    </div>
    <input type="text"
        placeholder="{{ $placeholder ?? __('Search') . '...' }}"
        {{ $attributes->merge(['class' => 'input input-bordered w-full pl-10 focus:bg-base-100 transition-colors']) }} />
</div>
