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

            @if ($item->children->isNotEmpty() && !in_array($item->type, ['group', 'accordiongroup']))
                @php
                    ['gridCols' => $childGridCols] = block_grid_classes($item);
                @endphp
                <div class="{{ $childGridCols ? 'md:grid gap-4 ' . $childGridCols : '' }}">
                    @foreach ($item->children->sortBy('order') as $child)
                        @php
                            ['colSpan' => $childColSpan] = block_grid_classes($child);
                        @endphp
                        <div class="{{ $childColSpan }}">
                            <x-blocks.components :item="$child" />
                        </div>
                    @endforeach
                </div>
            @endif