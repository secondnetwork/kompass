@props([
    'itemblocks' => '',
    'layout' => '',
    'type' => '',
    'slider' => '',
])


@php
    $layout = $itemblocks->set->layout ?? '';
    $alignment = $itemblocks->set->alignment ?? '';
    $slider = $itemblocks->set->slider ?? '';
@endphp
<nav-item class="flex items-center gap-2">
    <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Layout</span>
    <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'layout', '')">
        @if ($layout == '')
            <x-tabler-columns-3 class="stroke-blue-500" />
        @else
            <x-tabler-columns-3 />
        @endif
    </span>
    <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'layout', 'popout')">
        @if ($layout == 'popout')
            <x-tabler-carousel-vertical class="stroke-blue-500" />
        @else
            <x-tabler-carousel-vertical />
        @endif
    </span>
    <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'layout', 'fullpage')">
        @if ($layout == 'fullpage')
            <x-tabler-arrow-autofit-width class="stroke-blue-500" />
        @else
            <x-tabler-arrow-autofit-width />
        @endif
    </span>
</nav-item> 
@if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup')
    <nav-item class="flex items-center gap-2">
        <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Grid</span>
        <span class="cursor-pointer" x-data wire:click="updateGrid({{ $itemblocks->id }}, '1')">
            @if ($itemblocks->grid == '1')
                <x-tabler-square-number-1 class="stroke-blue-500" />
            @else
                <x-tabler-square-number-1 />
            @endif
        </span>
        <span class="cursor-pointer" x-data wire:click="updateGrid({{ $itemblocks->id }}, '2')">
            @if ($itemblocks->grid == '2')
                <x-tabler-square-number-2 class="stroke-blue-500" />
            @else
                <x-tabler-square-number-2 />
            @endif
        </span>
        <span class="cursor-pointer" x-data wire:click="updateGrid({{ $itemblocks->id }}, '3')">
            @if ($itemblocks->grid == '3')
                <x-tabler-square-number-3 class="stroke-blue-500" />
            @else
                <x-tabler-square-number-3 />
            @endif
        </span>
        <span class="cursor-pointer" x-data wire:click="updateGrid({{ $itemblocks->id }}, '4')">
            @if ($itemblocks->grid == '4')
                <x-tabler-square-number-4 class="stroke-blue-500" />
            @else
                <x-tabler-square-number-4 />
            @endif
        </span>
        <span class="cursor-pointer" x-data wire:click="updateGrid({{ $itemblocks->id }}, '5')">
            @if ($itemblocks->grid == '5')
                <x-tabler-square-number-5 class="stroke-blue-500" />
            @else
                <x-tabler-square-number-5 />
            @endif
        </span>
    </nav-item>
@endif
@if ($itemblocks->type == 'wysiwyg')
    <nav-item class="flex items-center gap-2">
        <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300 ">{{ __('Alignment') }}</span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'alignment', 'left')">
            @if ($alignment == 'left')
                <x-tabler-box-align-left class="stroke-blue-500" />
            @else
                <x-tabler-box-align-left />
            @endif
        </span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'alignment', 'right')">
            @if ($alignment == 'right')
                <x-tabler-box-align-right class="stroke-blue-500" />
            @else
                <x-tabler-box-align-right />
            @endif
        </span>

    </nav-item>
@endif
@if ($itemblocks->type == 'gallery')
    <nav-item class="flex items-center gap-2">
        <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300 ">Slider</span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'slider', '')">
            @if ($slider == '')
                <x-tabler-layout-dashboard class="stroke-blue-500 rotate-90" />
            @else
                <x-tabler-layout-dashboard class="rotate-90" />
            @endif
        </span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'slider', 'true')">
            @if ($slider == 'true')
                <x-tabler-carousel-horizontal class="stroke-blue-500" />
            @else
                <x-tabler-carousel-horizontal />
            @endif
        </span>
    </nav-item>
@endif
