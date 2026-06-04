@props([
    'itemblocks' => '',
    'layout' => '',
    'type' => '',
    'slider' => '',
])

@php
    $layout = $itemblocks->layout ?? '';
    $alignment = $itemblocks->alignment ?? '';
    $slider = $itemblocks->slider ?? '';
@endphp

<div x-data="{ open: false }" class="relative inline-block">

    <button type="button" @click="open = true"
        class="flex items-center justify-center size-5 md:size-6 cursor-pointer transition-colors"
        title="{{ __('Settings') }}">
        <x-tabler-adjustments class="cursor-pointer stroke-current size-5 md:size-6 text-stone-500" />
    </button>

    <x-kompass::offcanvas :w="'w-1/3'">
        <x-slot name="button">
            <h4 class="font-bold text-lg">{{ __('Block Settings') }}</h4>
        </x-slot>

        <x-slot name="body">
            <x-kompass::block-settings-header :itemblocks="$itemblocks" />

            {{-- Layout --}}
            <x-kompass::settings-section :title="__('Layout')">
                @if (! $itemblocks->subgroup == null)
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

            @if ($itemblocks->type == 'wysiwyg')
                {{-- Alignment --}}
                <x-kompass::settings-section :title="__('Alignment')">
                    <div class="flex items-center gap-1">
                        @foreach (['align-left' => 'tabler-align-left', 'align-center' => 'tabler-align-center', 'align-right' => 'tabler-align-right'] as $alignValue => $iconName)
                            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $alignment == $alignValue ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                                wire:click="saveset({{ $itemblocks->id }},'alignment', '{{ $alignValue }}')">
                                @svg($iconName, $alignment == $alignValue ? 'stroke-blue-500' : '')
                            </span>
                        @endforeach
                    </div>
                </x-kompass::settings-section>

                {{-- Link --}}
                <x-kompass::settings-section :title="__('Link')">
                    <x-kompass::block-meta-input :itemblocks="$itemblocks" meta-key="link-url" placeholder="https://example.com" />
                </x-kompass::settings-section>
            @endif

            @if ($itemblocks->type == 'gallery')
                {{-- Gallery --}}
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
                </x-kompass::settings-section>
            @endif

            {{-- Color --}}
            <x-kompass::settings-section :title="__('Block Color')">
                <x-kompass::block-color :itemblocks="$itemblocks" />
            </x-kompass::settings-section>

                {{-- Advanced --}}
                <x-kompass::settings-section :title="__('Advanced')">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Classname') }}</span>
                        <x-kompass::block-meta-input :itemblocks="$itemblocks" meta-key="css-classname" placeholder="my-class" />
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('ID') }}</span>
                        <x-kompass::block-meta-input :itemblocks="$itemblocks" meta-key="id-anchor" placeholder="section-id" />
                    </div>
                </x-kompass::settings-section>
          
        </x-slot>
    </x-kompass::offcanvas>
</div>
