@props([
    'layout' => '',
    'blockid' => '',
    'children' => '',
])

@if ($layout == 'group')


<section class="test">

@foreach ($children as $item)

<x-blocks.accordiongroup :layout="$item->slug" blockid="{{$item->id}}" :loop="$loop->iteration"/>

@endforeach


</section>

@endif
