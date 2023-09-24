@props([
    'layout' => '',
    'blockid' => '',
    'children' => '',
    'set' => '',
])

@if ($layout == 'group')


<section class="popout">

@foreach ($children as $item)

<x-blocks.accordiongroup :layout="$item->slug" blockid="{{$item->id}}" :loop="$loop->iteration"/>

@endforeach


</section>

@endif
