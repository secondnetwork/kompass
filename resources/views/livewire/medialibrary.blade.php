<div>
    <media-grid class="flex flex-col">
        <livewire:media-components.media-uploader />

        <div class="overflow-x-auto mt-4" x-cloak x-data="{ dir: @entangle("dir") }">
            <livewire:media-components.media-list />
        </div>
    </media-grid>

    <x-kompass::modal data="FormDelete" />

    <div x-data="{ open: @entangle("FormFolder") }">
        <x-kompass::offcanvas :w="'w-1/3'" class="p-8 grid gap-4">
            <x-slot name="body">
                <x-kompass::form.input type="text" name="name" wire:model="foldername" />
                <x-kompass::input-error for="name" class="mt-2" />
                <button wire:click="newFolder" class="btn btn-primary">Save</button>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div x-data="{ open: @entangle("FormEdit") }">
        <x-kompass::offcanvas :w="'w-1/3'" class="p-8 grid gap-4">
            <x-slot name="body">
                <div class="modal-body grid gap-1">
                    @if ($file)
                        @if ($type == "video")
                            <video controls src="{{ asset($file) }}"></video>
                        @else
                            <img class="relative text-sm rounded-lg shadow w-full aspect-[4/3] object-cover bg-cover bg-center bg-gray-300"
                                src="{{ asset($file) }}" alt="">
                        @endif
                    @endif
                    <label>Name</label>
                    <input wire:model="name" type="text" class="form-control input" />
                    @if ($errors->has("name"))
                        <p style="color: red;">{{ $errors->first("name") }}</p>
                    @endif

                    <label>Alt</label>
                    <input wire:model="alt" type="text" class="form-control input" />

                    <label>Description</label>
                    <input wire:model="description" type="text" class="form-control input" />
                    <label>Url:</label>
                    <input disabled value="{{ asset($file) }}" type="text" class="form-control input" />
                    <label>{{ __("Move to Folder") }}</label>
                    <div class="flex gap-2">
                        <select wire:model="newFolderLocation" class="form-control input">
                            <option value="media">{{ __("Base") }}</option>
                            @foreach ($dirgroup as $folder)
                                @if ($folder->path && $folder->path != "media")
                                    <option value="{{ $folder->path }}">{{ $folder->path }}</option>
                                @endif
                            @endforeach
                        </select>
                        <button wire:click="moveItem" class="btn btn-primary">{{ __("Move") }}</button>
                    </div>
                </div>
                <div class="modal-footer mt-4 flex gap-4">
                    <button wire:click="update" class="btn btn-primary">
                        <div wire:loading>
                            <svg class="animate-spin h-5 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <x-tabler-device-floppy class="icon-lg" wire:loading.remove />
                        {{ __("Save") }}
                    </button> 
                    <button
                        wire:click="selectItem({{ $iditem ?? 0 }}, 'delete')"
                        class="btn btn-error flex justify-center"><x-tabler-trash class="cursor-pointer" />{{ __("Delete") }}</button>
                </div>
            </x-slot>
        </x-kompass::offcanvas>
    </div>
</div>