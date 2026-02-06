@props([
    'item' => '',
])

@if ('group' == $item->type)
    <div
        class="group grid md:grid gap-4 transition-all ease-in-out duration-500 grid-cols-{{ $item->layoutgrid }} {{ $item->getMeta('css-classname') ?? '' }} {{ $item->getMeta('layout') ?? '' }}  {{ $item->getMeta('alignment') }}">
        @foreach ($item->children as $child)
            @switch($child->type)
                @case('wysiwyg')
                    <x-blocks.wysiwyg 
                        :item="$child" 
                        class="col-span-{{ $child->layoutgrid }} {{ $child->getMeta('layout') ?? '' }} {{ $child->getMeta('alignment') }}"
                    />
                    @break
                @case('button')
                    <x-blocks.button :item="$child" />
                    @break
                @case('video')
                    <x-blocks.video 
                        :item="$child" 
                        class="col-span-{{ $child->layoutgrid }} {{ $child->getMeta('layout') ?? '' }} {{ $child->getMeta('alignment') }}"
                    />
                    @break
                @case('gallery')
                    <x-blocks.gallery 
                        :item="$child" 
                        class="col-span-{{ $child->grid }} {{ $child->getMeta('layout') ?? '' }} {{ $child->getMeta('alignment') }}"
                    />
                    @break
                @case('download')
                    <x-blocks.download :item="$child" />
                    @break
                @case('group')
                    <x-blocks.group :item="$child" />
                    @break
                @case('accordiongroup')
                    <x-blocks.accordiongroup :item="$child" />
                    @break
            @endswitch
        @endforeach
    </div>
@endif