<div>


    <media-grid class="flex flex-col">

        <file-upload x-data="fileUpload()">

            <div x-show="isUploading" class="bg-gray-200 h-1 mt-3">
                <div class="bg-blue-500 h-[2px]" style="transition: width 1s" :style="`width: ${progress}%;`">
                </div>
            </div>

            <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
                <input wire:model.live="search" type="text"
                    class="block p-2 w-full border-2 border-gray-300 text-base rounded-md"
                    placeholder="{{ __('Search') }}...">
                <div x-data="{ open: @entangle('FormFolder') }" class="flex justify-end gap-4">

                    <button class="flex btn gap-x-2 justify-center items-center text-md" @click="open = true">
                        <x-tabler-folder-plus stroke-width="1.5" />{{ __('Add new Folder') }}
                    </button>


                    <label for="file-upload"
                        class="flex btn gap-x-2 cursor-pointer justify-center items-center text-md">
                        <x-tabler-square-plus stroke-width="1.5" />{{ __('Add file') }}
                    </label>

                    <input type="file" id="file-upload" multiple @change="handleFileSelect" class="hidden" />


                </div>

            </div>

        </file-upload>
        {{-- <div class="flex justify-between gap-4 my-4">




            <div class="w-1/6 relative">
                <select wire:model="orderAsc"
                    class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4   rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    id="grid-state">
                    <option value="1">Aufsteigend</option>
                    <option value="0">Absteigend</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                    </svg>
                </div>
            </div>
            <div class="w-1/6 relative">
                <select wire:model="perPage"
                    class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4   rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    id="grid-state">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                    </svg>
                </div>
            </div>




        </div> --}}

        <div class=" overflow-x-auto " x-cloak x-data="{ dir: @entangle('dir') }">

            <span>Folders</span>
            <div class="grid grid-cols-4 gap-5 mb-4 @if (!empty($search)) hidden @endif">

                @foreach ($dirgroup as $folder)
                    @if (empty($folder->path))
                        <div :class=" dir == '{{ $folder->path }}' ? 'border-blue-400 bg-blue-200' : 'bg-gray-200'"
                            class="cursor-pointer p-2 gap-1 border-2 bg-gray-200 rounded flex" @click="dir = ''">
                            <x-tabler-arrow-back-up /> Base
                        </div>
                    @else
                        <div :class=" dir == '{{ $folder->path }}' ? 'border-blue-400 bg-blue-200' : 'bg-gray-200'"
                            class="cursor-pointer p-2 gap-1 border-2 bg-gray-200 rounded flex"
                            @click="dir = '{{ $folder->path }}'">
                            <x-tabler-folder class="cursor-pointer stroke-current text-blue-500" />
                            {{ $folder->path }}
                        </div>
                    @endif
                @endforeach

            </div>


            <div class="@container">
                <div class="grid @sm:grid-cols-1 @lg:grid-cols-3 @3xl:grid-cols-4  gap-6">
                    @php
                        if ($search) {
                            $filesdata = $filessearch;
                        } else {
                            $filesdata = $mediafiles;
                        }
                        
                    @endphp

                    @forelse ($filesdata as $item)
                        <div id="m{{ $item->id }}"
                            @if (empty($search)) x-show="(dir === '{{ $item->path }}')" @endif>

                            @switch($item->type)
                                @case('video')
                                    <data-item class="bg-white block shadow rounded">

                                        <div
                                            class="relative text-sm font-bold rounded-tr-lg rounded-tl-lg w-full aspect-[16/9] bg-cover bg-center bg-gray-300 overflow-hidden">

                                            <video controls class="object-cover h-full"
                                                src="{{ asset($item->path . '/' . $item->slug . '.' . $item->extension) }}"></video>

                                            <div
                                                class="absolute rounded top-2 right-2 text-xs text-gray-600 bg-gray-200 uppercase py-1 px-2">
                                                {{ $item->extension }}</div>

                                        </div>

                                        <info-data class="flex justify-between items-center p-3">
                                            <div class="text-xs font-semibold  truncate">{{ $item->name }}</div>
                                            <div class="flex">
                                                <span wire:click="selectItem({{ $item->id }}, 'edit')">
                                                    <x-tabler-edit
                                                        class="cursor-pointer stroke-current text-gray-400 hover:text-blue-500" />
                                                </span>
                                                <span class="selectField"
                                                    wire:click="selectField({{ $item->id }}, '{{ $page ?? '' }}')">
                                                    <x-tabler-square-plus
                                                        class="cursor-pointer stroke-current text-gray-400 " />
                                                </span>
                                            </div>
                                        </info-data>

                                    </data-item>
                                @break

                                @case('audio')
                                    <span wire:click="selectItem({{ $item->id }}, 'edit')">
                                        <x-tabler-edit class="cursor-pointer stroke-current " />
                                    </span>
                                    <span class="selectField" wire:click="selectField({{ $item->id }}, 'page')">
                                        <x-tabler-square-plus class="cursor-pointer stroke-current " />
                                    </span>
                                    <x-tabler-file-music /># <div class="text-sm font-semibold py-4 truncate">
                                        {{ $item->name }} </div>
                                @break

                                @case('document')
                                    <span wire:click="selectItem({{ $item->id }}, 'edit')">
                                        <x-tabler-edit class="cursor-pointer stroke-current " />
                                    </span>
                                    <span class="selectField" wire:click="selectField({{ $item->id }}, 'page')">
                                        <x-tabler-square-plus class="cursor-pointer stroke-current " />
                                    </span>
                                    <x-tabler-file />
                                    <div class="text-sm font-semibold py-4 truncate">{{ $item->name }} </div>
                                @break

                                @case('folder')
                                @break

                                @case('image')
                                    <data-item class="bg-white block shadow rounded">

                                        <div class="relative text-sm font-bold rounded-tr-lg rounded-tl-lg w-full aspect-video bg-cover bg-center bg-gray-300"
                                            style="background-image: url('{{ asset($item->path . '/' . $item->slug . '.' . $item->extension) }}')">



                                            <div
                                                class="absolute rounded top-2 right-2 text-xs text-gray-600 bg-gray-200 uppercase py-1 px-2">
                                                {{ $item->extension }}</div>

                                        </div>

                                        <info-data class="flex justify-between items-center p-3">
                                            <div class="text-xs font-semibold  truncate">{{ $item->name }}</div>
                                            <div class="flex">
                                                <span wire:click="selectItem({{ $item->id }}, 'edit')">
                                                    <x-tabler-edit
                                                        class="cursor-pointer stroke-current text-gray-400 hover:text-blue-500" />
                                                </span>
                                                <span class="selectField"
                                                    wire:click="selectField({{ $item->id }}, '{{ $page ?? '' }}')">
                                                    <x-tabler-square-plus
                                                        class="cursor-pointer stroke-current text-gray-400 " />
                                                </span>
                                            </div>
                                        </info-data>

                                    </data-item>
                                @break

                                {{-- @case('svg')
                <img class="rounded m-auto" src="{{ asset( $item->extension->resize(300, 300)) }}" alt="">
                @break --}}

                                @default
                                    <data-item class="bg-white block shadow rounded">

                                        <div class="relative text-sm font-bold rounded-tr-lg rounded-tl-lg w-full aspect-[6/4] bg-cover bg-center bg-gray-300"
                                            style="background-image: url('{{ asset('storage' . $item->path . '/' . $item->slug . '.' . $item->extension) }}')">



                                            <div
                                                class="absolute rounded top-2 right-2 text-sm text-gray-600 bg-gray-200 uppercase py-1 px-3">
                                                {{ $item->extension }}</div>

                                        </div>

                                        <info-data class="flex justify-between items-center py-6 pl-4 pr-2">
                                            <div class="text-sm font-semibold  truncate">{{ $item->name }}</div>
                                            <div class="flex">
                                                <span wire:click="selectItem({{ $item->id }}, 'edit')">
                                                    <x-tabler-edit class="cursor-pointer stroke-current text-gray-400 " />
                                                </span>
                                                <span class="selectField"
                                                    wire:click="selectField({{ $item->id }}, 'page')">
                                                    <x-tabler-square-plus
                                                        class="cursor-pointer stroke-current text-gray-400 " />
                                                </span>
                                            </div>
                                        </info-data>

                                    </data-item>
                            @endswitch
                        </div>


                        @empty
                            <td>

                                <div class="h-[20rem] flex justify-center items-center bg-gray-100 col-span-5">
                                    <div>
                                        <x-tabler-clipboard-text
                                            class="h-[6rem] w-[6rem] m-auto stroke-[1.2] stroke-[#FFA700]" />
                                        <div class="font-semibold text-md"> {{ __('No Media') }} </div>
                                    </div>
                                </div>

                            </td>
                        @endforelse
                    </div>


                </div>

            </div>
            <div class="my-4">
                {{-- {{ $mediafiles->links('kompass::livewire.pagination') }} --}}
            </div>


        </media-grid>




        @once
            @push('scripts')
                <script>
                    function fileUpload() {
                        return {
                            isDropping: false,
                            isUploading: false,
                            progress: 0,
                            handleFileSelect(event) {
                                if (event.target.files.length) {
                                    this.uploadFiles(event.target.files)
                                }
                            },
                            handleFileDrop(event) {
                                if (event.dataTransfer.files.length > 0) {
                                    this.uploadFiles(event.dataTransfer.files)
                                }
                            },
                            uploadFiles(files) {
                                const $this = this;
                                this.isUploading = true,
                                @this.uploadMultiple('files', files,
                                
                                    function(success) {
                                        $this.isUploading = false
                                        $this.progress = 0
                                    },
                                    function(error) {
                                        console.log('error', error)
                                    },
                                    function(event) {
                                        $this.progress = event.detail.progress
                                    }
                                )
                            },
                            removeUpload(filename) {
                                @this.removeUpload('files', filename)
                            },
                        }
                    }
                </script>
            @endpush
        @endonce


        <x-kompass::modal data="FormDelete" />

        <div x-data="{ open: @entangle('FormFolder') }">
            <x-kompass::offcanvas :w="'w-1/3'" class="p-8 grid gap-4">
                <x-slot name="body">

                    <x-kompass::form.input type="text" name="name" wire:model="foldername" />
                    <x-kompass::input-error for="name" class="mt-2" />
                    <button wire:click="newFolder" class="btn btn-primary">Save</button>

                </x-slot>
            </x-kompass::offcanvas>
        </div>

        <div x-data="{ open: @entangle('FormEdit') }">
            <x-kompass::offcanvas :w="'w-1/3'" class="p-8 grid gap-4">
                <x-slot name="body">

                    <div class="modal-body">
                        @if ($file)

                            @if ($type == 'video')
                                <video controls src="{{ asset('storage' . $file) }}"></video>
                            @else
                                <img wire:model="extension"
                                    class="relative text-sm rounded-lg shadow w-full aspect-[4/3] object-cover bg-cover bg-center bg-gray-300"
                                    src="{{ asset($file) }}" alt="">
                            @endif
                        @endif
                        <label>Name</label>
                        <input wire:model="name" type="text" class="form-control" />
                        @if ($errors->has('name'))
                            <p style="color: red;">{{ $errors->first('name') }}</p>
                        @endif


                        <label>Alt</label>
                        <input wire:model="alt" type="text" class="form-control" />

                        <label>Description</label>
                        <input wire:model="description" type="text" class="form-control" />
                        <label>Url:</label>
                        <input value="{{ asset($file) }}" type="text" class="form-control" />


                    </div>
                    <div class="modal-footer mt-auto flex gap-4">
                        <button wire:click="update" class="btn btn-primary">{{ __('Save') }}</button> <button
                            wire:click="selectItem({{ $iditem }}, 'delete')" “
                            class="btn-danger flex  justify-center ">{{ __('Delete') }}</button>
                    </div>
                </x-slot>
            </x-kompass::offcanvas>
        </div>

    </div>
