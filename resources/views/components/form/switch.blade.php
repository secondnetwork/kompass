@props([
    'label' => null,
    'id' => null,
    'name' => null,
    'type' => 'text',
    'description' => '',
])

@php $wireModel = $attributes->get('wire:model'); @endphp
<div class="max-w-2xl">
    <label class="inline-flex items-center cursor-pointer justify-between gap-4 w-full" data-model="{{ $wireModel }}">

        <div class="py-3 text-sm">
          <span class="font-bold">{{ $label }}</span>
          <span class="block text-sm text-gray-500">{{ $description ?? '' }}</span>
        </div>
        
        <input {{ $attributes }} type="checkbox" class="sr-only peer">
        <div
            class="relative w-9 h-5 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
        </div>

    </label>
</div>
