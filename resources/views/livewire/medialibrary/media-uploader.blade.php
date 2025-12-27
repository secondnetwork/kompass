<div x-data="fileUpload()">
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between bg-base-200/50 p-4 rounded-xl border border-base-300">
            <div class="flex flex-col">
                <h3 class="font-bold text-sm">{{ __("Media Assets") }}</h3>
                <p class="text-xs opacity-60">{{ __("Upload and manage your media files") }}</p>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-sm btn-ghost gap-2" @click="$dispatch('add-folder')">
                    <x-tabler-folder-plus class="w-4 h-4" />
                    {{ __("New Folder") }}
                </button>
                <label for="file-upload" class="btn btn-sm btn-primary gap-2 shadow-sm">
                    <x-tabler-upload class="w-4 h-4" x-show="!isUploading" />
                    <span class="loading loading-spinner loading-xs" x-show="isUploading"></span>
                    {{ __("Upload Files") }}
                </label>
                <input type="file" id="file-upload" multiple @change="handleFileSelect" class="hidden" />
            </div>
        </div>
        <div x-show="isUploading" x-transition class="bg-base-100 border border-primary/20 p-4 rounded-xl shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-primary">{{ __("Uploading media...") }}</span>
                <span class="text-xs font-bold" x-text="progress + '%'"></span>
            </div>
            <progress class="progress progress-primary w-full h-2" :value="progress" max="100"></progress>
        </div>
        @if(session()->has('message'))
            <div class="alert alert-success shadow-sm py-3 px-4 rounded-xl" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                <x-tabler-check class="w-5 h-5" />
                <span class="text-sm font-medium">{{ session('message') }}</span>
            </div>
        @endif
    </div>
    <script>
        function fileUpload() {
            return {
                isUploading: false,
                progress: 0,
                handleFileSelect(event) {
                    if (event.target.files.length) {
                        this.uploadFiles(event.target.files)
                    }
                },
                uploadFiles(files) {
                    this.isUploading = true;
                    this.progress = 0;
                    @this.uploadMultiple('files', files, 
                        (success) => { 
                            this.isUploading = false; 
                            this.progress = 0;
                        },
                        (error) => { 
                            this.isUploading = false;
                            console.error(error); 
                        },
                        (event) => { 
                            this.progress = event.detail.progress; 
                        }
                    );
                }
            }
        }
    </script>
</div>