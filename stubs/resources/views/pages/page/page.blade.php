<div>
    @if (! empty($page->slug))
        @php
            $webtitle = setting('global.webtitle', 'Kompass');
            $supline = setting('global.supline', 'A Laravel CMS');

            seo()
                ->title($page->layout === 'is_front_page'
                    ? $webtitle . ' | ' . $supline
                    : ($page->title ?? $webtitle) . ' | ' . $webtitle)
                ->description($page->meta_description ?? setting('global.description', ''))
                ->locale(str_replace('_', '-', app()->getLocale()))
                ->twitter();

            if ($ogImage = setting('global.ogimage_src')) {
                seo()->tag('og:image', asset($ogImage));
            }
        @endphp
    @endif

    @if ($page_frontNotFound)
        <section class="py-16 text-center">
            <h1>404</h1>
            <h2>{{ __('Front page not Found') }}</h2>
            <p class="font-bold">{{ __('Please create the Front page in the backend.') }}</p>
        </section>
    @else
        @foreach ($this->blocks as $item)
            @php
                $componentName = 'blocks.' . $item->type;
                $viewName = 'components.' . $componentName;
                $isGroup = $item->type === 'group';
            @endphp

            @if (view()->exists($viewName))
                <section class="{{ $item->getMeta('css-classname') }}" id="{{ $item->getMeta('id-anchor') }}">
                    @unless ($isGroup) <div class="{{ $item->getMeta('layout') }}"> @endunless
                        <x-dynamic-component :component="$componentName" :item="$item" :is-first="$loop->first" />
                    @unless ($isGroup) </div> @endunless
                </section>
            @elseif (app()->hasDebugModeEnabled())
                {{-- Component missing & we are in dev mode -> show a hint --}}
                <section class="fullpage border border-dashed border-red-500 bg-red-50 p-4 text-red-600 rounded">
                    <strong>Entwickler-Info:</strong><br>
                    Die Komponente <code>&lt;x-{{ $componentName }} /&gt;</code> wurde nicht gefunden.<br>
                </section>
            @endif
        @endforeach
    @endif
</div>
