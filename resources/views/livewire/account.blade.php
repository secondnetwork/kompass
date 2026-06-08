<div>

    <x-kompass::modal data="FormDelete" />

    <div x-cloak x-data="{ open: @entangle('FormEdit') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">
                <div class="grid gap-4">
                    <x-kompass::input wire:model="name" label="{{ __('Name') }}" />
                    <x-kompass::input wire:model="email" label="{{ __('E-Mail Address') }}" />
                    <div wire:ignore>
                        <x-kompass::select wire:model="role" :searchable="false" label="{{ __('Role') }}"
                            placeholder="{{ __('Select') }}" :options="$roles" />
                    </div>
                    <button wire:click="createOrUpdateUser" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div class="flex flex-col">

        <div class="flex items-end justify-between gap-4 flex-wrap p-5 bg-base-100 border border-base-300 rounded-t-xl">
            <div>
                <h6 class="font-semibold text-lg">{{ __('User account') }}</h6>
                <p class="text-xs opacity-60">{{ __('Manage users and access') }}</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap justify-end">
                <div class="w-full sm:w-64">
                    <x-kompass::table-search wire:model.live="search" placeholder="{{ __('Search') }}..." />
                </div>
                <button class="btn btn-primary" wire:click="selectItem(1, 'add')">
                    <x-tabler-user-plus stroke-width="1.5" />
                    {{ __('Create Account') }}
                </button>
            </div>
        </div>

        <div class="overflow-hidden rounded-b-xl border border-t-0 border-base-300 bg-base-100">

            @if ($users->count())
                <table class="min-w-full divide-y divide-base-200 [&_tbody_tr:hover_td]:bg-base-200/50">
                    <thead class="bg-base-200">
                        <tr>
                            @foreach ($headers as $key => $value)
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                                    @if (in_array($key, ['name', 'status']))
                                        <button wire:click="sortBy('{{ $key }}')" class="flex items-center gap-1 uppercase font-medium">
                                            {{ __($value) }}
                                            @if ($orderBy === $key)
                                                @if ($orderAsc)
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
                        </tr>
                    </thead>
                    <tbody class="bg-base-100 divide-y divide-base-200">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                    <div class="flex items-center gap-3">
                                        <x-kompass::elements.avatar :user="$user" size="w-9" />
                                        <div>
                                            <div class="font-medium">{{ $user->name }}</div>
                                            <div class="text-xs text-base-content/50">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm bg-base-100">
                                    @empty($user->email_verified_at)
                                        <span class="badge badge-sm border-red-200 bg-red-100 text-red-800">{{ __('Not verified') }}</span>
                                    @else
                                        <span class="badge badge-sm border-green-200 bg-green-100 text-green-800">
                                            {{ \Carbon\Carbon::parse($user->email_verified_at)->format('d.m.Y') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-base-content/70 bg-base-100">
                                    @foreach ($user->roles as $user_role)
                                        <span class="badge badge-sm border-blue-200 bg-blue-100 text-blue-800">{{ $user_role->display_name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap bg-base-100">
                                    <div class="flex justify-end items-center gap-1">
                                        @if ($user->id === auth()->id())
                                            <a href="{{ route('admin.profile') }}" title="{{ __('Edit Profile') }}">
                                                <x-tabler-user-edit class="cursor-pointer stroke-blue-500" />
                                            </a>
                                        @else
                                            <span wire:click="selectItem('{{ $user->id }}', 'update')">
                                                <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                            </span>
                                        @endif
                                        @if ($user->id !== auth()->id())
                                            <span wire:click="selectItem('{{ $user->id }}', 'delete')">
                                                <x-tabler-trash class="cursor-pointer stroke-red-500" />
                                            </span>
                                        @else
                                            <x-tabler-trash class="stroke-base-content/20 cursor-not-allowed" />
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <x-kompass::table-footer :paginator="$users" />
            @else
                <div class="min-h-[60vh] flex flex-col items-center justify-center">
                    <x-tabler-users stroke-width="1.5" class="w-16 h-16 mb-2 text-base-content/30" />
                    <div class="text-lg font-semibold">{{ __('No Data') }}</div>
                </div>
            @endif

        </div>
    </div>
</div>
