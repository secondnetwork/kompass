@props([
    'id' => null,
    'lightbox' => false,
])

@php
    use Illuminate\Support\Facades\Cache;
    use Secondnetwork\Kompass\Models\File;

    $file = $id
        ? Cache::rememberForever('kompass_file_' . $id, fn () => File::find($id))
        : null;

    $ext = strtolower($file->extension ?? '');
    $isImage = $file && in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg']);

    // Path-safe URL (avoids a double slash when the file has no sub-path).
    $path = $file && $file->path ? $file->path . '/' : '';
    $fileUrl = $file ? asset('storage/' . $path . $file->slug . '.' . $file->extension) : null;
@endphp

@if (! $file)
    {{-- file missing: render nothing --}}
@elseif ($isImage && $lightbox)
    {{-- image opens in the gallery lightbox on click --}}
    <button type="button" @click="$dispatch('open-lightbox', '{{ $fileUrl }}')"
        {{ $attributes->merge(['class' => 'block cursor-zoom-in']) }}>
        <x-image :id="$id" class="w-full h-full rounded-lg object-cover" />
    </button>
@elseif ($isImage)
    {{-- images keep the normal <img> rendering --}}
    <x-image :id="$id" {{ $attributes }} />
@else
    {{-- non-image (PDF, doc, sheet, archive, …): full-width download row --}}
    <a href="{{ $fileUrl }}" download target="_blank" rel="noopener"
        {{ $attributes->merge(['class' => 'col-span-full flex items-center justify-between gap-4 bg-white p-6 rounded-lg border border-base-300 text-black hover:shadow-md transition']) }}>
        <span class="min-w-0 break-words">{{ $file->name }}</span>
        <span class="flex items-center gap-4 font-bold whitespace-nowrap shrink-0">
            {{ __('Download') }}
            <x-tabler-download class="text-[var(--primary)]" />
        </span>
    </a>
@endif
