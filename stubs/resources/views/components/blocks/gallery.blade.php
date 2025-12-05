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
                @endphp
                
            <x-image :id="$imageId" class="object-cover w-full h-full rounded-lg shadow-md" />



        @endforeach
    </div>
</div>
@endif
