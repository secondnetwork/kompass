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

    <div class="flex flex-col">

        <div class="flex justify-end">
            <button class="btn btn-primary" wire:click="selectItem('', 'add')">
                <x-tabler-lock-access stroke-width="1.5" />
                {{ __('Add') }}
            </button>
        </div>

        <div class="divider"></div>

        <div class="shadow overflow-hidden border-b border-base-300 sm:rounded-lg">

            @if ($roles->count())
                <table class="min-w-full divide-y divide-gray-50">
                    <thead class="bg-base-300">
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
                    <tbody class="bg-base-100 divide-y divide-gray-50">
                        @foreach ($roles as $role)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                    {{ $role->display_name }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap bg-base-100">
                                    <div class="flex justify-end items-center gap-1">
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
            @else
                <div class="min-h-[60vh] flex flex-col items-center justify-center">
                    <x-tabler-lock-access stroke-width="1.5" class="w-16 h-16 mb-2 text-base-content/30" />
                    <div class="text-lg font-semibold">{{ __('No Data') }}</div>
                </div>
            @endif

        </div>
    </div>
</div>
