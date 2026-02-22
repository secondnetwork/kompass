<div>
    <div x-cloak id="FormAdd" x-data="{ open: @entangle('FormAdd') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">
                <x-kompass::form.input label="{{ __('Name') }}" type="text" name="name" wire:model="name" />

                <x-kompass::form.input label="{{ __('Slug') }}" type="text" name="slug" wire:model="slug" />

                <x-kompass::form.textarea wire:model="description" id="description" name="description"
                    label="{{ __('Description') }}" type="text" class="block w-full h-24" />

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('Color') }}</label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" wire:click="$set('color', 'primary')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'primary' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #3b82f6;" title="primary"></button>
                            <button type="button" wire:click="$set('color', 'secondary')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'secondary' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #a855f7;" title="secondary"></button>
                            <button type="button" wire:click="$set('color', 'accent')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'accent' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #f472b6;" title="accent"></button>
                            <button type="button" wire:click="$set('color', 'info')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'info' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #06b6d4;" title="info"></button>
                            <button type="button" wire:click="$set('color', 'success')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'success' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #22c55e;" title="success"></button>
                            <button type="button" wire:click="$set('color', 'warning')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'warning' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #eab308;" title="warning"></button>
                            <button type="button" wire:click="$set('color', 'error')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'error' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #ef4444;" title="error"></button>
                            <button type="button" wire:click="$set('color', 'neutral')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'neutral' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #71717a;" title="neutral"></button>
                        </div>
                        <div class="mt-2 text-sm text-base-content/70">{{ $color }}</div>
                    </div>
                    <div>
                        <x-kompass::form.input label="{{ __('Order') }}" type="number" name="order" wire:model="order" />
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('Icon') }}</label>
                    <x-kompass::form.input type="text" name="iconSearch" wire:model="iconSearch" placeholder="{{ __('Search icon...') }}" />

                    @if ($selectedIcon)
                        <div class="flex items-center gap-2 mt-2 p-2 bg-base-200 rounded">
                            <x-kompass::icon :name="$selectedIcon" class="w-6 h-6" />
                            <span class="text-sm flex-1">{{ $selectedIcon }}</span>
                            <button wire:click="resetIcon" class="btn btn-ghost btn-xs text-error">
                                <x-tabler-x class="w-4 h-4" />
                            </button>
                        </div>
                    @endif

                    @if (count($filteredIcons) > 0)
                        <div class="mt-2 max-h-40 overflow-y-auto border border-base-300 rounded bg-base-100">
                            <div class="grid grid-cols-8 gap-1 p-2">
                                @foreach ($filteredIcons as $iconItem)
                                    <button wire:click="selectIcon('{{ $iconItem['name'] }}')"
                                        class="p-2 hover:bg-base-200 rounded flex justify-center transition-colors">
                                        <x-kompass::icon :name="$iconItem['name']" class="w-5 h-5" />
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <button wire:click="save" class="btn btn-primary mt-6">
                    <div wire:loading>
                        <svg class="animate-spin h-5 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <x-tabler-device-floppy class="icon-lg" wire:loading.remove />
                    {{ __('Save') }}
                </button>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <div x-cloak id="FormEdit" x-data="{ open: @entangle('FormEdit') }">
        <x-kompass::offcanvas :w="'w-2/6'">
            <x-slot name="body">
                <h3 class="text-lg font-medium mb-4">{{ __('Edit Category') }}</h3>

                <x-kompass::form.input label="{{ __('Name') }}" type="text" name="name" wire:model="name" />

                <x-kompass::form.input label="{{ __('Slug') }}" type="text" name="slug" wire:model="slug" />

                <x-kompass::form.textarea wire:model="description" id="description" name="description"
                    label="{{ __('Description') }}" type="text" class="mt-4 block w-full h-24" />

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('Color') }}</label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" wire:click="$set('color', 'primary')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'primary' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #3b82f6;" title="primary"></button>
                            <button type="button" wire:click="$set('color', 'secondary')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'secondary' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #a855f7;" title="secondary"></button>
                            <button type="button" wire:click="$set('color', 'accent')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'accent' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #f472b6;" title="accent"></button>
                            <button type="button" wire:click="$set('color', 'info')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'info' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #06b6d4;" title="info"></button>
                            <button type="button" wire:click="$set('color', 'success')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'success' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #22c55e;" title="success"></button>
                            <button type="button" wire:click="$set('color', 'warning')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'warning' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #eab308;" title="warning"></button>
                            <button type="button" wire:click="$set('color', 'error')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'error' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #ef4444;" title="error"></button>
                            <button type="button" wire:click="$set('color', 'neutral')"
                                class="w-8 h-8 rounded-full border-2 {{ $color === 'neutral' ? 'border-gray-900 scale-110' : 'border-transparent hover:scale-105' }}"
                                style="background-color: #71717a;" title="neutral"></button>
                        </div>
                        <div class="mt-2 text-sm text-base-content/70">{{ $color }}</div>
                    </div>
                    <div>
                        <x-kompass::form.input label="{{ __('Order') }}" type="number" name="order" wire:model="order" />
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-base-content/70 mb-1">{{ __('Icon') }}</label>
                    <x-kompass::form.input type="text" name="iconSearch" wire:model="iconSearch" placeholder="{{ __('Search icon...') }}" />

                    @if ($selectedIcon)
                        <div class="flex items-center gap-2 mt-2 p-2 bg-base-200 rounded">
                            <x-kompass::icon :name="$selectedIcon" class="w-6 h-6" />
                            <span class="text-sm flex-1">{{ $selectedIcon }}</span>
                            <button wire:click="resetIcon" class="btn btn-ghost btn-xs text-error">
                                <x-tabler-x class="w-4 h-4" />
                            </button>
                        </div>
                    @endif

                    @if (count($filteredIcons) > 0)
                        <div class="mt-2 max-h-40 overflow-y-auto border border-base-300 rounded bg-base-100">
                            <div class="grid grid-cols-6 gap-1 p-2">
                                @foreach ($filteredIcons as $iconItem)
                                    <button wire:click="selectIcon('{{ $iconItem['name'] }}')"
                                        class="p-2 hover:bg-base-200 rounded flex justify-center transition-colors">
                                        <x-kompass::icon :name="$iconItem['name']" class="w-5 h-5" />
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <button wire:click="update" class="btn btn-primary mt-6">
                    <div wire:loading>
                        <svg class="animate-spin h-5 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <x-tabler-device-floppy class="icon-lg" wire:loading.remove />
                    {{ __('Update') }}
                </button>
            </x-slot>
        </x-kompass::offcanvas>
    </div>

    <x-kompass::modal data="FormDelete" />

    <div class="flex flex-col">
        <div class="border-gray-200 whitespace-nowrap text-sm flex gap-8 justify-between items-center">
            <div class="w-full">
                <x-kompass::form.input type="text" name="search" wire:model.live="search"
                    placeholder="{{ __('Search categories...') }}" />
            </div>

            <div x-data="{ open: @entangle('FormAdd').live }" class="flex justify-end gap-4">
                <button class="btn btn-primary" @click="open = true">
                    <x-tabler-square-plus stroke-width="1.5" />{{ __('New category') }}
                </button>
            </div>
        </div>

        <div class="divider"></div>

        <div class="align-middle inline-block min-w-full">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                @if ($categories->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-base-300">
                            <tr>
                                @foreach ($headers as $key => $value)
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                                        @if ($value == 'name')
                                            <button wire:click="sortBy('{{ $value }}')"
                                                class="flex items-center gap-1 uppercase font-medium">
                                                {{ __($value) }}
                                                @if ($orderBy === $value)
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

                        <tbody class="bg-base-100 divide-y divide-gray-200">
                            @foreach ($categories as $category)
                                <tr>
                                    @foreach ($data as $key => $value)
                                        <td
                                            class="px-4 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">
                                            @if ($value == 'color' && $category->color)
                                                <span
                                                    class="badge badge-{{ $category->color }}">{{ $category->color }}</span>
                                            @elseif ($value == 'icon' && $category->icon)
                                                <div class="flex items-center gap-2">
                                                    @if ($category->icon)
                                                        <x-kompass::icon :name="$category->icon" class="w-5 h-5" />
                                                    @endif
                                                    <span>{{ $category->icon }}</span>
                                                </div>
                                            @else
                                                {{ $category->$value }}
                                            @endif
                                        </td>
                                    @endforeach

                                    <td class="px-4 py-3 whitespace-nowrap bg-base-100">
                                        <div class="flex justify-end items-center gap-1">
                                            <span wire:click="selectItem({{ $category->id }}, 'edit')"
                                                class="flex justify-center cursor-pointer">
                                                <x-tabler-edit class="stroke-blue-500" />
                                            </span>
                                            <span wire:click="selectItem({{ $category->id }}, 'delete')"
                                                class="flex justify-center cursor-pointer">
                                                <x-tabler-trash class="stroke-red-500" />
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="min-h-[60vh] flex flex-col items-center justify-center">
                        <x-tabler-tags stroke-width="1.5" class="w-16 h-16 mb-2 text-brand-500" />
                        <div class="text-lg font-semibold">{{ __('No Data') }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>
