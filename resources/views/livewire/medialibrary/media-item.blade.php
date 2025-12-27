<div>
    <div class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow group border border-base-300">
        <figure class="relative aspect-video overflow-hidden">
            <img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300"
                 src="{{ imageToWebp(Storage::url(($file->path ? $file->path . "/" : "") . $file->slug . "." . $file->extension), 500) }}"
                 alt="{{ $file->alt ?? $file->name }}">
            
            <div class="absolute top-2 right-2 flex gap-1">
                <div class="badge badge-neutral badge-xs opacity-80 uppercase">{{ $file->extension }}</div>
            </div>
        </figure>
        <div class="card-body p-3">
            <div class="flex justify-between items-start gap-2">
                <div class="truncate">
                    <div class="card-title  font-bold truncate block w-full" title="{{ $file->name }}">
                        {{ $file->name }}
                    </div>
                    <p class="text-[10px] opacity-60">{{ $file->created_at->format("d.m.Y") }}</p>
                </div>
                <div class="flex gap-1">
                    <button wire:click="selectField({{ $file->id }})" class="badge badge-primary cursor-pointer  selectField" title="{{ __('Use file') }}">
                        <x-tabler-square-plus class="w-4 h-4 stroke-current" />
                    </button>
                    <button wire:click="select" class="badge badge-primary cursor-pointer" title="{{ __('Edit Meta') }}">
                        <x-tabler-edit class="w-4 h-4 stroke-current" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
