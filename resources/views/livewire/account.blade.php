<div>

    <x-kompass::modal data="FormDelete" />

    <div x-cloak x-data="{ open: @entangle('FormEdit') }">


        <div x-cloak x-data="{ open: @entangle('FormEdit') }">
            <x-kompass::offcanvas :w="'w-2/6'">
              <x-slot name="body">
                    <div class="grid gap-4">
                    <x-kompass::input wire:model="name" label="Name" />

                    <x-kompass::input wire:model="email" label="{{ __('E-Mail Address') }}" />

                    <label>{{ __('Role') }}</label>
                    <div wire:ignore>

                        <select wire:model="role" class="relative cursor-pointer w-full select-none pl-3 pr-10 py-2 text-base">
                                <option>select</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
            
                    <div class="modal-footer mt-auto">
                        <button wire:click="createOrUpdateUser" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
                </x-slot>

            </x-kompass::offcanvas>
    
    </div>


    <div class="">

        <div class="border-gray-200 py-4 whitespace-nowrap text-sm flex gap-8 justify-end items-center">
          <input wire:model.live="search" type="text" class="block p-2 w-full border-2 border-gray-300 text-base rounded-md" placeholder="{{ __('User') }} {{ __('Search') }}...">

            <button class="flex btn gap-x-2 justify-center items-center text-md" wire:click="selectItem(1, 'add')"><x-tabler-user-plus stroke-width="1.5" />{{ __('Create Account') }}</button>
        </div>

        <div class=" align-middle inline-block min-w-full ">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        @foreach ($headers as $key => $value)
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                {{ __($value) }}
                            </th>
                        @endforeach

                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @if ($users->count())

                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="relative block">
                                                    <span
                                                        class="absolute inset-0 z-0 flex items-center justify-center text-[#36424A] bg-[#FFA700] rounded-full h-10 w-10 text-base">
                                                        {{ nameWithLastInitial($user->name) }}
                                                    </span>
                                                    <img class="absolute rounded-full h-10 w-10 z-10 items-center justify-center flex"
                                                        src="{{ $user->profile_photo_url }}" alt="">
                                                </div>


                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $user->name }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                     <td class="px-4 py-2 whitespace-nowrap">
                                        @empty($user->email_verified_at)
                                            <span
                                                class="px-2 inline-flex font-semibold rounded-md text-xs bg-red-300 text-red-800">
                                                no Active {{ $user->email_verified_at }}
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex font-semibold rounded text-xs bg-green-100 text-green-800">
                                                {{ \Carbon\Carbon::parse($user->email_verified_at)->format('d.m.Y H:i') }}
                                            </span>
                                @endif

                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    @foreach ($user->roles as $user_role)
                                        {{ $user_role->display_name }}
                                    @endforeach


                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-right">
                                    <div class="flex justify-end items-center gap-1">
                                        <span wire:click="selectItem('{{ $user->id }}', 'update')"
                                            class="flex justify-center"><x-tabler-edit
                                                class="cursor-pointer stroke-blue-500" /></span>
                                        <span wire:click="selectItem('{{ $user->id }}', 'delete')"
                                            class="flex justify-center"><x-tabler-trash
                                                class="cursor-pointer stroke-red-500" /></span>
                                    </div>
                                </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>
                                  <div class="flex justify-center p-4">{{ __('No Data') }}</div></td>
                            </tr>
                            @endif
                            <!-- More people... -->
                        </tbody>
                    </table>


                </div>
            </div>




        </div>
    </div>
