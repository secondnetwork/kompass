@props(['itemblocks'])

@php
    $type = $itemblocks->type ?? '';
    $layout = $itemblocks->layout ?? '';
@endphp

{{-- Container (group / accordiongroup) layout: only shown at top level. --}}
@if ($itemblocks->subgroup == null)
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
