
<div>
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
</div>