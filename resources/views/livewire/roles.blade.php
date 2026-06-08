<div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4">{{ session('message') }}</div>
    @endif

    <x-kompass::modal data="FormDelete" />

    <div x-cloak x-data="{ open: @entangle('FormEdit') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">
                <div class="grid gap-4">
                    <x-kompass::input wire:model="display_name" label="{{ __('Display Name') }}" />
                    <x-kompass::input wire:model="name" label="{{ __('Role Name') }}" />
                    <button wire:click="createOrUpdateRole" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    {{-- Assign permissions to a single role --}}
    <div x-cloak x-data="{ open: @entangle('FormPermissions') }">
        <x-kompass::offcanvas :w="'w-2/5'">
            <x-slot name="body">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h6 class="font-semibold text-lg">{{ __('Assign permissions') }}</h6>
                        <p class="text-xs opacity-60">{{ $permissionRoleName }}</p>
                    </div>
                    <button type="button" class="btn btn-ghost btn-sm gap-1" @click="open = false" wire:click="openPermissionManager">
                        <x-tabler-settings class="size-4" />{{ __('Manage permissions') }}
                    </button>
                </div>

                @if ($permissions->isEmpty())
                    <div class="flex flex-col items-center justify-center gap-2 py-12 text-center text-base-content/60">
                        <x-tabler-key class="size-10 opacity-40" />
                        <p class="text-sm">{{ __('No permissions yet') }}</p>
                        <button type="button" class="btn btn-primary btn-sm mt-2 gap-1" @click="open = false" wire:click="openPermissionManager">
                            <x-tabler-plus class="size-4" />{{ __('New permission') }}
                        </button>
                    </div>
                @else
                    <div class="grid gap-4 max-h-[60vh] overflow-y-auto pr-1">
                        @foreach ($permissionGroups as $group => $groupPermissions)
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-base-content/50 mb-1">{{ $group }}</div>
                                <div class="grid gap-0.5">
                                    @foreach ($groupPermissions as $permission)
                                        <label class="flex items-center gap-2 px-2 py-1.5 rounded-md hover:bg-base-200 cursor-pointer">
                                            <input type="checkbox" class="checkbox checkbox-sm checkbox-primary"
                                                value="{{ $permission->name }}" wire:model="selectedPermissions" />
                                            <span class="text-sm">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button wire:click="savePermissions" class="btn btn-primary mt-4 gap-1">
                        <x-tabler-device-floppy class="size-5" />{{ __('Save permissions') }}
                    </button>
                @endif
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    {{-- Global permission manager (create / delete) --}}
    <div x-cloak x-data="{ open: @entangle('FormManagePermissions') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">
                <div>
                    <h6 class="font-semibold text-lg">{{ __('Manage permissions') }}</h6>
                    <p class="text-xs opacity-60">{{ __('Create and remove permissions') }}</p>
                </div>

                <form wire:submit.prevent="createPermission" class="flex items-end gap-2 mt-2">
                    <div class="flex-1">
                        <x-kompass::form.input type="text" name="newPermissionName" wire:model="newPermissionName"
                            label="{{ __('Permission name') }}" placeholder="posts.create" />
                    </div>
                    <button type="submit" class="btn btn-primary gap-1">
                        <x-tabler-plus class="size-5" />{{ __('Add') }}
                    </button>
                </form>
                @error('newPermissionName')
                    <span class="text-error text-xs">{{ $message }}</span>
                @enderror

                <div class="mt-4 grid gap-1 max-h-[60vh] overflow-y-auto pr-1">
                    @forelse ($permissions as $permission)
                        <div class="flex items-center justify-between gap-2 px-3 py-2 rounded-md border border-base-200">
                            <span class="text-sm font-medium truncate">{{ $permission->name }}</span>
                            <button wire:click="deletePermission({{ $permission->id }})"
                                class="shrink-0 text-error hover:bg-error/10 rounded p-1 transition" title="{{ __('Delete') }}">
                                <x-tabler-trash class="size-4" />
                            </button>
                        </div>
                    @empty
                        <p class="text-sm text-base-content/50 py-6 text-center">{{ __('No permissions yet') }}</p>
                    @endforelse
                </div>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div class="flex flex-col">

        <div class="flex items-end justify-between gap-4 flex-wrap p-5 bg-base-100 border border-base-300 rounded-t-xl">
            <div>
                <h6 class="font-semibold text-lg">{{ __('Roles') }}</h6>
                <p class="text-xs opacity-60">{{ __('Manage roles and permissions') }}</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap justify-end">
                <div class="w-full sm:w-64">
                    <x-kompass::table-search wire:model.live="search" placeholder="{{ __('Search roles...') }}" />
                </div>
                <button class="btn btn-ghost border border-base-300 gap-1" wire:click="openPermissionManager">
                    <x-tabler-key stroke-width="1.5" />
                    {{ __('Permissions') }}
                </button>
                <button class="btn btn-primary" wire:click="selectItem('', 'add')">
                    <x-tabler-lock-access stroke-width="1.5" />
                    {{ __('Add') }}
                </button>
            </div>
        </div>

        <div class="overflow-hidden rounded-b-xl border border-t-0 border-base-300 bg-base-100">

            @if ($roles->count())
                <table class="min-w-full divide-y divide-base-200 [&_tbody_tr:hover_td]:bg-base-200/50">
                    <thead class="bg-base-200">
                        <tr>
                            @foreach ($headers as $key => $value)
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                                    @if ($key == 'name')
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
                        @foreach ($roles as $role)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                    {{ $role->display_name }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm bg-base-100">
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-base-300 px-2.5 py-0.5 text-xs text-base-content/70">
                                        <x-tabler-key class="size-3.5 opacity-50" />
                                        {{ $role->permissions_count }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap bg-base-100">
                                    <div class="flex justify-end items-center gap-1">
                                        <span wire:click="selectItem({{ $role->id }}, 'permissions')" title="{{ __('Assign permissions') }}">
                                            <x-tabler-key class="cursor-pointer stroke-amber-500" />
                                        </span>
                                        <span wire:click="selectItem({{ $role->id }}, 'update')">
                                            <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                        </span>
                                        <span wire:click="selectItem({{ $role->id }}, 'delete')">
                                            <x-tabler-trash class="cursor-pointer stroke-red-500" />
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <x-kompass::table-footer :paginator="$roles" />
            @else
                <div class="min-h-[60vh] flex flex-col items-center justify-center">
                    <x-tabler-lock-access stroke-width="1.5" class="w-16 h-16 mb-2 text-base-content/30" />
                    <div class="text-lg font-semibold">{{ __('No Data') }}</div>
                </div>
            @endif

        </div>
    </div>
</div>
