@props([
    'item' => ''
])


<x-blocks.group :item="$item" />

@if ($item->subgroup)
    <x-blocks.longtext :item="$item"
        class="max-w-none grid col-span-{{ $item->layoutgrid }} {{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('alignment') }} " />
    <x-blocks.gallery :item="$item"
        class="{{ $item->getMeta('layout') ?? 'fullpage' }} grid col-span-{{ $item->grid }} {{ $item->getMeta('alignment') }} " />
@else
    <x-blocks.longtext :item="$item"
        class="max-w-none {{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('alignment') }} " />
    <x-blocks.gallery :item="$item"
        class="{{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('alignment') }} " />
@endif

<x-blocks.accordiongroup :item="$item" />

<x-blocks.oembed :item="$item"
    class="col-span-{{ $item->layoutgrid }} {{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('alignment') }}" />

<x-blocks.download :item="$item" />
{{-- <x-blocks.highlights :item="$item" /> --}}
{{-- <x-blocks.content-box :item="$item" /> --}}
{{-- <x-blocks.hero :item="$item" /> --}}
{{-- <x-blocks.heroboxs :item="$item" /> --}}
{{-- <x-blocks.button :item="$item" /> --}}
{{-- <x-blocks.morebox :item="$item" /> --}}
{{-- <x-blocks.teaser :item="$item" /> --}}
{{-- <x-blocks.anchormenu :item="$item" /> --}}