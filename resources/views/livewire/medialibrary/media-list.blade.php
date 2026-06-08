<div x-data="{
    selectedIds: [],
    showMoveSelect: false,
    moveTarget: '',
    toggle(id) {
        if (this.selectedIds.includes(id)) {
            this.selectedIds = this.selectedIds.filter(i => i !== id);
        } else {
            this.selectedIds.push(id);
        }
        this.$dispatch('media-selection-changed', { ids: this.selectedIds });
    },
    isSelected(id) {
        return this.selectedIds.includes(id);
    },
    selectAll(ids) {
        this.selectedIds = ids;
        this.$dispatch('media-selection-changed', { ids: this.selectedIds });
    },
    clearSelection() {
        this.selectedIds = [];
        this.showMoveSelect = false;
        this.moveTarget = '';
        this.$dispatch('media-selection-changed', { ids: [] });
    },
    async confirmDelete() {
        if (!confirm('{{ __('Delete :count selected items?', ['count' => '']) }}'.replace(':count', this.selectedIds.length))) return;
        await $wire.bulkDelete(this.selectedIds);
        this.clearSelection();
    },
    async confirmMove() {
        if (!this.moveTarget) return;
        await $wire.bulkMove(this.selectedIds, this.moveTarget);
        this.clearSelection();
    }
}" @media-toggle.window="toggle($event.detail.id)">

    {{-- Search + Filter bar --}}
    <div class="flex flex-wrap gap-3 items-center pb-4">
        <div class="relative group flex-1 min-w-[12rem] md:max-w-sm">
            <div class="absolute z-40 inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <x-tabler-search class="w-5 h-5 opacity-40 group-focus-within:opacity-100 transition-opacity" />
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="input input-bordered input-sm h-10 w-full pl-10 focus:bg-base-100 transition-colors"
                placeholder="{{ __('Search media...') }}">
        </div>
        <div class="w-48">
            <x-kompass::select id="filter-type" :searchable="false" wire:model.live="filter" placeholder="{{ __('Filter by Type') }}" :options="[
                ['name' => __('All'), 'id' => null],
                ['name' => __('Images'), 'id' => 'image'],
                ['name' => __('Videos'), 'id' => 'video'],
                ['name' => __('Audio'), 'id' => 'audio'],
                ['name' => __('Documents'), 'id' => 'document'],
            ]" />
        </div>
    </div>

    {{-- Bulk action bar --}}
    <div x-show="selectedIds.length > 0" x-cloak x-transition
        class="flex items-center gap-3 mb-4 p-3 rounded-xl border border-primary/30 bg-primary/5">
        <span class="text-sm font-semibold" x-text="selectedIds.length + ' {{ __('selected') }}'"></span>
        <div class="flex-1 flex items-center gap-2">
            {{-- Move --}}
            <div class="flex items-center gap-2">
                <button @click="showMoveSelect = !showMoveSelect"
                    class="btn btn-sm btn-outline gap-1">
                    <x-tabler-folder-symlink class="w-4 h-4" />
                    {{ __('Move') }}
                </button>
                <div x-show="showMoveSelect" x-transition class="flex items-center gap-2">
                    <select x-model="moveTarget" class="select select-bordered select-sm">
                        <option value="">{{ __('Select folder...') }}</option>
                        <option value="media">{{ __('Root') }}</option>
                        @foreach ($allFolders as $f)
                            <option value="{{ ($f->path ? rtrim($f->path, '/') . '/' : '') . $f->slug }}">
                                {{ ($f->path && $f->path !== 'media' ? rtrim($f->path, '/') . '/' : '') . $f->name }}
                            </option>
                        @endforeach
                    </select>
                    <button @click="confirmMove" :disabled="!moveTarget"
                        class="btn btn-sm btn-primary">
                        {{ __('Apply') }}
                    </button>
                </div>
            </div>
            {{-- Delete --}}
            <button @click="confirmDelete" class="btn btn-sm btn-error gap-1">
                <x-tabler-trash class="w-4 h-4" />
                {{ __('Delete') }}
            </button>
        </div>
        <button @click="clearSelection" class="btn btn-sm btn-ghost">
            <x-tabler-x class="w-4 h-4" />
        </button>
    </div>

    {{-- Folders section --}}
    @if ($folders->isNotEmpty())
        @php $folderIds = $folders->pluck('id')->toJson(); @endphp
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-sm font-semibold opacity-60">{{ __('Folders') }} ({{ $folders->count() }})</span>
            </div>
            <div class="@container">
                <div class="grid grid-cols-2 @sm:grid-cols-2 @lg:grid-cols-3 @3xl:grid-cols-5 gap-3">
                    @foreach ($folders as $folder)
                        <livewire:media-components.media-item :file="$folder" :key="'folder-'.$folder->id" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Empty state --}}
    @if ($files->isEmpty() && $folders->isEmpty())
        <div class="min-h-[60vh] flex flex-col items-center justify-center">
            <x-tabler-photo-video stroke-width="1.5" class="w-16 h-16 mb-4 text-brand-500" />
            <div class="text-lg font-semibold">{{ __('No Media') }}</div>
        </div>

    {{-- Files section --}}
    @elseif ($files->isNotEmpty())
        @if ($folders->isNotEmpty())
            @php $fileIds = $files->pluck('id')->toJson(); @endphp
            <div class="flex items-center gap-2 mb-3">
                <span class="text-sm font-semibold opacity-60">{{ __('Assets') }} ({{ $files->total() }})</span>
                <button @click="selectAll({{ $fileIds }})"
                    class="text-xs opacity-40 hover:opacity-80 transition-opacity cursor-pointer">
                    {{ __('Select all') }}
                </button>
            </div>
        @endif
        <div class="@container">
            <div class="grid grid-cols-2 @sm:grid-cols-2 @lg:grid-cols-3 @3xl:grid-cols-5 pb-1 gap-3">
                @foreach ($files as $file)
                    <livewire:media-components.media-item :file="$file" :key="$file->id" />
                @endforeach
            </div>
        </div>
        <div class="mt-4">
            <x-kompass::table-footer :paginator="$files" />
        </div>
    @endif

</div>
