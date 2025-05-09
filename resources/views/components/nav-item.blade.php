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

@if(!$itemblocks->subgroup == null)
<nav-item class="flex items-center gap-2">
    <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Layout Grid</span>
    <span class="cursor-pointer" x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '1')">
        @if ($itemblocks->layoutgrid == '1')
            <x-tabler-square-number-1 class="stroke-blue-500" />
        @else
            <x-tabler-square-number-1 />
        @endif
    </span>
    <span class="cursor-pointer" x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '2')">
        @if ($itemblocks->layoutgrid == '2')
            <x-tabler-square-number-2 class="stroke-blue-500" />
        @else
            <x-tabler-square-number-2 />
        @endif
    </span>
    <span class="cursor-pointer" x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '3')">
        @if ($itemblocks->layoutgrid == '3')
            <x-tabler-square-number-3 class="stroke-blue-500" />
        @else
            <x-tabler-square-number-3 />
        @endif
    </span>
    <span class="cursor-pointer" x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '4')">
        @if ($itemblocks->layoutgrid == '4')
            <x-tabler-square-number-4 class="stroke-blue-500" />
        @else
            <x-tabler-square-number-4 />
        @endif
    </span>
    <span class="cursor-pointer" x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '5')">
        @if ($itemblocks->layoutgrid == '5')
            <x-tabler-square-number-5 class="stroke-blue-500" />
        @else
            <x-tabler-square-number-5 />
        @endif
    </span>



</nav-item>
@endif

<nav-item class="flex flex-wrap items-center gap-2">
  @if($itemblocks->subgroup == null)


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
  label="Classname:"
  meta-key="css-classname"
  :itemblocks="$itemblocks"
  wire-action="updateMeta"
  :key="'css-classname-'.$itemblocks->id"
  />
  <livewire:editable-meta
      label="ID:"
      meta-key="id-anchor"
      :itemblocks="$itemblocks"
      wire-action="updateMeta"
      :key="'id-anchor-'.$itemblocks->id"
  />

{{-- 
    <div class="w-full p-4 bg-white border rounded-md shadow-sm border-gray-300">
        <div class="grid gap-2">

          <div id="layout" class="flex items-center gap-2">
    
            <div  
            @if ($layout == 'content'):class="'border-blue-600'" @else :class="'border-blue-600/20'" @endif class="border-2 rounded-lg p-1  cursor-pointer"
            wire:click="saveset({{ $itemblocks->id }},'layout', 'content')">
            <img src="{{ kompass_asset('icons-blocks/content-page.png') }}" alt="">
            <span class="text-xs block mt-2">1x</span>
            </div>

            <div
            @if ($layout == 'popout'):class="'border-blue-600'" @else :class="'border-blue-600/20'" @endif :class="layout === 'popout' ? 'border-blue-600' : 'border-blue-600/20'"  class="border-2 rounded-lg p-1  cursor-pointer"
            wire:click="saveset({{ $itemblocks->id }},'layout', 'popout')">
            <img src="{{ kompass_asset('icons-blocks/popout-page.png') }}" alt="">
            <span class="text-xs block mt-2">2x</span>
            </div>

            <div
            @if ($layout == 'fullpage'):class="'border-blue-600'" @else :class="'border-blue-600/20'" @endif :class="layout == 'fullpage' ? 'border-blue-600' : 'border-blue-600/20'"  class=" border-2 rounded-lg p-1  cursor-pointer"
            wire:click="saveset({{ $itemblocks->id }},'layout', 'fullpage')">
            <img src="{{ kompass_asset('icons-blocks/full-page.png') }}" alt="">
            <span class="text-xs block mt-2">Fullpage</span>
            </div>

      
            
         
        </div>
      </div>
  </div> --}}

    @endif

     
</nav-item>     



@if ($itemblocks->type == 'wysiwyg')
    <nav-item class="flex items-center gap-2 py-2">
        <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300 ">{{ __('Alignment') }}</span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'alignment', 'align-left')">
            @if ($alignment == 'align-left')
                <x-tabler-align-left class="stroke-blue-500" />
            @else
                <x-tabler-align-left />
            @endif
        </span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'alignment', 'align-center')">
            @if ($alignment == 'align-center')
                <x-tabler-align-center class="stroke-blue-500" />
            @else
                <x-tabler-align-center />
            @endif
        </span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'alignment', 'align-right')">
            @if ($alignment == 'align-right')
                <x-tabler-align-right class="stroke-blue-500" />
            @else
                <x-tabler-align-right />
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