@props(['itemblocks'])

@php
    $slider = $itemblocks->slider ?? '';
    $lightbox = $itemblocks->lightbox ?? '';
@endphp

<x-kompass::settings-section :title="__('Gallery')">
    <div class="flex items-center gap-2">
        <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Slider') }}</span>
        <div class="flex items-center gap-1">
            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $slider == '' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                wire:click="saveset({{ $itemblocks->id }},'slider', '')">
                <x-tabler-layout-dashboard class="rotate-90 {{ $slider == '' ? 'stroke-blue-500' : '' }}" />
            </span>
            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $slider == 'true' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                wire:click="saveset({{ $itemblocks->id }},'slider', 'true')">
                <x-tabler-carousel-horizontal class="{{ $slider == 'true' ? 'stroke-blue-500' : '' }}" />
            </span>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Image Grid') }}</span>
        <div class="flex items-center gap-1">
            @foreach ([1, 2, 3, 4, 5] as $num)
                <span class="cursor-pointer rounded p-0.5 transition-colors {{ $itemblocks->grid == (string) $num ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                    x-data wire:click="updateGrid({{ $itemblocks->id }}, '{{ $num }}')">
                    @svg('tabler-square-number-'.$num, $itemblocks->grid == (string) $num ? 'stroke-blue-500' : '')
                </span>
            @endforeach
        </div>
    </div>
    <div class="flex items-center gap-2 {{ $slider === 'true' ? 'opacity-40' : '' }}">
        <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Lightbox') }}</span>
        <input type="checkbox" class="toggle toggle-sm toggle-primary" @checked($lightbox === 'true')
            @disabled($slider === 'true')
            x-data
            @change="$wire.saveset({{ $itemblocks->id }}, 'lightbox', $event.target.checked ? 'true' : '')" />
    </div>
</x-kompass::settings-section>
