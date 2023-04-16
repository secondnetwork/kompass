
@props([
    'layout' => '',
    'blockid' => '',
])
@if ('uberschrift' == $layout)
<section class="">

   <h1>{{$this->get_field('uberschrift',$blockid)}}</h1>

</section>
@endif
