<div>
    <media-grid class="flex flex-col rounded-xl border border-base-300 bg-base-100">

        <livewire:media-components.media-uploader :dir="$dir" />

        <div class="flex  justify-between gap-4 border-y border-base-300 px-5">
            <div class="breadcrumbs text-sm flex items-center gap-2 w-full">
                @php
                    $segments = explode('/', $dir);
                    $currentPath = '';
                @endphp
                @foreach ($segments as $segment)
                    @php
                        $currentPath = $currentPath ? $currentPath . '/' . $segment : $segment;
                    @endphp
                    @if ($loop->first)
                        <x-tabler-home class="size-4 opacity-40" />
                    @endif
                    @if (!$loop->first)
                        <x-tabler-chevron-right class="size-4 opacity-40" /><x-tabler-folder class="size-4 opacity-40" />
                    @endif

                    <button wire:click="goToFolder('{{ $currentPath }}')"
                        class="hover:text-primary transition-colors cursor-pointer {{ $loop->last ? 'font-bold' : 'opacity-60' }}">
                        {{ $segment == 'media' ? __('Home') : $segment }}
                    </button>
                @endforeach


            </div>

  


        </div>

                    {{-- Filter UI --}}


        <div class="overflow-x-auto p-4" x-cloak x-data="{ dir: @entangle('dir') }">
            <livewire:media-components.media-list :dir="$dir" :filter="$filter" />
        </div>
    </media-grid>


    <x-kompass::modal data="FormDelete" />

    <div x-data="{ open: @entangle('FormFolder') }">
        <x-kompass::offcanvas :w="'w-1/3'" class="p-8 grid gap-4">
            <x-slot name="body">
                <x-kompass::form.input type="text" name="name" label="{{ __('Folder Name') }}" wire:model="foldername" />
                <button wire:click="newFolder" class="btn btn-primary">{{ __('Save') }}</button>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div x-data="{ open: @entangle('FormEdit') }">
        <x-kompass::offcanvas :w="'w-1/3'" class="p-8 grid gap-4">
            <x-slot name="body">
                <div class="modal-body grid gap-1">
                    @if ($file)
                        @if ($type == 'video')
                            <video controls src="{{ asset($file) }}"></video>
                        @else
                            <img class="relative text-sm rounded-lg shadow w-full aspect-[4/3] object-cover bg-cover bg-center bg-gray-300"
                                src="{{ asset($file) }}" alt="">
                        @endif
                    @endif
                    <x-kompass::form.input wire:model="name" label="{{ __('Name') }}" type="text" class="form-control" />
                    <x-kompass::form.input wire:model="alt" label="{{ __('Alt') }}" type="text" class="form-control" />
                    <x-kompass::form.input wire:model="description" label="{{ __('Description') }}" type="text" class="form-control" />
                    <label>{{ __('Url') }}:</label>
                    <input disabled value="{{ asset($file) }}" type="text" class="form-control input" />
                    <label>{{ __('Move to Folder') }}</label>
                    <div class="flex gap-2">
                        <select wire:model="newFolderLocation" class="form-control input">
                            <option value="media">{{ __('Base') }}</option>
                            @foreach ($dirgroup as $folder)
                                @php
                                    $full_path = ($folder->path ? rtrim($folder->path, '/') . '/' : '') . $folder->slug;
                                @endphp
                                <option value="{{ $full_path }}">{{ $full_path }}</option>
                            @endforeach
                        </select>
                        <button wire:click="moveItem" class="btn btn-primary">{{ __('Move') }}</button>
                    </div>
                </div>
                <div class="modal-footer mt-4 flex gap-4">
                    <button wire:click="update" class="btn btn-primary">
                        <div wire:loading>
                            <svg class="animate-spin h-5 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <x-tabler-device-floppy class="icon-lg" wire:loading.remove />
                        {{ __('Save') }}
                    </button>
                    <button wire:click="selectItem({{ $iditem ?? 0 }}, 'delete')"
                        class="btn btn-error flex justify-center"><x-tabler-trash
                            class="cursor-pointer" />{{ __('Delete') }}</button>
                </div>
            </x-slot>
        </x-kompass::offcanvas>
    </div>
</div>
