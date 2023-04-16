<div>
    <div x-cloak id="FormAdd" x-data="{ open: @entangle('FormAdd') }">
        <x-kompass::offcanvas class="text-gray-500 p-8">
            <x-slot name="body">

                <x-kompass::form.input type="text" name="name" wire:model="name" />
                <x-kompass::input-error for="name" class="mt-2" />
                <button wire:click="addMenu" class="btn btn-primary">Save</button>

            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::modal data="FormDelete" />


    <div class="flex flex-col">
        <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
            <div x-data="{ open: @entangle('FormAdd') }" class="flex justify-end gap-4">

                <button class="flex gap-x-2 justify-center items-center text-md" @click="open = true">
                    <x-tabler-square-plus stroke-width="1.5" />{{ __('New menu') }}
                </button>
                {{-- <template x-teleport="#navheader"> </template> --}}
            </div>
        </div>

        <div class=" align-middle inline-block min-w-full ">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">



                @if ($menus->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            @foreach ($headers as $key => $value)
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    {{ __($value) }}
                                </th>
                            @endforeach

                        </thead>



                            <tbody wire:sortable="updateMenusOrder" class="bg-white divide-y divide-gray-200">
                                @foreach ($menus as $key => $menu)
                                    <tr wire:sortable.item="{{ $menu->id }}">
                                        <td wire:sortable.handle class="pl-4 w-4 bg-white">
                                            <x-tabler-arrow-autofit-height
                                                class="cursor-move stroke-current  text-gray-400" />
                                        </td>
    

                                        @foreach ($data as $key => $value)
                                            <td wire:sortable.handle class="px-4 whitespace-nowrap text-sm font-medium text-gray-800 bg-white">
                                                    {{ $menu->$value }}
                                            </td>
                                        @endforeach

                                        <td class="px-4 py-3 whitespace-nowrap bg-white">
                                            <div class="flex justify-end items-center gap-1">
                                                <a href="/admin/menus/show/{{ $menu->id }}"
                                                    class="flex justify-center">
                                                    <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                                </a>
                                                {{-- <span wire:click="clone({{ $menu->id }})"
                                                    class="flex justify-center">
                                                    <x-tabler-copy class="cursor-pointer    stroke-violet-500" />
                                                </span> --}}
                                                <span wire:click="selectItem({{ $menu->id }}, 'delete')"
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
                        <div class="h-36 text-center">{{__('No Data')}}</div>

                    @endif


                </div>
            </div>
    
        </div>

</div>
