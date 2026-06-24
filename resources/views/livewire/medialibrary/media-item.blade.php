<div>
    @if ($file->type == 'folder')
        <div class="flex items-center gap-3 p-3 rounded-lg border border-base-300 bg-base-100 hover:bg-base-200 hover:border-primary/40 hover:shadow-sm cursor-pointer transition-all group"
            wire:click="goToFolder">
            <div class="shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                <x-tabler-folder stroke-width="1.5" class="size-5 md:size-6 text-primary group-hover:scale-110 transition-transform duration-200" />
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-sm font-semibold truncate" title="{{ $file->name }}">{{ $file->name }}</div>
                <div class="text-[10px] opacity-40">{{ $file->created_at->format('d.m.Y') }}</div>
            </div>
            <button wire:click.stop="select"
                class="opacity-0 group-hover:opacity-100 btn btn-ghost btn-xs btn-circle transition-opacity"
                title="{{ __('Edit Meta') }}">
                <x-tabler-edit class="w-4 h-4" />
            </button>
        </div>
    @else
    <div class="group relative overflow-hidden rounded-lg border border-base-300 bg-base-100 transition-all duration-200 hover:border-primary/50 hover:shadow-md"
        x-data="{ checked: false }"
        @media-selection-changed.window="checked = $event.detail.ids.includes({{ $file->id }})"
        :class="checked && 'ring-2 ring-primary border-primary'">
        <figure class="relative aspect-video overflow-hidden bg-base-200 m-0">
            @if ($file->type == 'video')
                <div class="flex items-center justify-center w-full h-full">
                    <x-tabler-video stroke-width="1.5"
                        class="w-14 h-14 opacity-30 group-hover:scale-110 transition-transform duration-300" />
                </div>
            @elseif ($file->type == 'document')
                <div class="flex items-center justify-center w-full h-full">
                    <x-tabler-file-text stroke-width="1.5"
                        class="w-14 h-14 opacity-30 group-hover:scale-110 transition-transform duration-300" />
                </div>

            @elseif ($file->type == 'image')

                @php
                    $imgpath = Storage::url(
                        ($file->path ? $file->path . '/' : '') . $file->slug . '.' . $file->extension,
                    );
                @endphp

                <picture>
                    <source srcset="{{ imageToAvif($imgpath, 500) }}" type="image/avif">
                    <source srcset="{{ imageToWebp($imgpath, 500) }}" type="image/webp">
                    <img src="{{ asset($imgpath) }}"
                        class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300"
                        loading="lazy">
                </picture>
            @else
                <div class="flex items-center justify-center w-full h-full">
                    <x-tabler-file stroke-width="1.5"
                        class="w-14 h-14 opacity-30 group-hover:scale-110 transition-transform duration-300" />
                </div>
            @endif

            {{-- Hover scrim --}}
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-black/35 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>

            {{-- Selection checkbox (top-left). The base-100 plate only applies when
                 unchecked so DaisyUI's checked primary fill + tick stay visible. --}}
            <input type="checkbox"
                class="absolute top-2 left-2 checkbox checkbox-primary checkbox-sm shadow-sm transition"
                :class="checked ? 'opacity-100' : 'opacity-0 group-hover:opacity-100 bg-base-100'"
                :checked="checked"
                @click.stop="$dispatch('media-toggle', { id: {{ $file->id }} })" />

            {{-- Quick actions (top-right, hover) --}}
            <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button wire:click.stop="selectField({{ $file->id }})"
                    class="btn btn-xs btn-primary shadow selectField" title="{{ __('Use file') }}">
                    <x-tabler-square-plus class="w-4 h-4 stroke-current" />
                </button>
                <button wire:click.stop="select"
                    class="btn btn-xs bg-base-100 border-base-300 hover:bg-base-200 shadow" title="{{ __('Edit Meta') }}">
                    <x-tabler-edit class="w-4 h-4 stroke-current" />
                </button>
            </div>

            {{-- Extension badge (bottom-left) --}}
            @if ($file->extension)
                <span class="absolute bottom-2 left-2 badge badge-sm badge-neutral uppercase tracking-wide opacity-90">{{ $file->extension }}</span>
            @endif
        </figure>
        <div class="p-3">
            <div class="text-sm font-semibold truncate" title="{{ $file->name }}">{{ $file->name }}</div>
            <p class="text-[10px] opacity-50 mt-0.5">{{ $file->created_at->format('d.m.Y') }}</p>
        </div>
    </div>
    @endif
</div>
