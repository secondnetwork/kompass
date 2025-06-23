<div>
    @if ('is_front_page' !== str_replace(".","-", Route::currentRouteName()))
        @section('slug',$page->slug )
        @seo(['title' => $page->title. ' | '. setting('global.webtitle' ?? 'Kompass'),])
    @else
        @php
        seo()->title(
            default:  setting('global.webtitle' ?? 'Kompass'). ' | ' . setting('global.supline' ?? 'A Laravel CMS'),
            modify: fn (string $title) => $title . ' | ' .  setting('global.webtitle' ?? 'Kompass'). ' | ' . setting('global.supline' ?? 'A Laravel CMS')
        );
        @endphp
    @endif

    @php
    seo()
        ->description($page->meta_description ?? setting('global.description' ?? ''))
        ->locale(str_replace('_', '-', app()->getLocale()) )
        ->twitter()
        ->tag('og:image', asset(setting('global.ogimage_src')))->twitter();
    @endphp

@if ($page_frontNotFound)

<section class="py-16 text-center">
    <h1>404</h1>
    <h2>{{ __('Front page not Found') }}</h2>
    <p class="font-bold">{{ __('Please create the Front page in the backend.') }}</p>

</div>

@else

    @foreach ($this->blocks as $key => $item)

    <section class="py-16 {{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('css-classname') }}" id="{{ $item->getMeta('id-anchor') }}">
        <x-blocks.components :item="$item" />
    </section>

    @endforeach
@endif
</div>