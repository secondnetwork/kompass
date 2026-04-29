@props([
    'item' => '',
    'isFirst' => false
])
            @php
                $componentName = 'blocks.' . $item->type;
                $viewName = 'components.' . $componentName;
            @endphp

            @if (view()->exists($viewName))
                <x-dynamic-component :component="$componentName" :item="$item" :is-first="$isFirst" />
            @elseif (app()->hasDebugModeEnabled())
                <section class="fullpage border border-dashed border-red-500 bg-red-50 p-4 text-red-600 rounded">
                    <strong>Developer info:</strong><br>
                    Component <code>&lt;x-{{ $componentName }} /&gt;</code> not found.<br>
                </section>
            @else
                <!-- Block {{ $item->type }} could not be loaded -->
            @endif