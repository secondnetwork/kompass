<div>
    <div class="flex gap-4 items-center pb-4">
        <div class="relative group md:w-1/2">
            <div class="absolute z-40 inset-y-0 left-0 flex items-center pl-3 pointer-events-none ">
                <x-tabler-search class="w-5 h-5 opacity-40 group-focus-within:opacity-100 transition-opacity" />
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="input input-bordered w-full pl-10 focus:bg-base-100 transition-colors"
                placeholder="{{ __('Search media...') }}">
        </div>
        <div class="flex items-center gap-4 py-2 w-full justify-end">
            <label for="filter-type" class="block text-sm font-medium text-gray-700">Filter by Type:</label>
            <select id="filter-type" wire:model.live="filter" class="select">
                <option value="">All</option>
                <option value="folder">Folders</option>
                <option value="image">Images</option>
                <option value="video">Videos</option>
                <option value="audio">Audio</option>
                <option value="document">Documents</option>
            </select>
        </div>
    </div>

    @if ($files->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 opacity-40">
            <x-tabler-photo-video stroke-width="1" class="w-16 h-16 mb-4" />
            <p class="text-lg font-medium">{{ __('No Media') }}</p>
        </div>
    @else
        <div class="@container">
            <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4 pb-1 gap-6">
                @foreach ($files as $file)
                    <livewire:media-components.media-item :file="$file" :key="$file->id" />
                @endforeach
            </div>
        </div>
        <div class="flex justify-center">
            {{ $files->links('kompass::livewire.pagination') }}
        </div>
    @endif
</div>
