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

<div x-data="{
        popoverOpen: false,
        popoverPosition: 'bottom',
        popoverHeight: 0,
        popoverOffset: 8,
        popoverHeightCalculate() {
            this.$refs.popover.classList.add('invisible');
            this.popoverOpen = true;
            let that = this;
            $nextTick(function () {
                that.popoverHeight = that.$refs.popover.offsetHeight;
                that.popoverOpen = false;
                that.$refs.popover.classList.remove('invisible');
                that.popoverPositionCalculate();
            });
        },
        popoverPositionCalculate() {
            if (window.innerHeight < (this.$refs.popoverButton.getBoundingClientRect().top + this.$refs.popoverButton.offsetHeight + this.popoverOffset + this.popoverHeight)) {
                this.popoverPosition = 'top';
            } else {
                this.popoverPosition = 'bottom';
            }
        }
    }"
    x-init="
        window.addEventListener('resize', function(){ popoverPositionCalculate(); });
        $watch('popoverOpen', function(value){ if(value){ popoverPositionCalculate(); } });
    "
    class="relative inline-block">

    <button x-ref="popoverButton"
        @click="popoverOpen = !popoverOpen"
        class="flex items-center justify-center size-5 md:size-6 cursor-pointer transition-colors"
        :class="{ 'text-blue-500': popoverOpen }"
        title="{{ __('Settings') }}">
        <x-tabler-adjustments class="cursor-pointer stroke-current size-5 md:size-6 text-stone-500" />
    </button>

    <div x-ref="popover"
        x-show="popoverOpen"
        x-init="setTimeout(function(){ popoverHeightCalculate(); }, 100);"
        @click.away="popoverOpen = false"
        @keydown.escape.window="popoverOpen = false"
        :class="{
            'top-0 mt-5': popoverPosition == 'bottom',
            'bottom-0 mb-10': popoverPosition == 'top'
        }"
        class="absolute -right-3 z-50 w-96"
        x-cloak>

        <div x-show="popoverOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-1"
            class="bg-white border border-neutral-200 rounded-md shadow-md p-3 flex flex-col gap-1">

            {{-- Arrow --}}
            <div x-show="popoverPosition == 'bottom'" class="absolute top-0 right-3 inline-block w-5 -mt-2.5 overflow-hidden">
                <div class="w-2.5 h-2.5 origin-bottom-left rotate-45 bg-white border-t border-l border-neutral-200 rounded-sm"></div>
            </div>
            <div x-show="popoverPosition == 'top'" class="absolute bottom-0 right-3 inline-block w-5 mb-px overflow-hidden">
                <div class="w-2.5 h-2.5 origin-top-left -rotate-45 bg-white border-b border-l border-neutral-200 rounded-sm"></div>
            </div>

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-neutral-100 pb-2 mb-1">
                <span class="text-xs font-semibold text-neutral-500 uppercase tracking-wide">{{ __('Settings') }}</span>
                <button @click="popoverOpen = false" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                    <x-tabler-x class="size-4" />
                </button>
            </div>

            @if(!$itemblocks->subgroup == null)
                {{-- Layout Grid --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs text-neutral-500 w-24 shrink-0">{{ __('Col Span') }}</span>
                    <div class="flex items-center gap-1">
                        @foreach([1,2,3,4,5] as $num)
                            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $itemblocks->layoutgrid == (string)$num ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                                x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '{{ $num }}')">
                                @svg('tabler-square-number-' . $num, $itemblocks->layoutgrid == (string)$num ? 'stroke-blue-500' : '')
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-neutral-500 w-24 shrink-0">{{ __('Classname') }}</span>
                    <livewire:editable-meta label="" meta-key="css-classname" :itemblocks="$itemblocks" wire-action="updateMeta" :key="'css-classname-'.$itemblocks->id" />
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-neutral-500 w-24 shrink-0">{{ __('ID') }}</span>
                    <livewire:editable-meta label="" meta-key="id-anchor" :itemblocks="$itemblocks" wire-action="updateMeta" :key="'id-anchor-'.$itemblocks->id" />
                </div>
            @endif

            @if($itemblocks->subgroup == null)
                {{-- Layout --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs text-neutral-500 w-24 shrink-0">{{ __('Layout') }}</span>
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

            @if ($itemblocks->type == 'wysiwyg')
                {{-- Alignment --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs text-neutral-500 w-24 shrink-0">{{ __('Alignment') }}</span>
                    <div class="flex items-center gap-1">
                        @foreach(['align-left' => 'tabler-align-left', 'align-center' => 'tabler-align-center', 'align-right' => 'tabler-align-right'] as $alignValue => $iconName)
                            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $alignment == $alignValue ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                                wire:click="saveset({{ $itemblocks->id }},'alignment', '{{ $alignValue }}')">
                                @svg($iconName, $alignment == $alignValue ? 'stroke-blue-500' : '')
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Link --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs text-neutral-500 w-24 shrink-0">{{ __('Link') }}</span>
                    <livewire:editable-meta label="" meta-key="link-url" :itemblocks="$itemblocks" wire-action="updateMeta" :key="'link-url-'.$itemblocks->id" />
                </div>
            @endif

            @if ($itemblocks->type == 'gallery')
                {{-- Slider --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs text-neutral-500 w-24 shrink-0">{{ __('Slider') }}</span>
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

                {{-- Grid --}}
                <div class="flex items-center gap-2">
                    <span class="text-xs text-neutral-500 w-24 shrink-0">{{ __('Image Grid') }}</span>
                    <div class="flex items-center gap-1">
                        @foreach([1,2,3,4,5] as $num)
                            <span class="cursor-pointer rounded p-0.5 transition-colors {{ $itemblocks->grid == (string)$num ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                                x-data wire:click="updateGrid({{ $itemblocks->id }}, '{{ $num }}')">
                                @svg('tabler-square-number-' . $num, $itemblocks->grid == (string)$num ? 'stroke-blue-500' : '')
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
