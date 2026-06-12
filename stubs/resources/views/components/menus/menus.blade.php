@props(['menuitem' => null, 'level' => 0])
<div>

@isset($menuitem)

    <ul class="{{ $level == 0 ? 'menu menu-horizontal px-0' : 'menu' }}">
        @foreach ($menuitem as $item)
        <li x-data="{ open: false }"
            @mouseenter="open = true"
            @mouseleave="open = false"
            @click.away="open = false"
            @keydown.escape.window="open = false"
            class="relative">

            <a @if ($item->iconclass) class="{{ $item->iconclass }}" @endif
               @click="{{ $item['children']->count() ? 'open = !open; $event.preventDefault();' : 'true' }}"
               href="{{ $item->url }}" target="{{ $item->target }}"
               rel="noopener noreferrer">

                @if ($item->iconclass)
                    @svg($item->iconclass, 'size-4')
                @endif

                {{ $item->title }}

                @if ($item['children']->count())
                    <x-tabler-chevron-down class="size-3 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
                @endif
            </a>

            @if ($item['children']->count())
                @if ($level == 0)
                    {{-- Desktop Dropdown --}}
                    <ul x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="menu absolute top-full left-0 mt-1 min-w-48 bg-base-100 rounded-box border border-base-200 shadow-lg z-[100]">
                        @foreach ($item['children']->sortBy('order') as $child)
                            <li>
                                <a href="{{ $child->url }}" target="{{ $child->target }}">
                                    {{ $child->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    {{-- Nested Dropdown --}}
                    <div class="absolute left-full top-0 ml-1">
                        <x-menus.menus-chlidren :childrensub="$item['children']->sortBy('order')" :level="$level + 1" />
                    </div>
                @endif
            @endif

        </li>
        @endforeach
    </ul>

@endisset

</div>
