@props([
    'item' => ''
])
{{-- @dump($item->toArray())

       {{ $item->type }} --}}
            @php
                // Der Name der Komponente, z.B. "blocks.hero"
                $componentName = 'blocks.' . $item->type;

                // Der Pfad zur View, z.B. "components.blocks.hero"
                $viewName = 'components.' . $componentName;
            @endphp

            @if (view()->exists($viewName))
                {{-- Komponente existiert -> Rendern --}}
                    <x-dynamic-component :component="$componentName" :item="$item" />
      
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