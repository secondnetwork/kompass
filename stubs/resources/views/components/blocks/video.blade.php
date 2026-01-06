@props([
    'item' => '',
])
@if ('video' == $item->type)
    <div {{ $attributes }}>

        @if (get_field('video', $item->datafield))
            <x-blocks.videoplayer :videourl="get_field('video', $item->datafield)" :poster="get_field('poster', $item->datafield)" />
        @endif

        @php
            $videoEmbed = videoEmbed(get_field('oembed', $item->datafield));
        @endphp

        @if ($videoEmbed)
            @php
                $poster = get_field('poster', $item->datafield);
                $assetExists = Storage::disk('public')->exists('thumbnails-video/' . $videoEmbed['id'] . '.jpg');

                if (!empty($poster)) {
                    $assetUrl = $poster;
                } else {
                    $assetUrl = Storage::disk('public')->url('thumbnails-video/' . $videoEmbed['id'] . '.jpg');
                }
            @endphp

            @if ($videoEmbed['type'] == 'youtube')
                <lite-youtube class="aspect-video" videoid="{{ $videoEmbed['id'] }}" params="rel=0"
                    @if ($assetExists) style="background-image: url('{{ $assetUrl }}');" @endif>
                </lite-youtube>
            @endif
            @if ($videoEmbed['type'] == 'vimeo')
                <lite-vimeo class="aspect-video" videoid="{{ $videoEmbed['id'] }}"></lite-vimeo>
            @endif

            @if ($videoEmbed['type'] == 'facebook')
                <lite-facebook class="aspect-video" videoid="{{ $videoEmbed['id'] }}"></lite-facebook>
            @endif

            @if ($videoEmbed['type'] == 'dailymotion')
                <lite-dailymotion class="aspect-video" videoid="{{ $videoEmbed['id'] }}"></lite-dailymotion>
            @endif

            @if ($videoEmbed['type'] == 'tiktok')
                <lite-tiktok class="aspect-video" videoid="{{ $videoEmbed['id'] }}"></lite-tiktok>
            @endif
        @endif

    </div>
@endif
