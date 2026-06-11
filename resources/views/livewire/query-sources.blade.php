<div>

    <div class="flex flex-col">
        <div class="flex items-end justify-between gap-4 flex-wrap p-5 bg-base-100 border border-base-300 rounded-t-xl">
            <div>
                <h6 class="font-semibold text-lg">{{ __('Query sources') }}</h6>
                <p class="text-xs opacity-60">{{ __('Manage the data sources for the relationship block') }}</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap justify-end">
                <div class="w-full sm:w-64">
                    <x-kompass::table-search wire:model.live.debounce.300ms="search" placeholder="{{ __('Search sources...') }}" />
                </div>

                <button class="btn btn-primary" wire:click="create">
                    <x-tabler-square-plus stroke-width="1.5" />{{ __('Add') }}
                </button>
            </div>
        </div>

        <div class="align-middle inline-block min-w-full">
            <div class="overflow-hidden rounded-b-xl border border-t-0 border-base-300 bg-base-100">

                @if ($sources->count())
                    <table class="min-w-full divide-y divide-base-200 [&_tbody_tr:hover_td]:bg-base-200/50">
                        <thead class="bg-base-200">
                            <tr>
                                <th scope="col" class="pl-4 w-4"></th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                                    <button wire:click="sortBy('label')" class="flex items-center gap-1 uppercase font-medium">
                                        {{ __('Label') }}
                                        @if ($orderBy === 'label')
                                            @if ($orderAsc)
                                                <x-tabler-chevron-up class="w-4 h-4" />
                                            @else
                                                <x-tabler-chevron-down class="w-4 h-4" />
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">
                                    <button wire:click="sortBy('key')" class="flex items-center gap-1 uppercase font-medium">
                                        {{ __('Key') }}
                                        @if ($orderBy === 'key')
                                            @if ($orderAsc)
                                                <x-tabler-chevron-up class="w-4 h-4" />
                                            @else
                                                <x-tabler-chevron-down class="w-4 h-4" />
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">{{ __('Model') }}</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-base-content/70 uppercase">{{ __('Item view') }}</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-base-content/70 uppercase"></th>
                            </tr>
                        </thead>

                        <tbody class="bg-base-100 divide-y divide-base-200" wire:sort="reorder">
                            @foreach ($sources as $source)
                                <tr wire:key="query-source-{{ $source->id }}" wire:sort:item="{{ $source->id }}">
                                    <td wire:sort:handle class="pl-4 w-4 bg-base-100" title="{{ __('Drag to reorder') }}">
                                        <x-tabler-arrow-autofit-height class="cursor-move stroke-current text-gray-400" />
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-base-content bg-base-100">{{ $source->label }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm bg-base-100">
                                        <code class="text-xs">{{ $source->key }}</code>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm bg-base-100">
                                        <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                            {{ $source->model_key }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-base-content/70 bg-base-100">
                                        {{ $source->item_view ?: '—' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap bg-base-100">
                                        <div class="flex justify-end items-center gap-1">
                                            <button wire:click="editItem({{ $source->id }})" class="flex justify-center" title="{{ __('Edit') }}">
                                                <x-tabler-edit class="cursor-pointer stroke-blue-500" />
                                            </button>
                                            <span wire:click="delete({{ $source->id }})"
                                                wire:confirm="{{ __('Delete this source?') }}"
                                                class="flex justify-center cursor-pointer" title="{{ __('Delete') }}">
                                                <x-tabler-trash class="cursor-pointer stroke-red-500" />
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <x-kompass::table-footer :paginator="$sources" />
                @else
                    <div class="min-h-[60vh] flex flex-col items-center justify-center">
                        <div class="flex flex-col items-center">
                            <x-tabler-database stroke-width="1.5" class="w-16 h-16 mb-2 text-brand-500" />
                            <div class="font-semibold text-lg">{{ __('No Data') }}</div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Add / edit source offcanvas --}}
    <div x-cloak id="FormAdd" x-data="{ open: @entangle('FormAdd') }">
        <x-kompass::offcanvas :w="'w-3/4'">
            <x-slot name="button">
                <h3 class="text-lg font-bold">
                    {{ $editId ? __('Edit source') : __('Create source') }}
                </h3>
            </x-slot>

            <x-slot name="body">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Label') }}</label>
                        <input type="text" wire:model="label" class="input input-bordered w-full" />
                        @error('label') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Key') }}</label>
                        <input type="text" wire:model="key" placeholder="team"
                            @if ($editId) readonly @endif
                            class="input input-bordered w-full @if ($editId) bg-base-200 cursor-not-allowed @endif" />
                        <span class="text-xs @if ($editId) text-red-500 @else text-base-content/50 @endif">
                            @if ($editId)
                                {{ __('The key is locked because saved blocks reference it.') }}
                            @else
                                {{ __('Stable identifier used by saved blocks. Avoid renaming later.') }}
                            @endif
                        </span>
                        @error('key') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <x-kompass::select
                        wire:model="model_key"
                        :searchable="false"
                        label="{{ __('Model') }}"
                        placeholder="{{ __('— Select —') }}"
                        :options="collect($modelOptions)->map(fn ($optionLabel, $value) => ['id' => $value, 'name' => $optionLabel])->values()->all()" />

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Display fields') }}</label>
                        <input type="text" wire:model="display_fields" placeholder="title, created_at" class="input input-bordered w-full" />
                        <span class="text-xs text-base-content/50">{{ __('Comma separated. The first field is the title / link text; the rest are extra info. Also used for search.') }}</span>
                        @error('display_fields') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Order fields') }}</label>
                        <input type="text" wire:model="order_fields" placeholder="created_at, title" class="input input-bordered w-full" />
                        <span class="text-xs text-base-content/50">{{ __('Comma separated. First is the default.') }}</span>
                        @error('order_fields') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Status filter') }}</label>
                        <input type="text" wire:model="status_filter" placeholder="published" class="input input-bordered w-full" />
                        @error('status_filter') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Scope') }}</label>
                        <input type="text" wire:model="scope" placeholder="active" class="input input-bordered w-full" />
                        <span class="text-xs text-base-content/50">{{ __('Eloquent local scope name, e.g. "active" calls scopeActive(). Applied if the model defines it.') }}</span>
                        @error('scope') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('URL pattern') }}</label>
                        <input type="text" wire:model="url_pattern" placeholder="/blog/{slug}" class="input input-bordered w-full" />
                        <span class="text-xs text-base-content/50">{{ __('Leave empty for no link. "{slug}" is replaced per record.') }}</span>
                        @error('url_pattern') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <x-kompass::select
                        wire:model="item_view"
                        :searchable="true"
                        label="{{ __('Item view') }}"
                        placeholder="{{ __('Plain title link (default)') }}"
                        :options="collect($itemViewOptions)
                            ->map(fn ($view) => ['id' => $view, 'name' => $view])
                            ->prepend(['id' => '', 'name' => __('Plain title link (default)')])
                            ->values()
                            ->all()" />

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Wrapper class') }}</label>
                        <input type="text" wire:model="wrapper_class" placeholder="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" class="input input-bordered w-full" />
                        @error('wrapper_class') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Eager loads') }}</label>
                        <input type="text" wire:model="with" placeholder="category" class="input input-bordered w-full" />
                        <span class="text-xs text-base-content/50">{{ __('Comma separated relations to avoid N+1.') }}</span>
                        @error('with') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button class="btn btn-ghost" @click="open = false">{{ __('Cancel') }}</button>
                    <button class="btn btn-primary" wire:click="save">{{ __('Save') }}</button>
                </div>
            </x-slot>
        </x-kompass::offcanvas>
    </div>
</div>
