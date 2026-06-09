@props(['record', 'url' => null, 'modelKey' => null])

<div>
    @if ($url)
        <a href="{{ $url }}" class="text-lg font-medium hover:underline">{{ $record->title }}</a>
    @else
        <span class="text-lg font-medium">{{ $record->title }}</span>
    @endif
</div>
