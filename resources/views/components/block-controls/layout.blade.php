@props(['itemblocks'])

@php $layout = $itemblocks->layout ?? ''; @endphp

{{-- Module layout: Col Span when nested in a group, otherwise Width. --}}
<x-kompass::settings-section :title="__('Layout')">
    @if (! ($itemblocks->subgroup == null))
        <div class="flex items-center gap-2">
            <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Col Span') }}</span>
            <div class="flex items-center gap-1">
                @foreach ([1, 2, 3, 4, 5] as $num)
                    <span class="cursor-pointer rounded p-0.5 transition-colors {{ $itemblocks->layoutgrid == (string) $num ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                        x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '{{ $num }}')">
                        @svg('tabler-square-number-'.$num, $itemblocks->layoutgrid == (string) $num ? 'stroke-blue-500' : '')
                    </span>
                @endforeach
            </div>
        </div>
    @else
        <div class="flex items-center gap-2">
            <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Width') }}</span>
            <div class="flex items-center gap-1">
                <span class="cursor-pointer rounded p-0.5 transition-colors {{ $layout == 'content' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                    wire:click="saveset({{ $itemblocks->id }},'layout', 'content')">
                    <x-tabler-container class="{{ $layout == 'content' ? 'stroke-blue-500' : '' }}" />
                </span>
                <span class="cursor-pointer rounded p-0.5 transition-colors {{ $layout == 'popout' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                    wire:click="saveset({{ $itemblocks->id }},'layout', 'popout')">
                    <x-tabler-carousel-vertical class="{{ $layout == 'popout' ? 'stroke-blue-500' : '' }}" />
                </span>
                <span class="cursor-pointer rounded p-0.5 transition-colors {{ $layout == 'fullpage' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                    wire:click="saveset({{ $itemblocks->id }},'layout', 'fullpage')">
                    <x-tabler-arrow-autofit-width class="{{ $layout == 'fullpage' ? 'stroke-blue-500' : '' }}" />
                </span>
            </div>
        </div>
    @endif
</x-kompass::settings-section>
