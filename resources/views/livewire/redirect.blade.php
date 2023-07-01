    <div class="flex flex-col">
        <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
            <div x-data="{ open: @entangle('FormAdd') }" class="flex justify-end gap-4">

                <button class="flex btn gap-x-2 justify-center items-center text-md" @click="open = true">
                    <x-tabler-square-plus stroke-width="1.5" />{{ __('Add') }}
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
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
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
                                        <td
                                            class="px-4 whitespace-nowrap text-sm font-medium text-gray-800 bg-white">
                                            @if ($key == 0)
                                                <a target="_blank" href="/{{ $page->slug }}">
                                            @endif
                                            @if ($key == 2)
                                                @if ($page->$value == 'published')
                                                    <span
                                                        class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    @else
                                                        <span
                                                            class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                @endif
                                            @endif
                                            {{ $page->$value }}
                                            @if ($key == 0)
                                                </a>
                                            @endif
                                            @if ($key == 2)
                                                </span>
                                            @endif
                                        </td>
                                    @endforeach

                                    <td class="px-4 py-3 whitespace-nowrap bg-white">
                                        <div class="flex justify-end items-center gap-1">

                                            <a 
                                            {{-- href="/admin/pages/show/{{ $page->id }}"  --}}
                                            class="flex justify-center">
                                                <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                            </a>



                                            <span 
                                            {{-- wire:click="selectItem({{ $page->id }}, 'delete')" --}}
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
