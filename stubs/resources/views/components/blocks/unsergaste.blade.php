
@props([
    'layout' => '',
    'blockid' => '',
])

@if ('unsere-gaste' == $layout)
<section class="text">

    <div class="grid-1-4">
        <div>{!!$this->get_field('bild',$blockid,'medium')!!}</div>
        <div>{!!$this->get_field('text',$blockid,'','')!!}</div>
    </div>

</section>
@endif
