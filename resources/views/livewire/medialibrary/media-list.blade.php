<div>
    <div class="sticky top-0 z-10 bg-base-100/80 backdrop-blur pb-4 pt-1">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <x-tabler-search class="w-5 h-5 opacity-40 group-focus-within:opacity-100 transition-opacity" />
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="input input-bordered w-full pl-10 bg-base-200/50 focus:bg-base-100 transition-colors"
                placeholder="{{ __("Search media...") }}">
        </div>
    </div>

    @if($files->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 opacity-40">
            <x-tabler-photo-off class="w-16 h-16 mb-4" />
            <p class="text-lg font-medium">{{ __("No media found") }}</p>
        </div>
    @else
        <div class="@container">
            <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4 pb-1 gap-6">
            @foreach($files as $file)
                <livewire:media-components.media-item :file="$file" :key="$file->id" />
            @endforeach
            </div>
        </div>
        <div class="mt-8 flex justify-center">
            {{ $files->links('kompass::livewire.pagination') }}
        </div>
    @endif
</div>