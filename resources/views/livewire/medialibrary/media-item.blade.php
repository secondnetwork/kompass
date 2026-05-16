<div>
    @if ($file->type == 'folder')
        <div class="flex items-center gap-3 p-3 rounded-xl border border-base-300 bg-base-100 hover:bg-base-200 hover:border-primary/40 cursor-pointer transition-all group"
            wire:click="goToFolder">
            <div class="shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                <x-tabler-folder stroke-width="1.5" class="w-6 h-6 text-primary group-hover:scale-110 transition-transform duration-200" />
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-sm font-semibold truncate" title="{{ $file->name }}">{{ $file->name }}</div>
                <div class="text-[10px] opacity-40">{{ $file->created_at->format('d.m.Y') }}</div>
            </div>
            <button wire:click.stop="select"
                class="opacity-0 group-hover:opacity-100 btn btn-ghost btn-xs transition-opacity"
                title="{{ __('Edit Meta') }}">
                <x-tabler-edit class="w-4 h-4" />
            </button>
        </div>
    @else
    <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow group border border-base-300">
        <figure class="relative aspect-video overflow-hidden">
            @if ($file->type == 'video')
                <div class="flex items-center justify-center w-full h-full bg-base-300">
                    <x-tabler-video stroke-width="1.5"
                        class="w-16 h-16 opacity-40 group-hover:scale-110 transition-transform duration-300" />
                </div>
            @elseif ($file->type == 'document')
                <div class="flex items-center justify-center w-full h-full bg-base-300">
                    <x-tabler-file-text stroke-width="1.5"
                        class="w-16 h-16 opacity-40 group-hover:scale-110 transition-transform duration-300" />
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
                <div class="flex items-center justify-center w-full h-full bg-base-300">
                    <x-tabler-file stroke-width="1.5"
                        class="w-16 h-16 opacity-40 group-hover:scale-110 transition-transform duration-300" />
                </div>
            @endif

            <div class="absolute top-2 right-2 flex gap-1">
                <div class="badge badge-neutral opacity-80 uppercase">
                    {{ $file->extension ?? ($file->type == 'folder' ? __('Folder') : '') }}</div>
            </div>
        </figure>
        <div class="card-body p-3">
            <div class="flex justify-between items-start gap-2">
                <div class="truncate">
                    <div class="text-sm font-bold truncate block w-full" title="{{ $file->name }}">
                        {{ $file->name }}
                    </div>
                    <p class="text-[10px] opacity-60">{{ $file->created_at->format('d.m.Y') }}</p>
                </div>
                <div class="absolute top-2 left-2 flex gap-1 items-center"
                    x-data="{ checked: false }"
                    @media-selection-changed.window="checked = $event.detail.ids.includes({{ $file->id }})">
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
                    <input type="checkbox"
                        class="checkbox checkbox-primary checkbox-sm bg-base-100"
                        :checked="checked"
                        @click.stop="$dispatch('media-toggle', { id: {{ $file->id }} })" />
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
