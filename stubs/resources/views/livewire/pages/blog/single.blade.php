
<div>
    <section>

        <h2>{{ $post->title }}</h2>
        {{ $post->created_at }}
        {!! get_thumbnails($post->thumbnails) !!}
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

