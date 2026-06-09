@props(['item' => ''])

@php
    $cssclassname = get_meta($item, 'css-classname', '');
    $modelKey     = get_meta($item, 'query-model', '');
    $models       = query_models();
    $selected     = $models[$modelKey] ?? null;
    $records      = $selected ? kompass_query($item) : collect();
    $labelField   = $selected['label_field'] ?? 'title';
    $itemView     = $selected['item_view'] ?? null;
    $wrapper      = $selected['wrapper_class'] ?? 'grid gap-4';
    $hasItemView  = $itemView && view()->exists('components.' . $itemView);
@endphp

@if ($records->isNotEmpty())
    <div class="{{ $cssclassname }}">
        <div class="{{ $wrapper }}">
            @foreach ($records as $record)
                @php $url = kompass_query_url($modelKey, $record); @endphp
                @if ($hasItemView)
                    <x-dynamic-component :component="$itemView" :record="$record" :url="$url" :model-key="$modelKey"
                        wire:key="rel-{{ $item->id }}-{{ $record->id }}" />
                @else
                    {{-- Fallback: plain title link --}}
                    <div wire:key="rel-{{ $item->id }}-{{ $record->id }}">
                        @if ($url)
                            <a href="{{ $url }}" class="block hover:underline">{{ $record->{$labelField} ?? ('#' . $record->id) }}</a>
                        @else
                            {{ $record->{$labelField} ?? ('#' . $record->id) }}
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif
