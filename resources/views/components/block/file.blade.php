@props([
    'itemfield' => '',
])
@if (!empty($itemfield->data))
    @php
        $file = Secondnetwork\Kompass\Models\File::find($itemfield->data);
    @endphp
    @if ($file)
        <div class="flex items-center justify-between p-2 border rounded bg-base-100">
            <div class="flex items-center gap-2 truncate">
                <x-tabler-file class="h-5 w-5 text-primary" />
                <span class="text-xs font-semibold truncate">{{ $file->name }}</span>
            </div>
            <div class="flex gap-1">
                <button wire:click="removemedia({{ $itemfield->id }})" class="btn btn-ghost btn-xs text-error">
                    <x-tabler-trash class="h-4 w-4" />
                </button>
                <button wire:click="selectitem('addMedia',{{ $itemfield->id }},'file',{{ $itemfield->block_id }})" class="btn btn-ghost btn-xs text-info">
                    <x-tabler-edit class="h-4 w-4" />
                </button>
            </div>
        </div>
    @endif
@else
    <button wire:click="selectitem('addMedia',{{ $itemfield->id }},'file',{{ $itemfield->block_id }})"
            class="btn btn-outline btn-dashed border-2 w-full h-12 gap-2 text-gray-400">
        <x-tabler-file-plus class="h-5 w-5" />
        <span>{{ __('Add File') }}</span>
    </button>
@endif