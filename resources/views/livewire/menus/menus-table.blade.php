<div>
    <div x-cloak id="FormAdd" x-data="{ open: @entangle('FormAdd') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">

                <x-kompass::form.input label="Name" type="text" name="name" wire:model="name" />
                <x-kompass::input-error for="name" class="mt-2" />

                <x-kompass::select  label="Select" wire:model="group"
                :options="[
                            ['name' => __('Page'),  'id' => 'page'],
                            ['name' => __('Admin Sidebar'),  'id' => 'admin_aside']
                        ]">
                </x-kompass::select>

                <button wire:click="addMenu" class="btn btn-primary">Save</button>

            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::modal data="FormDelete" />


    <div class="flex flex-col">
        <div class=" border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
            <div x-data="{ open: @entangle('FormAdd') }" class="flex justify-end gap-4">

                <button class="flex btn gap-x-2 justify-center items-center text-md" @click="open = true">
                    <x-tabler-list-details stroke-width="1.5" />{{ __('New menu') }}
                </button>

            </div>
        </div>

        <div class=" align-middle inline-block min-w-full ">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">



                @if ($menus->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            @foreach ($headers as $key => $value)
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
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
                                                    {{-- {{ $menu->$value }} --}}
                                                    <div x-data="click_to_edit()" class="w-11/12 flex items-center">
                                                    <a @click.prevent @click="toggleEditingState" x-show="!isEditing" class="flex items-center select-none cursor-pointer" x-on:keydown.escape="isEditing = false">
                          
                                                        <span class="text-sm font-semibold">{{  $menu->$value }}</span>
                                                        
                                                        
                                                        {{-- <span><x-tabler-edit class="cursor-pointer stroke-current h-6 w-6 text-gray-400 hover:text-blue-500" /></span> --}}
                                                    </a>  
                                                    <div x-show=isEditing class="flex items-center" x-data="{id: '{{ $menu->id }}', name: '{{ $menu->$value }}'}">
                                
                                                        <input
                                                            type="text"
                                                            class="border border-gray-400 px-1 py-1 text-sm font-semibold"                 
                                                            x-model="name"
                                                            wire:model.lazy="newName" x-ref="input"
                                                            x-on:keydown.enter="isEditing = false"
                                                            x-on:keydown.escape="isEditing = false"
                                                            {{-- @keydown.window.escape="disableEditing"  --}}
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
                                            </td>
                                        @endforeach

                                        <td class="px-4 py-3 whitespace-nowrap bg-white">
                                            <div class="flex justify-end items-center gap-1">
                                                <a wire:navigate href="/admin/menus/show/{{ $menu->id }}"
                                                    class="flex justify-center">
                                                    <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                                </a>

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
