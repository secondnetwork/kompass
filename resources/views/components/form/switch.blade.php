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
          <span class="block text-sm text-base-content/70">{{ $description ?? '' }}</span>
        </div>
        
        <input {{ $attributes }} type="checkbox" class="toggle theme-controller">


    </label>
</div>
