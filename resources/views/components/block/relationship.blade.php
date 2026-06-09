@props(['itemblocks', 'search' => ''])

@php
    $models       = query_models();
    $selectedKey  = get_meta($itemblocks, 'query-model', '');
    $selected     = $models[$selectedKey] ?? null;
    $orderFields  = $selected['order_fields'] ?? [];
    $currentOrder = get_meta($itemblocks, 'query-order', $orderFields[0] ?? '');
    $currentDir   = strtolower((string) get_meta($itemblocks, 'query-direction', 'desc')) === 'asc' ? 'asc' : 'desc';
    $currentLimit = (int) (get_meta($itemblocks, 'query-limit', 5));
    $mode         = get_meta($itemblocks, 'query-mode') === 'manual' ? 'manual' : 'auto';
    $selectedIds  = get_meta($itemblocks, 'query-ids');
    $selectedIds  = is_array($selectedIds) ? array_map('intval', $selectedIds) : [];
    $labelField   = $selected['label_field'] ?? 'title';
    $records      = $selected ? kompass_query($itemblocks) : collect();
@endphp

<div class="rounded-lg border border-dashed border-teal-300 bg-teal-50/40 p-4 space-y-4">

    {{-- Source model --}}
    <div class="flex items-center gap-2">
        <span class="text-xs font-semibold uppercase tracking-wide text-teal-700 w-28 shrink-0">{{ __('Source') }}</span>
        <select class="select select-sm select-bordered w-full"
            wire:change="saveBlockMeta({{ $itemblocks->id }}, 'query-model', $event.target.value)">
            <option value="">{{ __('— Select —') }}</option>
            @foreach ($models as $key => $definition)
                <option value="{{ $key }}" @selected($selectedKey === $key)>{{ __($definition['label'] ?? $key) }}</option>
            @endforeach
        </select>
    </div>

    @if ($selected)
        {{-- Mode: automatic query vs. manual selection --}}
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold uppercase tracking-wide text-teal-700 w-28 shrink-0">{{ __('Mode') }}</span>
            <div class="join">
                <button type="button"
                    class="btn btn-xs join-item {{ $mode === 'auto' ? 'btn-primary' : 'btn-ghost' }}"
                    wire:click="saveBlockMeta({{ $itemblocks->id }}, 'query-mode', 'auto')">
                    {{ __('Automatic') }}
                </button>
                <button type="button"
                    class="btn btn-xs join-item {{ $mode === 'manual' ? 'btn-primary' : 'btn-ghost' }}"
                    wire:click="saveBlockMeta({{ $itemblocks->id }}, 'query-mode', 'manual')">
                    {{ __('Manual') }}
                </button>
            </div>
        </div>

        @if ($mode === 'auto')
            {{-- Order by --}}
            <div class="flex items-center gap-2">
                <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Order by') }}</span>
                <select class="select select-sm select-bordered w-full"
                    wire:change="saveBlockMeta({{ $itemblocks->id }}, 'query-order', $event.target.value)">
                    @foreach ($orderFields as $field)
                        <option value="{{ $field }}" @selected($currentOrder === $field)>{{ $field }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Direction --}}
            <div class="flex items-center gap-2">
                <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Direction') }}</span>
                <div class="flex items-center gap-1">
                    <span class="cursor-pointer rounded p-0.5 transition-colors {{ $currentDir === 'desc' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                        wire:click="saveBlockMeta({{ $itemblocks->id }}, 'query-direction', 'desc')">
                        <x-tabler-sort-descending class="{{ $currentDir === 'desc' ? 'stroke-blue-500' : '' }}" />
                    </span>
                    <span class="cursor-pointer rounded p-0.5 transition-colors {{ $currentDir === 'asc' ? 'bg-blue-50' : 'hover:bg-neutral-100' }}"
                        wire:click="saveBlockMeta({{ $itemblocks->id }}, 'query-direction', 'asc')">
                        <x-tabler-sort-ascending class="{{ $currentDir === 'asc' ? 'stroke-blue-500' : '' }}" />
                    </span>
                </div>
            </div>

            {{-- Limit --}}
            <div class="flex items-center gap-2">
                <span class="text-xs text-neutral-500 w-28 shrink-0 leading-tight">{{ __('Limit') }}</span>
                <input type="number" min="1" max="100" value="{{ $currentLimit }}"
                    class="input input-sm input-bordered w-full"
                    wire:change="saveBlockMeta({{ $itemblocks->id }}, 'query-limit', $event.target.value)">
            </div>

            {{-- Live result preview --}}
            <div class="border-t border-teal-100 pt-3">
                <div class="mb-1 text-xs text-neutral-400">{{ __('Result') }} ({{ $records->count() }})</div>
                @if ($records->isEmpty())
                    <p class="text-sm text-neutral-500">{{ __('No records found for the current query.') }}</p>
                @else
                    <ul class="divide-y divide-teal-100">
                        @foreach ($records as $record)
                            <li wire:key="rel-{{ $itemblocks->id }}-{{ $record->id }}" class="py-1.5 text-sm text-neutral-700">
                                {{ $record->{$labelField} ?? ('#' . $record->id) }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @else
            {{-- Manual selection: two lists side by side — available (left) and selected (right) --}}
            <div class="grid grid-cols-2 gap-3">

                {{-- Available records --}}
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500">{{ __('Available') }}</span>

                    {{-- Server-side search over the source's label field --}}
                    <div class="relative mt-1">
                        <x-tabler-search class="absolute left-2 top-1/2 -translate-y-1/2 size-4 text-neutral-400" />
                        <input type="search" placeholder="{{ __('Search…') }}"
                            wire:model.live.debounce.300ms="relationshipSearch.{{ $itemblocks->id }}"
                            class="input input-sm input-bordered w-full pl-8">
                        <span wire:loading wire:target="relationshipSearch.{{ $itemblocks->id }}"
                            class="absolute right-2 top-1/2 -translate-y-1/2">
                            <span class="loading loading-spinner loading-xs text-neutral-400"></span>
                        </span>
                    </div>

                    <div class="mt-1 max-h-64 overflow-y-auto rounded border border-base-300 divide-y divide-base-200 bg-base-100">
                        @php $available = kompass_query_candidates($selectedKey, 50, $search)->reject(fn ($r) => in_array((int) $r->id, $selectedIds, true)); @endphp
                        @forelse ($available as $record)
                            <button type="button" wire:key="avail-{{ $itemblocks->id }}-{{ $record->id }}"
                                wire:click="toggleQueryRecord({{ $itemblocks->id }}, {{ $record->id }})"
                                class="flex items-center gap-2 w-full px-2 py-1.5 text-sm text-left hover:bg-base-200">
                                <x-tabler-plus class="size-4 shrink-0 text-teal-600" />
                                <span class="truncate">{{ $record->{$labelField} ?? ('#' . $record->id) }}</span>
                            </button>
                        @empty
                            <p class="px-2 py-2 text-xs text-neutral-400">
                                {{ trim((string) $search) !== '' ? __('No matches.') : __('Nothing left to add.') }}
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- Selected records (in saved order, drag to reorder) --}}
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500">{{ __('Selected') }} ({{ $records->count() }})</span>
                    <div class="mt-1 max-h-[19rem] overflow-y-auto rounded border border-base-300 divide-y divide-base-200 bg-base-100"
                        wire:sort="reorderQueryRecord">
                        @forelse ($records as $record)
                            <div wire:key="sel-{{ $itemblocks->id }}-{{ $record->id }}"
                                wire:sort:item="{{ $itemblocks->id }}-{{ $record->id }}"
                                class="flex items-center gap-2 px-2 py-1.5 text-sm bg-base-100">
                                <span wire:sort:handle class="cursor-move shrink-0 text-neutral-400 hover:text-neutral-600">
                                    <x-tabler-grip-vertical class="size-4" />
                                </span>
                                <span class="text-xs text-primary w-4 shrink-0">{{ $loop->iteration }}</span>
                                <span class="truncate flex-1">{{ $record->{$labelField} ?? ('#' . $record->id) }}</span>
                                <button type="button"
                                    wire:click="toggleQueryRecord({{ $itemblocks->id }}, {{ $record->id }})"
                                    class="shrink-0">
                                    <x-tabler-x class="size-4 text-red-500 hover:text-red-700" />
                                </button>
                            </div>
                        @empty
                            <p class="px-2 py-2 text-xs text-neutral-400">{{ __('No records selected yet.') }}</p>
                        @endforelse
                    </div>
                </div>

            </div>
        @endif
    @else
        <div class="flex items-center gap-2 text-sm text-neutral-500">
            <x-tabler-database class="size-5" />
            <span>{{ __('Choose a source to query above.') }}</span>
        </div>
    @endif
</div>
