<div>

    <div x-cloak id="FormAdd" x-data="{ open: @entangle('FormAdd') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">

                <x-kompass::form.input wire:model.live="name" label="{{ __('Name') }}" type="text" class="mt-1 block w-full" />
                <x-kompass::form.input wire:model="type" label="{{ __('Type / Slug') }}" type="text" class="mt-1 block w-full bg-gray-100" readonly />
                <button wire:click="saveBlock" class="btn btn-primary">{{ __('Save') }}</button>

            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::modal data="FormDelete" />

    <div class="flex flex-col">
        <div class="flex items-end justify-between gap-4 flex-wrap p-5 bg-base-100 border border-base-300 rounded-t-xl">
            <div>
                <h6 class="font-semibold text-lg">{{ __('Blocks') }}</h6>
                <p class="text-xs opacity-60">{{ __('Manage your content blocks') }}</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap justify-end">
                <div class="w-full sm:w-64">
                    <x-kompass::table-search wire:model.live="search" placeholder="{{ __('Search blocks...') }}" />
                </div>

                <div x-data="{ open: @entangle('FormAdd') }">
                    <button class="btn btn-primary" @click="open = true">
                        <x-tabler-square-plus stroke-width="1.5" />{{ __('New block') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="align-middle inline-block min-w-full">
            <div class="overflow-hidden rounded-b-xl border border-t-0 border-base-300 bg-base-100">

                @if ($pages->count())
                    <table class="min-w-full divide-y divide-base-200 [&_tbody_tr:hover_td]:bg-base-200/50">
                        <thead class="bg-base-200">
                            @foreach ($headers as $key => $value)
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                                    @if($value == 'name' || $value == 'type')
                                        <button wire:click="sortBy('{{ $value }}')" class="flex items-center gap-1 uppercase font-medium">
                                            {{ __($value) }}
                                            @if($orderBy === $value)
                                                @if($orderAsc)
                                                    <x-tabler-chevron-up class="w-4 h-4" />
                                                @else
                                                    <x-tabler-chevron-down class="w-4 h-4" />
                                                @endif
                                            @endif
                                        </button>
                                    @else
                                        {{ __($value) }}
                                    @endif
                                </th>
                            @endforeach

                        </thead>


                        <tbody class="bg-base-100 divide-y divide-base-200" wire:sort="handleSort">
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

                    <x-kompass::table-footer :paginator="$pages" />
                @else
                    <div class="min-h-[60vh] flex flex-col items-center justify-center">
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
