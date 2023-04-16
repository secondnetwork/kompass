@props([
    'set' => '',
    'layout' => '',
    'blockid' => '',
])

@if ('longtext' == $layout)
<section class="{{$set->layout ?? ''}} prose m-0 max-w-none prose-p:m-0 {{$set->alignment ?? ''}}">

{!!$this->get_field('longtext',$blockid)!!}

</section>
@endif