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
                        <thead class="bg-base-300">
                            @foreach ($headers as $key => $value)
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                                    {{ __($value) }}
                                </th>
                            @endforeach

                        </thead>


                        <tbody class="bg-base-100 divide-y divide-gray-200" wire:sort="handleSort">
                            @foreach ($pages as $key => $page)
                                <tr wire:sort:item="{{ $page->id }}">
                                    <td wire:sort:handle class="pl-4 w-4 bg-base-100">
                                        <x-tabler-arrow-autofit-height
                                            class="cursor-move stroke-current  text-gray-400" />
                                    </td>

                                        @foreach ($data as $key => $value)
                                        <td
                                            class="px-4 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                            <div class="flex items-center ">

                            
                                                    <div class="">
                                                        <div class="text-sm font-medium text-base-content">

                                                            {{ $page->$value }}

                                                        </div>
                                                    </div>
                                           


                                            </div>
                                        </td>
                                    @endforeach

                                    <td class="px-4 py-3 whitespace-nowrap bg-base-100">
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
                    <div class="min-h-[60vh] flex flex-col items-center justify-center bg-gray-100">
                        <div class="flex flex-col items-center">
                            <x-tabler-layout-grid-add stroke-width="1.5" class="w-16 h-16 mb-2 text-brand-500" />
                            <div class="font-semibold text-lg"> {{ __('No Data') }} </div>
                        </div>
                    </div>

                @endif


            </div>
        </div>

    </div>

</div>
