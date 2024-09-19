@props([
    'itemblocks' => '',
    'layout' => '',
    'type' => '',
    'slider' => '',
])


@php
    $layout = $itemblocks->layout ?? '';
    $alignment = $itemblocks->alignment ?? '';
    $slider = $itemblocks->slider ?? '';
@endphp

@if(!$itemblocks->subgroup == null)
<nav-item class="flex items-center gap-2">
    <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Layout Grid</span>
    <span class="cursor-pointer" x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '1')">
        @if ($itemblocks->layoutgrid == '1')
            <x-tabler-square-number-1 class="stroke-blue-500" />
        @else
            <x-tabler-square-number-1 />
        @endif
    </span>
    <span class="cursor-pointer" x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '2')">
        @if ($itemblocks->layoutgrid == '2')
            <x-tabler-square-number-2 class="stroke-blue-500" />
        @else
            <x-tabler-square-number-2 />
        @endif
    </span>
    <span class="cursor-pointer" x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '3')">
        @if ($itemblocks->layoutgrid == '3')
            <x-tabler-square-number-3 class="stroke-blue-500" />
        @else
            <x-tabler-square-number-3 />
        @endif
    </span>
    <span class="cursor-pointer" x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '4')">
        @if ($itemblocks->layoutgrid == '4')
            <x-tabler-square-number-4 class="stroke-blue-500" />
        @else
            <x-tabler-square-number-4 />
        @endif
    </span>
    <span class="cursor-pointer" x-data wire:click="updateLayoutGrid({{ $itemblocks->id }}, '5')">
        @if ($itemblocks->layoutgrid == '5')
            <x-tabler-square-number-5 class="stroke-blue-500" />
        @else
            <x-tabler-square-number-5 />
        @endif
    </span>
</nav-item>
@endif

<nav-item class="flex items-center gap-2">
    @if($itemblocks->subgroup == null)
    <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Layout</span>
    <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'layout', 'content')">
        @if ($layout == 'content')
            <x-tabler-columns-3 class="stroke-blue-500" />
        @else
            <x-tabler-columns-3 />
        @endif
    </span>
    <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'layout', 'popout')">
        @if ($layout == 'popout')
            <x-tabler-carousel-vertical class="stroke-blue-500" />
        @else
            <x-tabler-carousel-vertical />
        @endif
    </span>
    <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'layout', 'fullpage')">
        @if ($layout == 'fullpage')
            <x-tabler-arrow-autofit-width class="stroke-blue-500" />
        @else
            <x-tabler-arrow-autofit-width />
        @endif
    </span>
   
    @endif


    <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Classname</span>
    <div x-data="click_to_edit()" class="flex items-center">
        <a @click.prevent @click="toggleEditingState" x-show="!isEditing"
            class="flex items-center select-none cursor-text" x-on:keydown.escape="isEditing = false">
            <span class="text-sm font-semibold">{{ $itemblocks->getMeta('css-classname') }}</span>
            <x-tabler-edit class="cursor-pointer " />
        </a>
    <div x-show=isEditing class="flex items-center" x-data="{ id: '{{ $itemblocks->id }}', classname: '{{ $itemblocks->getMeta('css-classname')}}' }">

        <input type="text" class="border border-gray-400 px-1 py-1 text-sm font-semibold" x-model="classname"
            wire:model.lazy="newName" x-ref="input" x-on:keydown.enter="isEditing = false"
            x-on:keydown.escape="isEditing = false"
            x-on:click.away="isEditing = false" wire:keydown.enter="classname({{ $itemblocks->id }})">
        <span wire:click="classname({{ $itemblocks->id }})" x-on:click="isEditing = false">
            <x-tabler-square-check class="cursor-pointer stroke-current h-6 w-6 text-green-600" />
        </span>
        <span x-on:click="isEditing = false">
            <x-tabler-square-x class="cursor-pointer stroke-current h-6 w-6 text-red-600" />
        </span>
    </div>
    </div>

    <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">ID</span>
    <div x-data="click_to_edit()" class="flex items-center">
        <a @click.prevent @click="toggleEditingState" x-show="!isEditing"
            class="flex items-center select-none cursor-text" x-on:keydown.escape="isEditing = false">
            <span class="text-sm font-semibold">{{ $itemblocks->getMeta('id-anchor') }}</span>
            <x-tabler-edit class="cursor-pointer " />
        </a>
    <div x-show=isEditing class="flex items-center" x-data="{ id: '{{ $itemblocks->id }}', idanchor: '{{ $itemblocks->getMeta('id-anchor')}}' }">

        <input type="text" class="border border-gray-400 px-1 py-1 text-sm font-semibold" x-model="idanchor"
            wire:model.lazy="newName" x-ref="input" x-on:keydown.enter="isEditing = false"
            x-on:keydown.escape="isEditing = false"
            x-on:click.away="isEditing = false" wire:keydown.enter="idanchor({{ $itemblocks->id }})">
        <span wire:click="idanchor({{ $itemblocks->id }})" x-on:click="isEditing = false">
            <x-tabler-square-check class="cursor-pointer stroke-current h-6 w-6 text-green-600" />
        </span>
        <span x-on:click="isEditing = false">
            <x-tabler-square-x class="cursor-pointer stroke-current h-6 w-6 text-red-600" />
        </span>
    </div>
    </div>

     
</nav-item>     



@if ($itemblocks->type == 'wysiwyg')
    <nav-item class="flex items-center gap-2">
        <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300 ">{{ __('Alignment') }}</span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'alignment', 'align-left')">
            @if ($alignment == 'align-left')
                <x-tabler-align-left class="stroke-blue-500" />
            @else
                <x-tabler-align-left />
            @endif
        </span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'alignment', 'align-center')">
            @if ($alignment == 'align-center')
                <x-tabler-align-center class="stroke-blue-500" />
            @else
                <x-tabler-align-center />
            @endif
        </span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'alignment', 'align-right')">
            @if ($alignment == 'align-right')
                <x-tabler-align-right class="stroke-blue-500" />
            @else
                <x-tabler-align-right />
            @endif
        </span>

    </nav-item>
@endif
@if ($itemblocks->type == 'gallery')
    <nav-item class="flex items-center gap-2">
        <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300 ">Slider</span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'slider', '')">
            @if ($slider == '')
                <x-tabler-layout-dashboard class="stroke-blue-500 rotate-90" />
            @else
                <x-tabler-layout-dashboard class="rotate-90" />
            @endif
        </span>
        <span class="cursor-pointer" wire:click="saveset({{ $itemblocks->id }},'slider', 'true')">
            @if ($slider == 'true')
                <x-tabler-carousel-horizontal class="stroke-blue-500" />
            @else
                <x-tabler-carousel-horizontal />
            @endif
        </span>
    </nav-item>



    <nav-item class="flex items-center gap-2">
        <span class="text-sm font-medium px-2.5 py-0.5 rounded bg-gray-300">Grid</span>
        <span class="cursor-pointer" x-data wire:click="updateGrid({{ $itemblocks->id }}, '1')">
            @if ($itemblocks->grid == '1')
                <x-tabler-square-number-1 class="stroke-blue-500" />
            @else
                <x-tabler-square-number-1 />
            @endif
        </span>
        <span class="cursor-pointer" x-data wire:click="updateGrid({{ $itemblocks->id }}, '2')">
            @if ($itemblocks->grid == '2')
                <x-tabler-square-number-2 class="stroke-blue-500" />
            @else
                <x-tabler-square-number-2 />
            @endif
        </span>
        <span class="cursor-pointer" x-data wire:click="updateGrid({{ $itemblocks->id }}, '3')">
            @if ($itemblocks->grid == '3')
                <x-tabler-square-number-3 class="stroke-blue-500" />
            @else
                <x-tabler-square-number-3 />
            @endif
        </span>
        <span class="cursor-pointer" x-data wire:click="updateGrid({{ $itemblocks->id }}, '4')">
            @if ($itemblocks->grid == '4')
                <x-tabler-square-number-4 class="stroke-blue-500" />
            @else
                <x-tabler-square-number-4 />
            @endif
        </span>
        <span class="cursor-pointer" x-data wire:click="updateGrid({{ $itemblocks->id }}, '5')">
            @if ($itemblocks->grid == '5')
                <x-tabler-square-number-5 class="stroke-blue-500" />
            @else
                <x-tabler-square-number-5 />
            @endif
        </span>
    </nav-item>
@endif