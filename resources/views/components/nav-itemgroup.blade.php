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
<nav-item class="flex items-center gap-2">

    <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Layout</span>
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
   
  
    <livewire:editable-meta
        label="Classname"
        meta-key="css-classname"
        :itemblocks="$itemblocks"
        wire-action="updateMeta"
        :key="'css-classname-'.$itemblocks->id"
    />
    <livewire:editable-meta
        label="ID"
        meta-key="id-anchor"
        :itemblocks="$itemblocks"
        wire-action="updateMeta"
        :key="'id-anchor-'.$itemblocks->id"
    />

     
</nav-item> 
@if ($itemblocks->type == 'group' || $itemblocks->type == 'accordiongroup')
<nav-item class="flex items-center gap-2">
    <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Layout Grid</span>
    @php
        $gridOptions = [1, 2, 3, 4, 5];
    @endphp
    @foreach($gridOptions as $gridNumber)
        <span class="cursor-pointer" wire:click="updateLayoutGrid({{ $itemblocks->id }}, {{ $gridNumber }})">
            @if($itemblocks->layoutgrid == $gridNumber)
                @svg('tabler-square-number-'.$gridNumber, 'stroke-blue-500')
            @else
                @svg('tabler-square-number-'.$gridNumber)
            @endif
        </span>
    @endforeach
</nav-item>

@endif
<nav-item class="flex items-center gap-2">
    @if($itemblocks->type == 'wysiwyg')
        <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300 ">{{ __('Alignment') }}</span>
        @php
            $alignments = [
                'align-left' => 'tabler-align-left',
                'align-center' => 'tabler-align-center',
                'align-right' => 'tabler-align-right',
            ];
        @endphp
         @foreach($alignments as $alignmentValue => $iconName)
            <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'alignment', '{{ $alignmentValue }}')">
                 @if ($alignment == $alignmentValue)
                    @svg($iconName, 'stroke-blue-500')
                @else
                    @svg($iconName)
                @endif
            </span>
         @endforeach
    @elseif($itemblocks->type == 'gallery')
       <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300 ">Slider</span>
        @php
            $sliders = [
                '' => ['icon' => 'tabler-layout-dashboard', 'class' => 'rotate-90'],
                'true' => ['icon' => 'tabler-carousel-horizontal', 'class' => ''],
            ];
        @endphp
        @foreach($sliders as $sliderValue => $sliderData)
             <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'slider', '{{ $sliderValue }}')">
                @if ($slider == $sliderValue)
                     @svg($sliderData['icon'], 'stroke-blue-500 ' . $sliderData['class'])
                @else
                   @svg($sliderData['icon'], $sliderData['class'])
                @endif
            </span>
        @endforeach
    @endif
</nav-item>
