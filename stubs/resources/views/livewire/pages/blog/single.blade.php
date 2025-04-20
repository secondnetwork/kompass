
<div>
    <section>

        <h2>{{ $post->title }}</h2>
        {{ $post->created_at }}
        @if ($post->thumbnails)
       
                            @php
                            $file = Cache::rememberForever('kompass_imgId_' . $post->thumbnails, function () use ($post) {
                                return Files::where('id', $post->thumbnails)->first(); // Use find instead of where()->first() for better performance
                            });
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
             
                        @else
                            <div class="flex items-center justify-center w-full text-gray-500 bg-gray-200 dark:bg-gray-800 aspect-video">
                                <svg class="w-10 h-10 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            </div>
                        @endif
        @foreach ($this->blocks as $key => $item)

        <x-blocks.longtext :item="$item" class="prose m-0 max-w-none  {{ $item->set->layout ?? '' }}"/>
            <x-blocks.oembed :item="$item" class="{{ $item->set->layout ?? '' }}"/>
        {{-- <x-blocks.oembed layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
        <x-blocks.anmeldemaske layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
        <x-blocks.unsergaste layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
        <x-blocks.ueberschrift layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
        <x-blocks.accordion layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>
        <x-blocks.gallery layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>

        <x-blocks.tables layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set"/>


        <x-blocks.group layout="{{$item->type}}" blockid="{{$item->id}}" :set="$item->set" :children="$item['children']->where('status', 'published')->sortBy('order')" /> --}}

    @endforeach

    </section>
</div>


    {{-- <article class="relative w-full h-auto mx-auto prose-sm prose md:prose-2xl dark:prose-invert">
        <div class="py-6 mx-auto heading md:py-12 lg:w-full md:text-center">

            <div class="flex flex-col items-center justify-center mt-4 mb-0">
                <h1 class="!mb-0 font-sans text-4xl font-bold heading md:text-6xl dark:text-white md:leading-tight">
                    {{ $post->title }}
                </h1>
            </div>

            <div class="flex items-center justify-center">
                <div class="ml-2">
                    <p class="text-sm text-gray-600 dark:text-gray-500">Posted on {{ $post->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            @if ($post->image)
                <img src="@if(str_starts_with($post->image, 'https') || str_starts_with($post->image, 'http')){{ $post->image }}@else{{ asset('storage/' . $post->image) }}@endif" alt="{{ $post->title }}" class="w-full mx-auto mt-4">
            @endif

            <div class="flex items-center justify-center mt-4 text-left">
                <div class="max-w-full -mt-5 text-lg text-gray-600 whitespace-pre-line dark:text-gray-300">
                    {!! $post->body !!}
                </div>
            </div>
        </div>
    </article> --}}

