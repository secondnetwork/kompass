@props(['itemblocks'])

@php
    $visibility = $itemblocks->getMeta('visibility') ?? '';
@endphp

<x-kompass::settings-section :title="__('Visibility')">
    <div class="flex items-center gap-2">
        <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Show on') }}</span>
        <div class="flex items-center gap-1">
            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $visibility == '' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                title="{{ __('All devices') }}"
                wire:click="saveset({{ $itemblocks->id }},'visibility', '')">
                <x-tabler-devices class="{{ $visibility == '' ? 'stroke-blue-500' : '' }}" />
            </span>
            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $visibility == 'mobile' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                title="{{ __('Mobile only') }}"
                wire:click="saveset({{ $itemblocks->id }},'visibility', 'mobile')">
                <x-tabler-device-mobile class="{{ $visibility == 'mobile' ? 'stroke-blue-500' : '' }}" />
            </span>
            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $visibility == 'desktop' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                title="{{ __('Desktop only') }}"
                wire:click="saveset({{ $itemblocks->id }},'visibility', 'desktop')">
                <x-tabler-device-desktop class="{{ $visibility == 'desktop' ? 'stroke-blue-500' : '' }}" />
            </span>
        </div>
    </div>
</x-kompass::settings-section>
