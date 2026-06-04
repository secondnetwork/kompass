@props([
    'itemblocks' => '',
    'layout' => '',
    'type' => '',
    'slider' => '',
])

@php
    $type = $itemblocks->type ?? '';
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

            @if ($itemblocks->subgroup == null)
                {{-- Layout --}}
                <x-kompass::settings-section :title="__('Layout')">
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

                    @if ($type == 'group')
                        @php
                            $alignOptions = [
                                '' => ['icon' => 'tabler-layout-align-top', 'label' => 'Top'],
                                'items-center' => ['icon' => 'tabler-layout-align-middle', 'label' => 'Middle'],
                                'items-end' => ['icon' => 'tabler-layout-align-bottom', 'label' => 'Bottom'],
                            ];
                            $currentAlign = $itemblocks->getMeta('align') ?? '';
                            $currentOrder = $itemblocks->getMeta('order') ?? '';
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Align') }}</span>
                            <div class="flex items-center gap-1">
                                @foreach ($alignOptions as $alignValue => $alignData)
                                    <span class="cursor-pointer rounded p-0.5 transition-colors {{ $currentAlign == $alignValue ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                                        wire:click="saveset({{ $itemblocks->id }},'align', '{{ $alignValue }}')">
                                        @svg($alignData['icon'], $currentAlign == $alignValue ? 'stroke-blue-500' : '')
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Mobile reverse') }}</span>
                            <div class="flex items-center gap-1">
                                <span class="cursor-pointer rounded p-0.5 transition-colors {{ empty($currentOrder) ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                                    wire:click="saveset({{ $itemblocks->id }},'order', '')">
                                    @svg('tabler-layout-off', empty($currentOrder) ? 'stroke-blue-500' : '')
                                </span>
                                <span class="cursor-pointer rounded p-0.5 transition-colors {{ $currentOrder == 'reverse' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                                    wire:click="saveset({{ $itemblocks->id }},'order', 'reverse')">
                                    @svg('tabler-reorder', $currentOrder == 'reverse' ? 'stroke-blue-500' : '')
                                </span>
                            </div>
                        </div>
                    @endif
                </x-kompass::settings-section>
            @endif

            @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup')
                {{-- Grid --}}
                <x-kompass::settings-section :title="__('Layout Grid')">
                    <div class="flex items-center gap-1">
                        @foreach ([1, 2, 3, 4, 5] as $gridNumber)
                            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $itemblocks->layoutgrid == $gridNumber ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                                wire:click="updateLayoutGrid({{ $itemblocks->id }}, {{ $gridNumber }})">
                                @svg('tabler-square-number-'.$gridNumber, $itemblocks->layoutgrid == $gridNumber ? 'stroke-blue-500' : '')
                            </span>
                        @endforeach
                    </div>
                </x-kompass::settings-section>
            @endif

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
            @elseif ($itemblocks->type == 'gallery')
                {{-- Gallery --}}
                <x-kompass::settings-section :title="__('Gallery')">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Slider') }}</span>
                        <div class="flex items-center gap-1">
                            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $slider == '' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                                wire:click="saveset({{ $itemblocks->id }},'slider', '')">
                                @svg('tabler-layout-dashboard', 'rotate-90 '.($slider == '' ? 'stroke-blue-500' : ''))
                            </span>
                            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $slider == 'true' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                                wire:click="saveset({{ $itemblocks->id }},'slider', 'true')">
                                @svg('tabler-carousel-horizontal', $slider == 'true' ? 'stroke-blue-500' : '')
                            </span>
                        </div>
                    </div>
                </x-kompass::settings-section>
            @endif

            {{-- Color --}}
            <x-kompass::settings-section :title="__('Block Color')">
                <x-kompass::block-color :itemblocks="$itemblocks" />
            </x-kompass::settings-section>

            @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup')
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
            @endif
        </x-slot>
    </x-kompass::offcanvas>
</div>
