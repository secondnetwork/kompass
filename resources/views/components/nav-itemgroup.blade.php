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
        class="flex items-center justify-center size-5 md:size-6  cursor-pointer transition-colors"
        :class="{ 'bg-neutral-100': popoverOpen }"
        title="{{ __('Settings') }}">
        <x-tabler-adjustments class="cursor-pointer stroke-current size-5 md:size-6 text-stone-500" />
    </button>

    <div x-ref="popover"
        x-show="popoverOpen"
        x-init="setTimeout(function(){ popoverHeightCalculate(); }, 100);"
        @click.away="popoverOpen = false"
        @keydown.escape.window="popoverOpen = false"
        :class="{
            'top-0 mt-10': popoverPosition == 'bottom',
            'bottom-0 mb-10': popoverPosition == 'top'
        }"
        class="absolute top-0 right-0 z-50 w-96"
        x-cloak>

        <div x-show="popoverOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-1"
            class="bg-white border border-neutral-200 rounded-md shadow-md p-3 grid   items-center gap-2">

            {{-- Arrow --}}
            <div x-show="popoverPosition == 'bottom'" class="absolute top-0 right-3 inline-block w-5 -mt-2.5 overflow-hidden">
                <div class="w-2.5 h-2.5 origin-bottom-left rotate-45 bg-white border-t border-l border-neutral-200 rounded-sm"></div>
            </div>
            <div x-show="popoverPosition == 'top'" class="absolute bottom-0 right-3 inline-block w-5 mb-px overflow-hidden">
                <div class="w-2.5 h-2.5 origin-top-left -rotate-45 bg-white border-b border-l border-neutral-200 rounded-sm"></div>
            </div>

            @if ($itemblocks->subgroup == null)
                {{-- Layout --}}
                <div class="flex gap-2">
                <span class="text-xs flex items-center font-medium px-1.5 py-0.5 rounded  whitespace-nowrap">Layout</span>
                <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'layout', 'content')">
                    @if ($layout == 'content')
                        <x-tabler-container class="stroke-blue-500" />
                    @else
                        <x-tabler-container />
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
                </div>
                @if ($type == 'group')
                    {{-- Align --}}
                    <div class="flex gap-2">
                    <span class="text-xs flex items-center font-medium px-1.5 py-0.5 rounded  whitespace-nowrap">Align</span>
                    @php
                        $alignOptions = [
                            '' => 'tabler-layout-align-top',
                            'items-center' => 'tabler-layout-align-middle',
                            'items-end' => 'tabler-layout-align-bottom',
                        ];
                        $currentAlign = $itemblocks->getMeta('align') ?? '';
                    @endphp
                    @foreach ($alignOptions as $alignValue => $iconName)
                        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'align', '{{ $alignValue }}')">
                            @if ($currentAlign == $alignValue)
                                @svg($iconName, 'stroke-blue-500')
                            @else
                                @svg($iconName)
                            @endif
                        </span>
                    @endforeach
                    </div>
                    <div class="flex gap-2">    
                    {{-- Mobile Layout --}}
                    <span class="text-xs flex items-center font-medium px-1.5 py-0.5 rounded  whitespace-nowrap">Mobile reverse</span>
                    @php
                        $currentOrder = $itemblocks->getMeta('order') ?? '';
                    @endphp
                    <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'order', '')">
                        @if (empty($currentOrder))
                            @svg('tabler-layout-off', 'stroke-blue-500')
                        @else
                            @svg('tabler-layout-off')
                        @endif
                    </span>
                    <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'order', 'reverse')">
                        @if ($currentOrder == 'reverse')
                            @svg('tabler-reorder', 'stroke-blue-500')
                        @else
                            @svg('tabler-reorder')
                        @endif
                    </span>
                    </div>
                @endif
            @endif

            @if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup')
                {{-- Layout Grid --}}
                <div class="flex gap-2">
                <span class="text-xs flex items-center font-medium px-1.5 py-0.5 rounded  whitespace-nowrap">Layout Grid</span>
                @foreach([1, 2, 3, 4, 5] as $gridNumber)
                    <span class="cursor-pointer" wire:click="updateLayoutGrid({{ $itemblocks->id }}, {{ $gridNumber }})">
                        @if ($itemblocks->layoutgrid == $gridNumber)
                            @svg('tabler-square-number-' . $gridNumber, 'stroke-blue-500')
                        @else
                            @svg('tabler-square-number-' . $gridNumber)
                        @endif
                    </span>
                @endforeach
                </div>
                <livewire:editable-meta label="Classname: " meta-key="css-classname" :itemblocks="$itemblocks" wire-action="updateMeta" :key="'css-classname-' . $itemblocks->id" />
                <livewire:editable-meta label="ID: " meta-key="id-anchor" :itemblocks="$itemblocks" wire-action="updateMeta" :key="'id-anchor-' . $itemblocks->id" />
            @endif

            @if ($itemblocks->type == 'wysiwyg')
                <span class="text-xs flex items-center font-medium px-1.5 py-0.5 rounded  whitespace-nowrap">{{ __('Alignment') }}</span>
                @foreach(['align-left' => 'tabler-align-left', 'align-center' => 'tabler-align-center', 'align-right' => 'tabler-align-right'] as $alignValue => $iconName)
                    <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'alignment', '{{ $alignValue }}')">
                        @if ($alignment == $alignValue)
                            @svg($iconName, 'stroke-blue-500')
                        @else
                            @svg($iconName)
                        @endif
                    </span>
                @endforeach
            @elseif ($itemblocks->type == 'gallery')
                <span class="text-xs flex items-center font-medium px-1.5 py-0.5 rounded  whitespace-nowrap">Slider</span>
                @foreach(['' => ['icon' => 'tabler-layout-dashboard', 'class' => 'rotate-90'], 'true' => ['icon' => 'tabler-carousel-horizontal', 'class' => '']] as $sliderValue => $sliderData)
                    <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'slider', '{{ $sliderValue }}')">
                        @if ($slider == $sliderValue)
                            @svg($sliderData['icon'], 'stroke-blue-500 ' . $sliderData['class'])
                        @else
                            @svg($sliderData['icon'], $sliderData['class'])
                        @endif
                    </span>
                @endforeach
            @endif

        </div>
    </div>
</div>
