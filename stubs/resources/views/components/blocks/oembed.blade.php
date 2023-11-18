
@props([
    'set' => '',
    'layout' => '',
    'blockid' => '',
])
@if ('video' == $layout)
<section class="{{$set->layout ?? ''}} prose m-0 max-w-none prose-p:m-0 {{$set->alignment ?? ''}}">


    @if ($this->get_field('video',$blockid))

    @php
    $video_array = parse_video_id($this->get_field('video',$blockid));
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
