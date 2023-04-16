
@props([
    'layout' => '',
    'blockid' => '',
])
@if ('video' == $layout)
<section class="">


    @if ($this->get_field('video',$blockid))

    @php
    $video_array = parse_video_uri($this->get_field('video',$blockid));
    @endphp

    @if ($video_array['type'] == 'youtube')
        <lite-youtube videoid="{{ $video_array['id'] }}" playlabel=""></lite-youtube>
    @endif

    @if ($video_array['type'] == 'vimeo')
        <lite-vimeo videoid="{{ $video_array['id'] }}"></lite-vimeo>
    @endif
    @endif


</section>
@endif
