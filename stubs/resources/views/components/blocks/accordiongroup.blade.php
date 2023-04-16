@props([
    'layout' => '',
    'blockid' => '',
    'loop' => '',
])

@if ('accordion' == $layout)
    <section>




        <div x-data="accordion({{$blockid}})">
            <div-nav-action :class="handleAc()" class="flex items-center justify-between border-b border-gray-200 px-4 ">
                <span class="flex items-center py-6 w-full ">
            <h2 @click="handleClick()" >{{$this->get_field('titel',$blockid)}}</h2>

            </span>
            {{-- <svg
            :class="handleRotate()"
            class="fill-current text-purple-700 h-6 w-6 transform transition-transform duration-500"
            viewBox="0 0 20 20"
          >
            <path d="M13.962,8.885l-3.736,3.739c-0.086,0.086-0.201,0.13-0.314,0.13S9.686,12.71,9.6,12.624l-3.562-3.56C5.863,8.892,5.863,8.611,6.036,8.438c0.175-0.173,0.454-0.173,0.626,0l3.25,3.247l3.426-3.424c0.173-0.172,0.451-0.172,0.624,0C14.137,8.434,14.137,8.712,13.962,8.885 M18.406,10c0,4.644-3.763,8.406-8.406,8.406S1.594,14.644,1.594,10S5.356,1.594,10,1.594S18.406,5.356,18.406,10 M17.521,10c0-4.148-3.373-7.521-7.521-7.521c-4.148,0-7.521,3.374-7.521,7.521c0,4.147,3.374,7.521,7.521,7.521C14.148,17.521,17.521,14.147,17.521,10"></path>
          </svg> --}}
          <div :class="handleRotate()"  class="transform transition-transform duration-500">
          <x-tabler-chevron-down @click="handleClick()" class="cursor-pointer stroke-current h-8 w-8 text-gray-900 " />
        </div>
            {{-- <div class="flex items-center gap-2">
                <span :class="!expanded ? '' : 'hidden'" @click="expanded = ! expanded">
                    <x-tabler-chevron-down class="cursor-pointer stroke-current h-8 w-8 text-gray-900" />
                </span>
                <span :class="expanded ? '' : 'hidden'" @click="expanded = ! expanded">
                    <x-tabler-chevron-up class="cursor-pointer stroke-current h-8 w-8 text-gray-900" />
                </span>

            </div> --}}

            </div-nav-action>

            <div  x-ref="tab" :style="handleToggle()"  class="bg-white  overflow-hidden max-h-0 duration-500 transition-all">

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
            <div class="p-8">
                <span>{!!$this->get_field('text',$blockid)!!}</span>
            </div>
            </div>
        </div>
    </section>
@endif
