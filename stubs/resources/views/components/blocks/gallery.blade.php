
@props([
    'set' => '',
    'layout' => '',
    'blockid' => '',
])
@if ('gallery' == $layout)
<section class="{{$set->layout ?? ''}} full prose m-0 max-w-none prose-p:m-0 {{$set->alignment ?? ''}}">

<div class="relative flex flex-col justify-center overflow-hidden ">

  <div class="pointer-events-none relative flex gap-5 overflow-hidden">
    <div class="animate-marquee flex min-w-full shrink-0 items-center justify-around gap-5 @if ($set->alignment == 'left') [animation-direction:reverse] @endif ">
        {!!$this->get_gallery($blockid)!!}
    </div>
    <div aria-hidden="true" class="animate-marquee flex min-w-full shrink-0 items-center justify-around gap-5 @if ($set->alignment == 'left') [animation-direction:reverse] @endif ">
        {!!$this->get_gallery($blockid)!!}
    </div>
  </div>
</div>

</section>
@endif
