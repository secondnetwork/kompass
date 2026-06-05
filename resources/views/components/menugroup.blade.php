@props([
    'key' => '',
    'item' => '',
    'fields' => '',
    'page' => '',
    'class' => '',
])

@php
    $isChild = (bool) $item->subgroup;
    $hasChildren = isset($item->children) && $item->children->isNotEmpty();

    // Hierarchy colour coding: top-level group (with children) = indigo,
    // top-level link = slate/blue, nested child = lighter slate.
    if ($isChild) {
        $rail = 'border-l-slate-300';
        $accent = 'text-slate-400';
    } elseif ($hasChildren) {
        $rail = 'border-l-indigo-500';
        $accent = 'text-indigo-600';
    } else {
        $rail = 'border-l-sky-500';
        $accent = 'text-sky-600';
    }
@endphp

<div class="itemblock bg-base-100 border border-base-300 border-l-4 {{ $rail }} rounded-md shadow-sm mt-2 {{ $class }}"
    x-data="{ expanded: true }"
    wire:sort:item="{{ $item->id }}"
    wire:key="group-{{ $item->id }}">

    <div-nav-action class="flex items-center justify-between gap-2 px-3 py-1.5">

        {{-- Left: drag + chevron + icon + title + url --}}
        <span class="flex items-center min-w-0 flex-1 gap-1">
            <span wire:sort:handle class="shrink-0 cursor-move text-base-content transition-colors">
                <x-tabler-grip-vertical class="size-5" />
            </span>

            @if ($hasChildren)
                <button type="button" @click="expanded = !expanded"
                    class="shrink-0 {{ $accent }} transition-transform duration-200"
                    :class="expanded ? 'rotate-90' : ''" title="{{ __('Expand / collapse') }}">
                    <x-tabler-chevron-right class="size-4" />
                </button>
            @endif

            <span class="shrink-0 flex items-center justify-center size-7 rounded-md {{ $accent }}">
                @if ($item->iconclass)
                    @svg(str_starts_with($item->iconclass, 'tabler-') ? $item->iconclass : 'tabler-'.$item->iconclass, 'size-5')
                @elseif ($isChild)
                    <x-tabler-corner-down-right class="size-5" />
                @elseif ($hasChildren)
                    <x-tabler-folder class="size-5" />
                @else
                    <x-tabler-link class="size-5" />
                @endif
            </span>

            <div x-data="click_to_edit()" class="flex items-center gap-2 min-w-0">
                <a @click.prevent @click="toggleEditingState" x-show="!isEditing"
                    class="flex items-center gap-2 min-w-0 select-none cursor-pointer group"
                    x-on:keydown.escape="isEditing = false">
                    <span class="text-sm font-semibold truncate">{{ $item->title }}</span>
                    <x-tabler-pencil class="shrink-0 size-3.5 text-base-content/0 group-hover:text-base-content/40 transition-colors" />
                </a>

                @if ($item->url)
                    <a x-show="!isEditing" target="_blank" href="{{ $item->url }}"
                        class="hidden sm:inline-flex items-center gap-1.5 max-w-[18rem] truncate text-sm font-medium text-sky-700 bg-sky-50 hover:bg-sky-100 ring-1 ring-sky-200 rounded-md px-2.5 py-1 transition-colors">
                        <x-tabler-external-link class="size-4 shrink-0" />
                        <span class="truncate">{{ $item->url }}</span>
                    </a>
                @endif

                <div x-show="isEditing" x-cloak class="flex items-center gap-1"
                    x-data="{ id: '{{ $item->id }}', name: '{{ $item->title }}' }">
                    <input type="text"
                        class="text-sm font-semibold rounded-md border border-base-300 bg-base-100 px-2 py-1 focus:outline-none focus:border-primary"
                        x-model="name"
                        wire:model.lazy="newName" x-ref="input"
                        x-on:keydown.enter="isEditing = false"
                        x-on:keydown.escape="isEditing = false"
                        x-on:click.away="isEditing = false"
                        wire:keydown.enter="savename({{ $item->id }})">
                    <span wire:click="savename({{ $item->id }})" x-on:click="isEditing = false">
                        <x-tabler-square-check class="cursor-pointer size-6 text-green-600" />
                    </span>
                    <span x-on:click="isEditing = false">
                        <x-tabler-square-x class="cursor-pointer size-6 text-red-500" />
                    </span>
                </div>
            </div>
        </span>

        {{-- Right: actions --}}
        <div class="flex items-center gap-0.5 shrink-0">
            <button type="button" wire:click="selectItem({{ $item->id }}, 'update', {{ $item->subgroup ?? 'null' }})"
                class="flex items-center justify-center size-7 rounded hover:bg-base-200 transition-colors" title="{{ __('Edit') }}">
                <x-tabler-edit class="size-5 text-blue-500" />
            </button>

            @unless ($isChild)
                <button type="button" wire:click="selectItem({{ $item->menu_id }}, 'additem', {{ $item->id }})"
                    class="flex items-center justify-center size-7 rounded hover:bg-base-200 transition-colors" title="{{ __('Add submenu') }}">
                    <x-tabler-subtask class="size-5 text-indigo-600" />
                </button>
            @endunless

            <button type="button" wire:click="selectItem({{ $item->id }}, 'deleteblock')"
                class="flex items-center justify-center size-7 rounded hover:bg-red-50 transition-colors" title="{{ __('Delete') }}">
                <x-tabler-trash class="size-5 text-red-500" />
            </button>
        </div>

    </div-nav-action>

    {{-- Children (also a drop target for nesting items via drag) --}}
    @if ($hasChildren)
        <div x-show="expanded" x-collapse class="border-t border-base-300 bg-base-200/40 px-2 pb-2 rounded-b-md">
            <div wire:sort="handleSort" wire:sort:group="menuitems" wire:sort:group-id="{{ $item->id }}"
                class="min-h-[0.5rem]">
                <x-kompass::menugroupsub :childrensub="$item['children']->sortBy('order')" />
            </div>
        </div>
    @else
        <div wire:sort="handleSort" wire:sort:group="menuitems" wire:sort:group-id="{{ $item->id }}"
            class="min-h-[0.25rem] rounded-b-md"></div>
    @endif

</div>
