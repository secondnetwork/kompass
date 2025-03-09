@props([
    'item' => '',
])

@if ('accordiongroup' == $item->type)

@if ($item->getMeta('css-classname') == 'hero-page')
    <h1 class="grid justify-center uppercase fullpage ">@yield('title')</h1>
@endif


<div class="accordion">
@foreach ($item->children as $item)

@if ($item->type == 'wysiwyg')
<div x-data="accordion({{ $item->id }})" class="bg-white border border-[var(--grey-dark)] mb-4 rounded-lg">
    <div-nav-action :class="handleAc()"   class="flex items-center justify-between rounded-t-lg px-4 cursor-pointer">

    <span class="flex items-center py-4 w-full text-xl" @click="handleClick()">
        <strong >{{ $item->name }}</strong>
    </span>

    <div :class="handleRotate()"  class="transform transition-transform duration-500 ">
        <x-tabler-circle-plus stroke-width="1.5" @click="handleClick()" class="cursor-pointer stroke-current text-gray-900 " />
    </div>

    </div-nav-action>

    <div  x-ref="tab" :style="handleToggle()"  class="bg-white rounded-b-xl overflow-hidden max-h-0 duration-500 transition-all">
    <div class="px-4">
            <x-blocks.components :item="$item"  />
    </div>
    </div>
</div>
@endif

@if ($item->type == 'button')
    <x-blocks.button :item="$item"  />
@endif

@endforeach
</div>

@endif
