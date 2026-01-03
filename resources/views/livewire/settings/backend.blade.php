<div class="grid grid-cols-1 items-center w-full h-auto gap-4" x-data="{ isDropping: false, isUploading: false, progress: 0 }"
    x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">

    <x-kompass::form.switch wire:model.live="registration_can_user" label="{{ __('User can register') }}" />

    <hr class="h-px w-full border-none bg-base-300">
    <label class="text-base-content font-bold text-sm">Admin Logo</label>
    <div class="max-w-120">
       
        @if (isset($adminlogo) && $adminlogo != '')
            <div class="relative  border border-dashed border-base-300 rounded-lg">

                <img src="{{ is_string($adminlogo) ? url($adminlogo) . '?' . uniqid() : $adminlogo->temporaryUrl() }}"
                    class="h-auto rounded-lg aspect-video" />
                <button wire:click="deleteImage()"
                    class="flex absolute top-0 right-0 items-center px-3 py-1.5 mt-2 mr-2 text-xs font-medium text-white rounded-md bg-red-500/70 hover:bg-red-500/90">
                    <x-tabler-trash class="mr-1 w-4 h-4" />
                    <span>{{ __('Remove Image') }}</span>
                </button>
            </div>
        @else
            <div class="" :class="{ 'border-primary bg-primary/5': isDropping }"
                @dragover.prevent="isDropping = true" @dragleave.prevent="isDropping = false"
                @drop.prevent="isDropping = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))">
                <label for="file-upload"
                    class="relative flex justify-center items-center cursor-pointer rounded-lg aspect-video border border-dashed border-gray-900/25 hover:border-primary px-6 py-10 transition-colors duration-200">

                    <div class="text-center ">

                        <x-tabler-cloud-upload stroke-width="1.5" class="mx-auto size-12 text-base-300" />
                        <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">

                            <input id="file-upload" x-ref="fileInput" wire:model="adminlogo" type="file"
                                class="sr-only" accept="image/*">

                            <p class="pl-1">{{ __('Upload a file or drag and drop') }}</p>
                        </div>
                        <p class="text-xs leading-5">SVG, PNG, JPG</p>
                    </div>
                </label>
            </div>

            <!-- Progress Bar -->
            <div x-show="isUploading" class="bg-gray-200 rounded-full h-2.5 mt-2" x-cloak>
                <div class="bg-primary h-2.5 rounded-full" :style="'width: ' + progress + '%'"></div>
            </div>
        @endif
    </div>


</div>
