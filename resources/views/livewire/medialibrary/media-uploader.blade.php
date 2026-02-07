<div
    x-data="{ uploading: false, progress: 0 }"
    x-on:livewire-upload-start="uploading = true; progress = 0"
    x-on:livewire-upload-progress="progress = $event.detail.progress"
    x-on:livewire-upload-finish="uploading = false; progress = 0"
    x-on:livewire-upload-error="uploading = false; progress = 0"
    x-on:livewire-upload-cancel="uploading = false; progress = 0"
>
    <div class="flex flex-col gap-4 p-5">
        <div class="flex justify-between">
            <div class="flex flex-col">
                <h6 class="font-semibold text-lg">{{ __('Media library') }}</h6>
                <p class="text-xs opacity-60">{{ __('Upload and manage your media files') }}</p>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-neutral btn-outline gap-2" wire:click="$dispatch('add-folder')">
                    <x-tabler-folder-plus class="size-6" />
                    {{ __('Add new Folder') }}
                </button>
                <label for="file-upload" class="btn btn-primary gap-2 shadow-sm cursor-pointer">
                    <x-tabler-upload class="size-6" />
                    {{ __('Add file') }}
                </label>
                <input type="file" id="file-upload" wire:model="files" multiple class="hidden" />
            </div>
        </div>

        <div x-show="uploading" x-transition class="bg-base-100 border border-primary/20 p-4 rounded-xl shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium">{{ __('Uploading media...') }}</span>
                <span class="text-xs font-bold" x-text="progress + '%'"></span>
            </div>
            <progress class="progress progress-primary w-full h-2" :value="progress" max="100"></progress>
        </div>

        @error('files.*')
            <div class="alert alert-error shadow-sm py-3 px-4 rounded-xl">
                <span class="text-sm font-medium">{{ $message }}</span>
            </div>
        @enderror
        @if(session()->has('message'))
            <div class="alert alert-success shadow-sm py-3 px-4 rounded-xl" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                <x-tabler-check class="w-5 h-5" />
                <span class="text-sm font-medium">{{ session('message') }}</span>
            </div>
        @endif
    </div>
</div>
