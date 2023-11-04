@props([
    'layout' => '',
    'blockid' => '',
    'children' => '',
    'set' => '',

])

@if ($layout == 'group')




<section class="popout">

@foreach ($children as $item)

<x-blocks.longtext :layout="$item->type" blockid="{{$item->id}}"  :loop="$loop->iteration"/>

@endforeach

</section>

@endif
