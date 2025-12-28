<div>
    <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow group border border-base-300 {{ $file->type == 'folder' ? 'cursor-pointer' : '' }}"
        @if ($file->type == 'folder') wire:click="goToFolder" @endif>
        <figure class="relative aspect-video overflow-hidden">
            @if ($file->type == 'folder')
                <div class="flex items-center justify-center w-full h-full bg-base-200">
                    <x-tabler-folder
                        class="w-16 h-16 opacity-40 group-hover:scale-110 transition-transform duration-300" />
                </div>
            @elseif ($file->type == 'video')
                <div class="flex items-center justify-center w-full h-full bg-base-200">
                    <x-tabler-video
                        class="w-16 h-16 opacity-40 group-hover:scale-110 transition-transform duration-300" />
                </div>
            @else
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
            @endif

            <div class="absolute top-2 right-2 flex gap-1">
                <div class="badge badge-neutral opacity-80 uppercase">
                    {{ $file->extension ?? ($file->type == 'folder' ? __('Folder') : '') }}</div>
            </div>
        </figure>
        <div class="card-body p-3">
            <div class="flex justify-between items-start gap-2">
                <div class="truncate">
                    <div class="card-title font-bold truncate block w-full" title="{{ $file->name }}">
                        {{ $file->name }}
                    </div>
                    <p class="text-[10px] opacity-60">{{ $file->created_at->format('d.m.Y') }}</p>
                </div>
                <div class="absolute top-2 left-2 flex gap-1">
                    @if ($file->type != 'folder')
                        <button wire:click.stop="selectField({{ $file->id }})"
                            class="badge badge-primary cursor-pointer selectField" title="{{ __('Use file') }}">
                            <x-tabler-square-plus class="w-4 h-4 stroke-current" />
                        </button>
                    @endif
                    <button wire:click.stop="select" class="badge badge-primary cursor-pointer"
                        title="{{ __('Edit Meta') }}">
                        <x-tabler-edit class="w-4 h-4 stroke-current" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
