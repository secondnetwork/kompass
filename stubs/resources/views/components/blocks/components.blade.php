@props([
    'item' => ''
])



@switch($item->type)
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
        
@endswitch


{{-- <x-blocks.highlights :item="$item" /> --}}
{{-- <x-blocks.content-box :item="$item" /> --}}
{{-- <x-blocks.hero :item="$item" /> --}}
{{-- <x-blocks.heroboxs :item="$item" /> --}}
{{-- <x-blocks.button :item="$item" /> --}}
{{-- <x-blocks.morebox :item="$item" /> --}}
{{-- <x-blocks.teaser :item="$item" /> --}}
{{-- <x-blocks.anchormenu :item="$item" /> --}}