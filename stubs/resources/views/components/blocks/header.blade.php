@props([
    'layout' => '',
    'blockid' => '',
])

@if ('header' == $layout)
    <section>

            {!! $this->get_field('bild', $blockid, Null) !!}

    </section>
    <section class="prose prose-p:m-0 max-w-none">

            {!! $this->get_field('text', $blockid, '') !!}

    </section>
@endif
