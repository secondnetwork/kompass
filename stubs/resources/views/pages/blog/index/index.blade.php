@use('Illuminate\Support\Facades\Storage')
@use('Carbon\Carbon')
@use('Illuminate\Support\Str')

<section class="grid-set">
    <div class="fullpage relative flex flex-col w-full px-6 py-10 mx-auto lg:max-w-6xl sm:max-w-xl md:max-w-full sm:pb-16">

        <h1 class="text-2xl font-bold tracking-tighter leading-tighter font-heading md:text-3xl">
            Welcome to new blog
        </h1>
        <p class="w-full mt-2 text-base font-medium text-neutral-400 md:mt-2">
            Find out all the latest news around A.I. We stay up-to-date with the latest technologies and AI news so you don't have to.
        </p>

        <div class="relative w-full mt-10 space-y-10">
            {{-- Zugriff auf die Computed Property $this->posts --}}
            @foreach ($this->posts as $post)
                <article class="flex space-x-8" wire:key="post-{{ $post->id }}">
                    <a href="/{{ $route_prefix }}/{{ $post->slug }}" class="block w-5/12">
               
                        @php
                            // Wir rufen die Hilfsmethode der Komponente auf
                            $file = $this->getPostImage($post->thumbnails);
                        @endphp

                        @if ($file)
                            @php
                                $dirpath = $file->path ? $file->path . '/' : '';
                                $imageUrl = Storage::url($dirpath . $file->slug . '.' . $file->extension);
                                // Checks für Helper-Funktionen
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
                                <img loading="lazy" 
                                     src="{{ $imageUrl }}"
                                     alt="{{ $file->alt ?? $post->title }}" 
                                     class="object-cover w-full h-full rounded-lg shadow-md aspect-video" 
                                />
                            </picture>
                        @else
                            {{-- Placeholder wenn kein Bild da ist --}}
                            <div class="flex items-center justify-center w-full text-gray-500 bg-gray-200 dark:bg-gray-800 aspect-video rounded-lg">
                                <svg class="w-10 h-10 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </div>
                        @endif
                    </a>

                    <div class="w-7/12 mt-2">
                        <div class="mb-1 text-sm">
                            <svg class="dark:text-gray-400 -mt-0.5 h-3.5 inline-block w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <time class="inline-block dark:text-gray-400">
                                {{ Carbon::parse($post->updated_at)->format('d. F Y') }}
                            </time>
                        </div>
                        
                        <h2 class="mb-2 text-xl font-medium leading-tight underline dark:text-slate-300 font-heading sm:text-2xl">
                            <a class="transition duration-200 ease-in dark:hover:text-blue-700 hover:text-primary" href="/{{ $route_prefix }}/{{ $post->slug }}">
                                {{ $post->title }}
                            </a>
                        </h2>
                        
                        <p class="flex-grow text-lg font-light text-muted dark:text-slate-400">
                            {{ Str::limit(strip_tags($post->body), 200, '...') }}
                        </p>
                    </div>
                </article>
            @endforeach

            {{-- Load More Bereich --}}
            <div class="flex items-center justify-center w-full pt-5">
                @if ($this->posts->count() < $total)
                    <button wire:click="loadMore" wire:loading.attr="disabled" class="inline-flex tracking-wide uppercase text-xs items-center justify-center px-5 py-2.5 font-semibold bg-gray-200 text-gray-600 hover:text-gray-800 dark:text-gray-100 dark:hover:text-white dark:bg-gray-800 border border-transparent rounded-md shadow-sm dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 dark:focus:ring-gray-900">
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