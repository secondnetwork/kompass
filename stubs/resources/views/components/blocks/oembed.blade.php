@props([
    'item' => '',
])
@if ('video' == $item->type)
    <div {{ $attributes }}>

        @if (get_field('video',$item->datafield))
        <x-blocks.videoplayer :videourl="get_field('video', $item->datafield)" :poster="get_field('poster', $item->datafield)" />
        @endif

        @php
        $videoEmbed = videoEmbed(get_field('oembed', $item->datafield));
        @endphp

            @if ($videoEmbed)
            @php

            $assetExists = Storage::disk('public')->exists('thumbnails-video/'.$videoEmbed['id'].'.jpg');
            $assetUrl = Storage::disk('public')->url('thumbnails-video/'.$videoEmbed['id'].'.jpg');
            @endphp
                @if ($videoEmbed['type'] == 'youtube')
                <lite-youtube class="aspect-video" videoid="{{ $videoEmbed['id'] }}" params="rel=0" @if($assetExists) style="background-image: url('{{ $assetUrl }}');" @endif>
                </lite-youtube>
                @endif
                @if ($videoEmbed['type'] == 'vimeo')
                <lite-vimeo class="aspect-video" videoid="{{ $videoEmbed['id'] }}"></lite-vimeo>
                @endif
            @endif

    </div>
@endif
