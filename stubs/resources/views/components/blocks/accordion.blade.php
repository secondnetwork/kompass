@props([
    'layout' => '',
    'blockid' => '',

])

@if ('accordion' == $layout)
    <section>
        <div x-data="{ expanded: false }">
            <div-nav-action class="flex items-center justify-between border-b border-gray-200 px-4">
                <span class="flex items-center py-6 w-full ">
            <h2>{{$this->get_field('titel',$blockid)}}</h2>

            </span>


            <div class="flex items-center gap-2">
                <span :class="!expanded ? '' : 'rotate-180'" @click="expanded = ! expanded" class="transform transition-transform duration-500">
                    <x-tabler-chevron-down class="cursor-pointer stroke-current h-8 w-8 text-gray-900 " />
                </span>
            </div>

            </div-nav-action>

            <div x-show="expanded" x-collapse class="grid gap-6 p-6">

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

                <span>{!!$this->get_field('text',$blockid)!!}</span>

            </div>

        </div>
    </section>
@endif
