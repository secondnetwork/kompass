@props(['record', 'url' => null, 'modelKey' => null])

@if (($record->status ?? null) === 'published')
{{-- Blog post card: thumbnail + title --}}
<article class="card bg-base-100 overflow-hidden">
    @if ($record->thumbnails)
        <a href="{{ $url ?? '#' }}" class="block aspect-[16/9] overflow-hidden">
            <x-image :id="$record->thumbnails" class="w-full h-full object-cover transition-transform hover:scale-105" />
        </a>
    @endif

    <div class="card-body p-4">
        @if ($record->category)
            <span class="badge badge-sm badge-{{ $record->category->color ?? 'neutral' }} gap-1">
                @if ($record->category->icon)
                    <x-dynamic-component :component="$record->category->icon" class="size-3" />
                @endif
                {{ $record->category->name }}
            </span>
        @endif

        <h3 class="card-title text-base">
            @if ($url)
                <a href="{{ $url }}" class="hover:underline">{{ $record->title }}</a>
            @else
                {{ $record->title }}
            @endif
        </h3>

        @if ($record->meta_description)
            <p class="text-sm text-base-content/70 line-clamp-2">{{ $record->meta_description }}</p>
        @endif

        <span class="text-xs text-base-content/50">{{ $record->created_at }}</span>
    </div>
</article>
@endif
