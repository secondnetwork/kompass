@props([
    'label' => null,
    'id' => null,
    'name' => null,
    'type' => 'text',
    'description' => ''
])

@php $wireModel = $attributes->get('wire:model'); @endphp

<div>
    @if($label)
        <label for="{{ $id ?? '' }}" class="block text-sm font-medium leading-6 text-gray-900">{{ $label  }}</label>
    @endif

    @if($description ?? false)
        <p class="text-sm leading-6 text-base-content/70">{{ $description ?? '' }}</p>
    @endif

    <div data-model="{{ $wireModel }}" class="mt-1.5 rounded-md">
        <input {{ $attributes->merge(['class' => 'appearance-none flex w-full h-10 px-3 py-2 text-sm bg-white rounded-md border-gray-300 ring-offset-background placeholder:text-base-content/70 dark:placeholder:text-gray-400 focus:border-blue-600 dark:focus:border-gray-700 focus:outline-none  disabled:cursor-not-allowed disabled:opacity-50']) }} {{ $attributes->whereStartsWith('wire:model') }} id="{{ $id ?? '' }}" name="{{ $name ?? '' }}" type="{{ $type ?? '' }}" required autofocus />
    </div>

    @error($wireModel)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>