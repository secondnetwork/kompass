@props([
    'item' => '',
])

@use('Secondnetwork\Kompass\Models\File' , 'Files')

@if ('download' == $item->type)
<div {{ $attributes }}>

        <div class="">
        @foreach ($item->datafield as $image)

        @php
                            $image = $image['data'];
        
                $file = Cache::rememberForever('kompass_imgId_'.$image, function () use ($image) {
                    return files::where('id', $image)->first();
                });
        @endphp

        <div class="bg-white p-6 mb-4 md:ml-16 rounded-lg text-black flex justify-between ">
            {{ $file->name }}
            <a class="flex items-center gap-4 text-black font-bold" download="" href="{{ asset('storage'.$file->path.'/'.$file->slug.'.'.$file->extension) }}">Download <x-tabler-download class="text-[var(--primary)]" /></a>
        </div>

        @endforeach
    </div>

    <div class="max-lg:hidden flex place-content-center -mt-20">
        <img src="{{ asset('images/download.svg') }}" alt="download">

    </div>
</div>

@endif
