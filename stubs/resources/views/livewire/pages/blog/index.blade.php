<?php

use Secondnetwork\Kompass\Models\Post;

use function Livewire\Volt\layout;
use function Livewire\Volt\mount;
use function Livewire\Volt\state;
use function Livewire\Volt\usesPagination;
use function Livewire\Volt\with;

layout('layouts.main');

usesPagination();

state([
    'numResults' => 6,
    'results' => null,
    'total' => '',
    'finished' => false,
    'route_prefix' => 'blog',
    'sortOrder' => 'desc',
]);

mount(function () {
    $this->results = $this->numResults;
    $this->total = Post::count();
});

$loadMore = function () {
    $this->results += $this->numResults;
    if ($this->results >= $this->total) {
        $this->finished = true;
    }
};

with(fn () => [
    'posts' => Post::query()
        ->where('status', 'published')
        ->orderBy('created_at', $this->sortOrder)
        ->paginate($this->results),
]);

?>



<section>

    <div class="relative flex flex-col w-full px-6 py-10 mx-auto lg:max-w-6xl sm:max-w-xl md:max-w-full sm:pb-16">

        <h1 class="text-2xl font-bold tracking-tighter leading-tighter  font-heading md:text-3xl">Welcome to new blog</h1>
        <p class="w-full mt-2 text-base font-medium text-neutral-400  md:mt-2">Find out all the latest news around A.I. We stay up-to-date with the latest technologies and AI news so you don't have to.</p>

        <div class="relative w-full mt-10 space-y-10">
            @foreach ($posts as $post)




                <article class="flex space-x-8">
                    <a href="/{{ $this->route_prefix }}/{{ $post->slug }}" class="block w-5/12">
                        {{ $post->title }}
                        @if ($post->thumbnails)
                        {!! get_thumbnails($post->thumbnails,'relative object-cover w-full h-full bg-gray-200 rounded-lg shadow-lg aspect-video dark:bg-slate-700') !!}
                        @else
                            <div class="flex items-center justify-center w-full text-gray-500 bg-gray-200 dark:bg-gray-800 aspect-video">
                                <svg class="w-10 h-10 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            </div>
                        @endif
                    </a>
                    <div class="w-7/12 mt-2">
                        <div class="mb-1 text-sm">
                            <svg class="dark:text-gray-400 -mt-0.5 h-3.5 inline-block w-3.5" data-icon="tabler:clock" height="1em" viewBox="0 0 24 24" width="1em"><symbol id="ai:tabler:clock"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0-18 0"></path><path d="M12 7v5l3 3"></path></g></symbol><use xlink:href="#ai:tabler:clock"></use></svg>
                            <time class="inline-block dark:text-gray-400">{{ Carbon\Carbon::parse($post->updated_at)->format('d. F Y') }}</time><span class="mx-2">·</span><a class="font-medium underline capitalize hover:underline dark:text-gray-400" href="/category/documentation"></a>
                        </div>
                        <h2 class="mb-2 text-xl font-medium leading-tight underline dark:text-slate-300 font-heading sm:text-2xl"><a class="transition duration-200 ease-in dark:hover:text-blue-700 hover:text-primary" href="/{{ $this->route_prefix }}/{{ $post->slug }}">{{ $post->title }}</a></h2>
                        <p class="flex-grow text-lg font-light text-muted dark:text-slate-400">{{ substr(strip_tags($post->body), 0, 200) }}@if(strlen(strip_tags($post->body)) > 200){{ '...' }}@endif</p>
          
                    </div>
                </article>
            @endforeach

            <div class="flex items-center justify-center w-full pt-5">
                @if (!$finished)
                    <button wire:click="loadMore" class="inline-flex tracking-wide uppercase text-xs items-center justify-center px-5 py-2.5 font-semibold bg-gray-200 text-gray-600 hover:text-gray-800 dark:text-gray-100 dark:hover:text-white dark:bg-gray-800 border border-transparent rounded-md shadow-sm dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 dark:focus:ring-gray-900">Load More Posts</button>
                @else
                    <p class="text-sm text-gray-600">No more posts.</p>
                @endif
            </div>
        </div>

    </div>

</section>



