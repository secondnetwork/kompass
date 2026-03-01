<div>
    <div x-cloak id="FormAdd" x-data="{ open: @entangle('FormAdd') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">

                <x-kompass::form.input label="{{ __('Name') }}" type="text" name="name" wire:model="name" />

                <x-kompass::select  label="{{ __('Group') }}" wire:model="group"
                :options="[
                            ['name' => __('Page'),  'id' => 'page'],
                            ['name' => __('Admin Sidebar'),  'id' => 'admin_aside']
                        ]">
                </x-kompass::select>

                @if (setting('global.multilingual'))
                <div class="mt-4">
                    <x-kompass::select wire:model="land" label="{{ __('Language') }}" :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])">
                    </x-kompass::select>
                </div>
                @endif

                <button wire:click="addMenu" class="btn btn-primary mt-4">{{ __('Save') }}</button>

            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div x-cloak id="FormClone" x-data="{ open: @entangle('FormClone').live }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">
                <h3 class="text-lg font-bold mb-4">{{ __('Clone Menu') }}</h3>
                
                @if (setting('global.multilingual'))
                <p class="mb-4 text-sm text-base-content/70">{{ __('Select the target language for the cloned menu.') }}</p>

                <div class="mt-4">
                    <x-kompass::select wire:model="cloneLand" label="{{ __('Language') }}" :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])">
                    </x-kompass::select>
                </div>
                @else
                <p class="mb-4 text-sm text-base-content/70">{{ __('Are you sure you want to clone this menu?') }}</p>
                @endif

                <button wire:click="cloneMenu" class="btn btn-primary mt-4">{{ __('Clone') }}</button>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::modal data="FormDelete" />


    <div class="flex flex-col">
        <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
            
            <div class="flex justify-end gap-4 items-center">
                @if (setting('global.multilingual'))
                <div class="w-44">
                    <x-kompass::select wire:model.live="land" label=" " :options="collect($available_locales)->map(fn($l) => ['name' => strtoupper($l), 'id' => $l])->prepend(['name' => __('All Languages'), 'id' => ''])">
                    </x-kompass::select>
                </div>
                @endif

                <button class="btn btn-primary" wire:click="$set('FormAdd', true)">
                    <x-tabler-list-details stroke-width="1.5" />{{ __('New menu') }}
                </button>
          </div>

        </div>

        <div class=" align-middle inline-block min-w-full ">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">



                @if ($menus->count())
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
                                @foreach ($menus as $key => $menu)
                                    <tr wire:sort:item="{{ $menu->id }}">
                                        <td wire:sort:handle class="pl-4 w-4 bg-base-100">
                                            <x-tabler-arrow-autofit-height
                                                class="cursor-move stroke-current  text-gray-400" />
                                        </td>


                                        @foreach ($data as $column)
                                            <td class="px-4 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                                @if ($column == 'name')
                                                    <div x-data="click_to_edit()" class="w-11/12 flex items-center gap-2">
                                                        <a @click.prevent @click="toggleEditingState" x-show="!isEditing" class="flex items-center select-none cursor-pointer" x-on:keydown.escape="isEditing = false">
                                                            <span class="text-sm font-semibold">{{  $menu->name }}</span>
                                                        </a>
                                                        @if (setting('global.multilingual'))
                                                            @if ($menu->land)
                                                                <span class="inline-flex items-center gap-1.5 py-1 px-2 rounded text-xs font-medium bg-blue-600 text-white">{{ strtoupper($menu->land) }}</span>
                                                            @endif
                                                        @endif
                                                        <div x-show=isEditing class="flex items-center" x-data="{id: '{{ $menu->id }}', name: '{{ $menu->name }}'}">
                                                            <input
                                                                type="text"
                                                                class="border border-gray-400 px-1 py-1 text-sm font-semibold"                 
                                                                x-model="name"
                                                                wire:model.lazy="newName" x-ref="input"
                                                                x-on:keydown.enter="isEditing = false"
                                                                x-on:keydown.escape="isEditing = false"
                                                                x-on:click.away="isEditing = false"
                                                                wire:keydown.enter="rename({{$menu->id }})"
                                                            >
                                                            <span wire:click="rename({{ $menu->id }})" x-on:click="isEditing = false">
                                                                <x-tabler-square-check class="cursor-pointer stroke-current h-6 w-6 text-green-600" />
                                                            </span>
                                                            <span x-on:click="isEditing = false">
                                                                <x-tabler-square-x class="cursor-pointer stroke-current h-6 w-6 text-red-600" />
                                                            </span>
                                                        </div>
                                                    </div>
                                                @elseif ($column == 'land')
                                                    <span class="text-xs font-medium uppercase">{{ $menu->land }}</span>
                                                @else
                                                    {{ $menu->$column }}
                                                @endif
                                            </td>
                                        @endforeach

                                        <td class="px-4 py-3 whitespace-nowrap bg-base-100">
                                            <div class="flex justify-end items-center gap-1">
                                                <a wire:navigate href="/admin/menus/show/{{ $menu->id }}"
                                                    class="flex justify-center">
                                                    <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                                </a>

                                                <span wire:click="selectItem({{ $menu->id }}, 'clone')" class="flex justify-center">
                                                    <x-tabler-copy class="cursor-pointer stroke-violet-500" />
                                                </span>

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
                        <div class="min-h-[60vh] flex flex-col items-center justify-center">
                            <x-tabler-layout-navbar stroke-width="1.5" class="w-16 h-16 mb-2 text-brand-500" />
                            <div class="text-lg font-semibold">{{__('No Data')}}</div>
                        </div>

                    @endif


                </div>
            </div>

        </div>

</div>
