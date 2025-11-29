@props([
    'item' => ''
])
@dump($item->toArray())

<x-blocks.group :item="$item" />

@if ($item->subgroup)
    <x-blocks.longtext :item="$item"
        class="max-w-none grid col-span-{{ $item->layoutgrid }} {{ $item->getMeta('layout') ?? '' }} {{ $item->getMeta('alignment') }} " />
    <x-blocks.gallery :item="$item"
        class="{{ $item->getMeta('layout') ?? '' }} grid col-span-{{ $item->grid }} {{ $item->getMeta('alignment') }} " />
@else

{{  $item->getMeta('layout') }}
    <x-blocks.longtext :item="$item"
        class="max-w-none {{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('alignment') }} " />
    <x-blocks.gallery :item="$item"
        class="{{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('alignment') }} " />
@endif

<x-blocks.accordiongroup :item="$item" />

<x-blocks.oembed :item="$item"
    class="col-span-{{ $item->layoutgrid }} {{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('alignment') }}" />

<x-blocks.download :item="$item" />

{{-- @switch($item->type)
    @case('group')
            <x-blocks.group :item="$item" />
        @break
    @case('wysiwyg')
            <x-blocks.longtext :item="$item" class="max-w-none @if ($item->subgroup) grid col-span-{{ $item->layoutgrid }} @endif {{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('alignment') }} " />
        @break
    @case('gallery')  
            <x-blocks.gallery :item="$item" class="{{ $item->getMeta('layout') ?? 'fullpage' }} @if ($item->subgroup) grid col-span-{{ $item->grid }} @endif {{ $item->getMeta('alignment') }} " />
        @break  
    @case('accordiongroup')
            <x-blocks.accordiongroup :item="$item" />
        @break
    @case('download')
            <x-blocks.accordiongroup :item="$item" />
        @break
    @case('video')
         <x-blocks.oembed :item="$item" class="col-span-{{ $item->layoutgrid }} {{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('alignment') }}" />
        @break
    @default
        
@endswitch --}}


{{-- <x-blocks.highlights :item="$item" /> --}}
{{-- <x-blocks.content-box :item="$item" /> --}}
{{-- <x-blocks.hero :item="$item" /> --}}
{{-- <x-blocks.heroboxs :item="$item" /> --}}
{{-- <x-blocks.button :item="$item" /> --}}
{{-- <x-blocks.morebox :item="$item" /> --}}
{{-- <x-blocks.teaser :item="$item" /> --}}
{{-- <x-blocks.anchormenu :item="$item" /> --}}

{{-- <div class="grid grid-cols-2">
    <p>1</p>
    <p>2</p>
</div> --}}