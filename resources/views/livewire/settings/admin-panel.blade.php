<div class="p-6">
    <div class="max-w-xl" x-data="{ isDropping: false, isUploading: false, progress: 0 }"
         x-on:livewire-upload-start="isUploading = true"
         x-on:livewire-upload-finish="isUploading = false"
         x-on:livewire-upload-error="isUploading = false"
         x-on:livewire-upload-progress="progress = $event.detail.progress">
       
        

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">{{ __('Copyright') }}</label>
                <input type="text" wire:model="copyright" class="input input-bordered w-full mt-1">
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="btn btn-primary" :disabled="isUploading" wire:loading.attr="disabled">{{ __('Save') }}</button>
                <span wire:loading class="loading loading-spinner loading-sm"></span>
                <span x-data="{ show: false }" x-show="show" x-transition x-init="@this.on('saved', () => { show = true; setTimeout(() => show = false, 2000) })" class="text-sm text-green-600">
                    {{ __('Saved.') }}
                </span>
            </div>
        </form>
    </div>
</div>
