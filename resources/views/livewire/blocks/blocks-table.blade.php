<div>

    <div x-cloak id="FormAdd" x-data="{ open: @entangle('FormAdd') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">

                <x-kompass::form.input wire:model.live="name" label="Name" type="text" class="mt-1 block w-full" />
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <x-kompass::form.input wire:model="type" label="Type / Slug" type="text" class="mt-1 block w-full bg-gray-100" readonly />
                @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <button wire:click="saveBlock" class="btn btn-primary">Save</button>

            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::modal data="FormDelete" />

    <div class="flex flex-col">
        <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
            <div x-data="{ open: @entangle('FormAdd') }" class="flex justify-end gap-4">

                <button class="btn btn-primary" @click="open = true">
                    <x-tabler-square-plus stroke-width="1.5" />{{ __('New block') }}
                </button>
                {{-- <template x-teleport="#navheader"> </template> --}}
            </div>
        </div>

        <div class=" align-middle inline-block min-w-full ">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                  @if ($pages->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            @foreach ($headers as $key => $value)
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                                    {{ __($value) }}
                                </th>
                            @endforeach

                        </thead>


                        <tbody wire:sortable="updateOrder" class="bg-white divide-y divide-gray-200 ">
                            @foreach ($pages as $key => $page)
                                <tr wire:sortable.item="{{ $page->id }}">
                                    <td wire:sortable.handle class="pl-4 w-4 bg-white">
                                        <x-tabler-arrow-autofit-height
                                            class="cursor-move stroke-current  text-gray-400" />
                                    </td>

                                    @foreach ($data as $key => $value)
                                        <td wire:sortable.handle
                                            class="px-4 whitespace-nowrap text-sm font-medium text-gray-800 bg-white">
                                            <div class="flex items-center ">
                                                @if ($key == '1')
                                                    <div
                                                        class="px-4 whitespace-nowrap text-sm font-medium text-gray-800 bg-white">
                                                        {{-- <img class="h-12 w-12 rounded-full" src="{{ asset('storage/'.$user->profile_photo_path) }}" alt=""> --}}
                                                        <img class="h-14 w-14 rounded-lg" src="{{ $page->$value }}"
                                                            alt="">

                                                    </div>
                                                @else
                                                    <div class="">
                                                        <div class="text-sm font-medium text-gray-900">

                                                            {{ $page->$value }}

                                                        </div>
                                                    </div>
                                                @endif


                                            </div>
                                        </td>
                                    @endforeach

                                    <td class="px-4 py-3 whitespace-nowrap bg-white">
                                        <div class="flex justify-end items-center gap-1">


                                            <a href="/admin/blocks/show/{{ $page->id }}"
                                                class="flex justify-center">
                                                <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                            </a>

                                            <span wire:click="selectItem({{ $page->id }}, 'delete')"
                                                class="flex justify-center">
                                                <x-tabler-trash class="cursor-pointer stroke-red-500" />
                                            </span>
                                        </div>
                                    </td>
                            @endforeach
                            </tr>


                        </tbody>
                    </table>
                @else
                    <div class="h-[20rem] flex justify-center items-center bg-gray-100">
                        <div>
                            <x-tabler-clipboard-text class="h-[6rem] w-[6rem] m-auto stroke-[1.2] stroke-[#FFA700]" />
                            <div class="font-semibold text-md"> {{ __('No Data') }} </div>
                        </div>
                    </div>

                @endif


            </div>
        </div>

    </div>

</div>
