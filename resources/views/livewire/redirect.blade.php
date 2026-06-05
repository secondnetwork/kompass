<div class="flex flex-col">

    <div class="border-base-300 py-4 whitespace-nowrap text-sm flex gap-4 justify-between items-center">
        <div class="relative w-full max-w-xs">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="{{ __('Search') }}"
                class="input input-bordered w-full" />
        </div>

        <button class="btn btn-primary" wire:click="create">
            <x-tabler-square-plus stroke-width="1.5" />{{ __('Add') }}
        </button>
    </div>

    <div class="align-middle inline-block min-w-full">
        <div class="shadow overflow-hidden border-b border-base-300 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-50">
                <thead class="bg-base-300">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                            <button wire:click="sortBy('old_url')" class="flex items-center gap-1 uppercase font-medium">
                                {{ __('Old url') }}
                                @if ($orderBy === 'old_url')
                                    @if ($orderAsc)
                                        <x-tabler-chevron-up class="w-4 h-4" />
                                    @else
                                        <x-tabler-chevron-down class="w-4 h-4" />
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                            <button wire:click="sortBy('new_url')" class="flex items-center gap-1 uppercase font-medium">
                                {{ __('New url') }}
                                @if ($orderBy === 'new_url')
                                    @if ($orderAsc)
                                        <x-tabler-chevron-up class="w-4 h-4" />
                                    @else
                                        <x-tabler-chevron-down class="w-4 h-4" />
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                            {{ __('Status code') }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                            <button wire:click="sortBy('updated_at')" class="flex items-center gap-1 uppercase font-medium">
                                {{ __('Updated') }}
                                @if ($orderBy === 'updated_at')
                                    @if ($orderAsc)
                                        <x-tabler-chevron-up class="w-4 h-4" />
                                    @else
                                        <x-tabler-chevron-down class="w-4 h-4" />
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-base-content/70 uppercase"></th>
                    </tr>
                </thead>

                <tbody class="bg-base-100 divide-y divide-gray-50">
                    @forelse ($pages as $page)
                        <tr wire:key="redirect-{{ $page->id }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                <a target="_blank" href="{{ $page->old_url }}" class="hover:underline">
                                    {{ $page->old_url }}
                                </a>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                <a target="_blank" href="{{ $page->new_url }}" class="hover:underline">
                                    {{ $page->new_url }}
                                </a>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium bg-base-100">
                                <span
                                    class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $page->status_code }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                {{ $page->updated_at->isoFormat('dddd, D.M.Y HH:mm') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap bg-base-100">
                                <div class="flex justify-end items-center gap-2">
                                    <button wire:click="editItem({{ $page->id }})"
                                        class="flex justify-center" title="{{ __('Edit') }}">
                                        <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                    </button>
                                    <button wire:click="delete({{ $page->id }})"
                                        wire:confirm="{{ __('Delete this redirect?') }}"
                                        class="flex justify-center" title="{{ __('Delete') }}">
                                        <x-tabler-trash class="cursor-pointer stroke-red-500" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="min-h-[40vh] flex flex-col items-center justify-center">
                                    <x-tabler-arrow-forward-up stroke-width="1.5" class="w-16 h-16 mb-2 text-brand-500" />
                                    <div class="text-lg font-semibold">{{ __('No Data') }}</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $pages->links('kompass::livewire.pagination') }}
        </div>
    </div>

    {{-- Add / edit redirect modal --}}
    <div x-data="{ open: @entangle('FormAdd') }" x-cloak>
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

            <div class="relative bg-base-100 rounded-lg shadow-xl w-full max-w-lg mx-4 p-6"
                @keydown.escape.window="open = false">
                <h3 class="text-lg font-bold mb-4">
                    {{ $editId ? __('Edit redirect') : __('Create redirect') }}
                </h3>

                <div class="flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Old url') }}</label>
                        <input type="text" wire:model="old_url" placeholder="/old-page" class="input input-bordered w-full" />
                        @error('old_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('New url') }}</label>
                        <input type="text" wire:model="new_url" placeholder="/" class="input input-bordered w-full" />
                        @error('new_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Status code') }}</label>
                        <select wire:model="status_code" class="select select-bordered w-full">
                            <option value="301">301 ({{ __('Permanent') }})</option>
                            <option value="302">302 ({{ __('Temporary') }})</option>
                            <option value="410">410 ({{ __('Gone') }})</option>
                        </select>
                        @error('status_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button class="btn btn-ghost" @click="open = false">{{ __('Cancel') }}</button>
                    <button class="btn btn-primary" wire:click="save">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
