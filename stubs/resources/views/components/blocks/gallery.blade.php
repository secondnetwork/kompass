@props([
    'item' => '',
])

@use('Secondnetwork\Kompass\Models\File', 'Files')

@if ('gallery' == $item->type)

<div {{ $attributes }} >
    <div  class="grid md:grid gap-4 transition-all ease-in-out duration-500  grid-cols-{{ $item->grid }}  one-image {{ $item->getMeta('css-classname') }}">

        @foreach ($item->datafield as $image)

                @php

                    $imageId = $image['data'];

                    $file = Cache::rememberForever('kompass_imgId_' . $imageId, function () use ($imageId) {
                        return Files::where('id',$imageId)->first(); // Use find instead of where()->first() for better performance
                    });
                @endphp

                @if ($file)

                    @php
                    $dirpath = $file->path ? $file->path . '/' : '';
                    $imageUrl = Storage::url($dirpath . $file->slug . '.' . $file->extension);
                    $avifUrl = function_exists('imageToAvif') ? imageToAvif($imageUrl) : null;
                    $webpUrl = function_exists('imageToWebp') ? imageToWebp($imageUrl) : null;
                    @endphp

                    <picture>
                        @if ($avifUrl)
                        <source type="image/avif" srcset="{{ $avifUrl }}">
                        @endif
                        @if ($webpUrl)
                        <source type="image/webp" srcset="{{ $webpUrl }}">
                        @endif
                        <img loading="lazy" src="{{ Storage::url($dirpath . $file->slug . '.' . $file->extension) }}"
                            alt="{{ $file->alt }}" />
                        @if ($file->description)
                            <span class="block mt-4 text-xl font-semibold">{{ $file->description }}</span>
                        @endif
                    </picture>
                @endif

        @endforeach
    </div>
</div>
@endif
