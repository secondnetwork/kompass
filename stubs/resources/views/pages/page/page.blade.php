<div>
    @if (!empty($page->slug))
        @if ('is_front_page' !== str_replace('.', '-', Route::currentRouteName()))
            @section('slug', $page->slug)
            @seo(['title' => $page->title . ' | ' . setting('global.webtitle' ?? 'Kompass')])
        @else
            @php
                seo()->title(
                    default: setting('global.webtitle' ?? 'Kompass') .
                        ' | ' .
                        setting('global.supline' ?? 'A Laravel CMS'),
                    modify: fn(string $title) => $title .
                        ' | ' .
                        setting('global.webtitle' ?? 'Kompass') .
                        ' | ' .
                        setting('global.supline' ?? 'A Laravel CMS'),
                );
            @endphp
        @endif

        @php
            seo()
                ->description($page->meta_description ?? setting('global.description' ?? ''))
                ->locale(str_replace('_', '-', app()->getLocale()))
                ->twitter()
                ->tag('og:image', asset(setting('global.ogimage_src')))
                ->twitter();
        @endphp
    @endif
    @if ($page_frontNotFound)

        <section class="py-16 text-center">
            <h1>404</h1>
            <h2>{{ __('Front page not Found') }}</h2>
            <p class="font-bold">{{ __('Please create the Front page in the backend.') }}</p>

        </section>
    @else
        @foreach ($this->blocks as $key => $item)
            @php
                // Der Name der Komponente, z.B. "blocks.hero"
                $componentName = 'blocks.' . $item->type;

                // Der Pfad zur View, z.B. "components.blocks.hero"
                $viewName = 'components.' . $componentName;
            @endphp

            @if (view()->exists($viewName))
                {{-- Komponente existiert -> Rendern --}}
                <section class="{{ $item->getMeta('layout') ?? 'fullpage' }} {{ $item->getMeta('css-classname') }}"
                    id="{{ $item->getMeta('id-anchor') }}">
                    <x-dynamic-component :component="$componentName" :item="$item" />
                </section>
            @elseif (app()->hasDebugModeEnabled())
                {{-- Komponente fehlt & wir sind im Dev-Modus -> Fehler anzeigen --}}
                <section class="fullpage border border-dashed border-red-500 bg-red-50 p-4 text-red-600 rounded">
                    <strong>Entwickler-Info:</strong><br>
                    Die Komponente <code>&lt;x-{{ $componentName }} /&gt;</code> wurde nicht gefunden.<br>
                </section>
            @else
                {{-- Produktion -> Einfach nichts anzeigen (Fallback) --}}
                <!-- Block {{ $item->type }} konnte nicht geladen werden -->
            @endif
        @endforeach

    @endif
</div>
