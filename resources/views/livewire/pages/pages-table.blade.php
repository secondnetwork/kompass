<div class="flex flex-col">

    <div x-cloak id="FormAdd" x-data="{ open: @entangle('FormAdd').live }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">

                <x-kompass::form.input label="{{ __('Title') }}" type="text" name="title" wire:model="title" />
                <x-kompass::form.textarea wire:model="meta_description" id="name" name="Description"
                    label="{{ __('Description') }}" type="text" class="mt-1 block w-full h-[15rem]" />

                @if (setting('global.multilingual'))
                <div class="mt-4">
                    <x-kompass::select wire:model="land" label="{{ __('Language') }}" :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])">
                    </x-kompass::select>
                </div>
                @endif

                <button wire:click="addPage" class="btn btn-primary mt-4">{{ __('Save') }}</button>

            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div x-cloak id="FormClone" x-data="{ open: @entangle('FormClone').live }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">
                <h3 class="text-lg font-bold mb-4">{{ __('Clone Page') }}</h3>
                
                @if (setting('global.multilingual'))
                <p class="mb-4 text-sm text-base-content/70">{{ __('Select the target language for the cloned page.') }}</p>

                <div class="mt-4">
                    <x-kompass::select wire:model="cloneLand" label="{{ __('Language') }}" :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])">
                    </x-kompass::select>
                </div>
                @else
                <p class="mb-4 text-sm text-base-content/70">{{ __('Are you sure you want to clone this page?') }}</p>
                @endif

                <button wire:click="clonePage" class="btn btn-primary mt-4">{{ __('Clone') }}</button>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::modal data="FormDelete" />
    <x-kompass::action-message class="" on="status" />

    <div class="flex flex-col">
   
        <div class="w-full border-gray-200 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
            
            <div class="flex justify-end gap-4 items-center">
                @if (setting('global.multilingual'))
                <div class="w-44">
                    <x-kompass::select wire:model.live="land" label=" " :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])->prepend(['name' => __('All Languages'), 'id' => ''])">
                    </x-kompass::select>
                </div>
                @endif

                <button class="btn btn-primary" wire:click="$set('FormAdd', true)">
                    <x-tabler-square-plus stroke-width="1.5" />{{ __('New page') }}
                </button>
          </div>
       

        </div>

        <div class="divider"></div>
      
        <div class=" align-middle inline-block min-w-full h-full">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg h-full">



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
                                <tr wire:key="page-{{ $page->id }}" wire:sort:item="{{ $page->id }}">
                                    <td wire:sort:handle class="pl-4 w-4 bg-base-100">
                                        <x-tabler-arrow-autofit-height
                                            class="cursor-move stroke-current  text-gray-400" />
                                    </td>

                                    @foreach ($data as $column)
                                        <td class="px-4 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                            @if ($column == 'title')
                                                <a wire:navigate href="/admin/pages/show/{{ $page->id }}">
                                                    {{ __($page->title) }}
                                                </a>

                                                @if (setting('global.multilingual'))
                                                    @if ($page->land)
                                                        <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded text-xs font-medium bg-blue-600 text-white">{{ strtoupper($page->land) }}</span>
                                                    @endif
                                                @endif

                                                @if ($page->layout == 'is_front_page')
                                                    <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded text-xs font-medium bg-amber-500 text-white">
                                                        <x-tabler-home class="w-3 h-3" /> {{ __('Home') }}
                                                    </span>
                                                @endif

                                            @elseif ($column == 'status')
                                                @switch($page->status)
                                                    @case('published')
                                                        <span class="badge badge-sm border-green-200 bg-green-100 text-green-800">
                                                            <span class="relative flex h-2 w-2">
                                                                <span class="animate-[ping_3s_ease-in-out_infinite] absolute inline-flex h-full w-full rounded-full bg-teal-500 opacity-75"></span>
                                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-teal-500"></span>
                                                            </span>
                                                            {{ __('published') }}
                                                        </span>
                                                        @break

                                                    @case('password')
                                                        <span class="badge badge-sm border-violet-200 bg-violet-100 text-violet-800">
                                                            <span class="relative flex h-2 w-2">
                                                                <span class="animate-[ping_3s_ease-in-out_infinite] absolute inline-flex h-full w-full rounded-full bg-purple-500 opacity-75"></span>
                                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
                                                            </span>
                                                            {{ __('password') }}
                                                        </span>
                                                        @break

                                                    @default
                                                        <span class="badge badge-sm border-gray-300 bg-gray-900/10 text-gray-800">
                                                            <span class="relative flex h-2 w-2">
                                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-gray-500"></span>
                                                            </span>
                                                            {{ __('draft') }}
                                                        </span>
                                                @endswitch
                                            @elseif ($column == 'land')
                                                 <span class="text-xs font-medium uppercase">{{ $page->land }}</span>
                                            @else
                                                {{ $page->$column }}
                                            @endif
                                        </td>
                                    @endforeach

                                        <td class="px-4 py-3 whitespace-nowrap bg-base-100">
                                        <div class="flex justify-end items-center gap-1">



                                            <a wire:navigate href="/admin/pages/show/{{ $page->id }}" class="flex justify-center">
                                                <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                            </a>

                                            @if ($page->status == 'published')
                                                <span wire:click="status({{ $page->id }}, 'draft')">
                                                    <x-tabler-eye class="cursor-pointer stroke-gray-400" />
                                                </span>
                                            @else
                                                <span wire:click="status({{ $page->id }}, 'published')">
                                                    <x-tabler-eye-off class="cursor-pointer stroke-red-500" />
                                                </span>
                                            @endif

                                            @php
                                                $defaultLocale = config('app.locale', 'de');
                                                $langPrefix = ($page->land == $defaultLocale) ? '' : '/' . $page->land;
                                                $pageUrl = ($page->layout == 'is_front_page') ? url($langPrefix ?: '/') : url($langPrefix . '/' . $page->slug);
                                            @endphp
                                            <a target="_blank" href="{{ $pageUrl }}" class="flex justify-center">
                                                <x-tabler-external-link class="cursor-pointer stroke-gray-400" />
                                            </a>

                                            <span wire:click="selectItem({{ $page->id }}, 'clone')" class="flex justify-center">
                                                <x-tabler-copy class="cursor-pointer    stroke-violet-500" />
                                            </span>

                                            <span wire:click="selectItem({{ $page->id }}, 'delete')"
                                                class="flex justify-center">
                                                <x-tabler-trash class="cursor-pointer stroke-red-500" />
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="min-h-[60vh] flex flex-col items-center justify-center">
                        <x-tabler-file-text stroke-width="1.5" class="w-16 h-16 mb-2 text-brand-500" />
                        <div class="text-lg font-semibold">{{ __('No Data') }}</div>
                    </div>

                @endif


            </div>
        </div>

    </div>

</div>
