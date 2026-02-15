@use('Illuminate\Support\Facades\Storage')
@use('Carbon\Carbon')
@use('Illuminate\Support\Str')

<section class="grid-set">

    @seo(['title' => 'Blog' . ' | ' . setting('global.webtitle' ?? 'Kompass')])
    {{-- @php
        seo()
            ->description($page->meta_description ?? setting('global.description' ?? ''))
            ->locale(str_replace('_', '-', app()->getLocale()))
            ->twitter()
            ->tag('og:image', asset(setting('global.ogimage_src')))
    @endphp --}}

    <div class="fullpage">



        <!-- Title -->
        <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
            <h2 class="text-2xl font-bold md:text-4xl md:leading-tight">Blog</h2>
            <p class="mt-1 text-gray-600">Stay in the know with insights from industry experts.</p>
        </div>
        <!-- End Title -->

        <!-- Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($this->posts as $post)
                <article class="relative group" wire:key="post-{{ $post->id }}">
                    <!-- Card -->
                    <div class="relative rounded-xl overflow-hidden hover:transform">

                        {{-- {!! getImageID($post->thumbnails,'thumbnail', 'object-cover w-full h-full rounded-lg shadow-md aspect-video transition-all duration-300 group-hover:scale-110') !!} --}}

                        <x-image :id="$post->thumbnails" size="thumbnail" class="object-cover w-full h-full rounded-lg shadow-md aspect-video transition-all duration-300 group-hover:scale-110" />
          
                        <span
                            class="absolute top-0 end-0 rounded-se-xl rounded-es-xl text-xs font-medium bg-gray-800 text-white py-1.5 px-3">
                            {{ Carbon::parse($post->updated_at)->format('d. F Y') }}
                        </span>
                    </div>

                        <h3 class="text-xl font-semibold text-gray-800 group-hover:text-gray-600 my-2">
                            {{ $post->title }}
                        </h3>
                        <p class="text-base!">{{ $post->meta_description }}</p>
                    <a class="inset-0 absolute z-10  focus:outline-hidden "
                        href="/{{ $route_prefix }}/{{ $post->slug }}"></a>

                </article>
            @endforeach
            <!-- End Card -->



          
            <!-- Card -->
            <a class="group relative flex flex-col w-full min-h-60 bg-[url('https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80')] bg-center bg-cover rounded-xl hover:shadow-lg focus:outline-hidden focus:shadow-lg transition"
                href="#">
                <div class="flex-auto p-4 md:p-6">
                    <h3 class="text-xl text-white/90 group-hover:text-white"><span class="font-bold">Preline</span>
                        Press publishes books about economic and technological advancement.</h3>
                </div>
                <div class="pt-0 p-4 md:p-6">
                    <div
                        class="inline-flex items-center gap-2 text-sm font-medium text-white group-hover:text-white/70 group-focus:text-white/70">
                        Visit the site
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </div>
            </a>
            <!-- End Card -->
        </div>
        <!-- End Grid -->


        {{-- Load More Bereich --}}
        <div class="flex items-center justify-center w-full pt-5">
            @if ($this->posts->count() < $total)
                <button wire:click="loadMore" wire:loading.attr="disabled"
                    class="inline-flex tracking-wide uppercase text-xs items-center justify-center px-5 py-2.5 font-semibold bg-gray-200 text-gray-600 hover:text-gray-800 dark:text-gray-100 dark:hover:text-white dark:bg-gray-800 border border-transparent rounded-md shadow-sm dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 dark:focus:ring-gray-900">
                    <span wire:loading.remove wire:target="loadMore">Load More Posts</span>
                    <span wire:loading wire:target="loadMore">Loading...</span>
                </button>
            @else
                <p class="text-sm text-gray-600">No more posts.</p>
            @endif
        </div>
    </div>
    </div>
</section>
