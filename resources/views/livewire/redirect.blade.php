    <div class="flex flex-col">
        <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
            <div x-data="{ open: @entangle('FormAdd') }" class="flex justify-end gap-4">

                <button class="btn btn-primary" @click="open = true">
                    <x-tabler-square-plus stroke-width="1.5" />{{ __('Add') }}
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

                        <tbody class="bg-base-100 divide-y divide-gray-200 ">
                            @foreach ($pages as $key => $page)

                                    @foreach ($data as $key => $value)
                                        <td
                                            class="px-4 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                            @if ($key == 0)
                                                <a target="_blank" href="/{{ $page->slug }}">
                                            @endif
                                            @if ($key == 2)
                                                @if ($page->$value == 'published')
                                                    <span
                                                        class="badge badge-sm border-green-200 bg-green-100 text-green-800">
                                                    @else
                                                        <span
                                                            class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium border border-red-200 bg-red-100 text-red-800">
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

                                    <td class="px-4 py-3 whitespace-nowrap bg-base-100">
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
                    <div class="min-h-[60vh] flex flex-col items-center justify-center">
                        <x-tabler-arrow-forward-up stroke-width="1.5" class="w-16 h-16 mb-2 text-brand-500" />
                        <div class="text-lg font-semibold">{{__('No Data')}}</div>
                    </div>

                @endif


            </div>
        </div>

    </div>
