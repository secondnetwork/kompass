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

    {{-- Regenerate confirm modal --}}
    <div x-data="{ open: @entangle('FormRegenerate') }" @keydown.escape.window="open = false" :class="{ 'z-40': open }" class="relative">
        <template x-teleport="body">
            <div x-show="open" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    @click="open = false" class="absolute inset-0 w-full h-full bg-opacity-50 backdrop-blur-sm"></div>
                <div x-show="open" x-trap.inert.noscroll="open"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                    class="overflow-hidden relative w-full p-6 bg-base-100 shadow-md bg-opacity-90 drop-shadow-md backdrop-blur-sm sm:max-w-lg sm:rounded-lg">

                    <div class="relative w-max">
                        <div data-featured-icon="true"
                            class="relative flex shrink-0 items-center justify-center *:data-icon:size-6 rounded-full size-12 bg-primary/20 text-primary">
                            <x-tabler-replace />
                        </div>
                        <svg width="336" height="336" viewBox="0 0 336 336" fill="none"
                            class="opacity-30 pointer-events-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                            <mask id="mask_regen" style="mask-type: alpha;" maskUnits="userSpaceOnUse" x="0" y="0" width="336" height="336">
                                <rect width="336" height="336" fill="url(#paint_regen)" />
                            </mask>
                            <g mask="url(#mask_regen)">
                                <circle cx="168" cy="168" r="47.5" stroke="currentColor" />
                                <circle cx="168" cy="168" r="71.5" stroke="currentColor" />
                                <circle cx="168" cy="168" r="95.5" stroke="currentColor" />
                                <circle cx="168" cy="168" r="119.5" stroke="currentColor" />
                                <circle cx="168" cy="168" r="143.5" stroke="currentColor" />
                                <circle cx="168" cy="168" r="167.5" stroke="currentColor" />
                            </g>
                            <defs>
                                <radialGradient id="paint_regen" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                                    gradientTransform="translate(168 168) rotate(90) scale(168 168)">
                                    <stop />
                                    <stop offset="1" stop-opacity="0" />
                                </radialGradient>
                            </defs>
                        </svg>
                    </div>

                    <span @click="open = false" class="absolute top-3 right-4 cursor-pointer p-2 bg-gray-100 rounded-full hover:bg-gray-300 transition-all">
                        <x-tabler-x />
                    </span>

                    <div class="py-6 space-y-2">
                        <p class="font-semibold">{{ __('Regenerate view file') }}</p>
                        <p class="text-sm font-mono text-primary break-all">{{ $regenerateFileName }}</p>
                        <p class="text-sm text-base-content">{{ __('This will overwrite the existing view file with a new stub based on the current fields. Any custom changes you have made to the file will be lost.') }}</p>
                        
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                        <button @click="open = false" type="button" class="btn btn-neutral">{{ __('Cancel') }}</button>
                        <button wire:click="regenerateViewFile({{ $regenerateId }})" @click="open = false" type="button" class="btn btn-error">
                            <x-tabler-replace class="size-4" />
                            {{ __('Regenerate') }}
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

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

                                            <button wire:click="confirmRegenerate({{ $page->id }})"
                                                title="{{ __('Regenerate view file') }}"
                                                class="flex justify-center">
                                                <x-tabler-replace class="cursor-pointer stroke-current text-base-content/50 hover:stroke-primary" />
                                            </button>

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
