<div>
    @if ('is_front_page' !== str_replace(".","-", Route::currentRouteName()))
        @section('slug',$page->slug )
        @seo(['title' => $page->title. ' | '. config('kompass.settings.webtitle'),])
    @else
        @php
        seo()->title(
            default:  config('kompass.settings.webtitle'). ' | ' . config('kompass.settings.supline'),
            modify: fn (string $title) => $title . ' | ' .  config('kompass.settings.webtitle'). ' | ' . config('kompass.settings.supline')
        );
        @endphp
    @endif

    @php
    seo()
        ->description($page->meta_description ?? config('kompass.settings.description' ?? ''))
        ->locale('de_DE')
        ->twitter()
        ->tag('og:image', asset(config('kompass.settings.image_src')))->twitter();
    @endphp



    @foreach ($this->blocks as $key => $item)

    <section class="py-16 {{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('css-classname') }}" id="{{ $item->getMeta('id-anchor') }}">
        <x-blocks.components :item="$item" />
    </section>

    @endforeach

</div>