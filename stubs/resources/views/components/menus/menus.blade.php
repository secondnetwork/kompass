@props(['menuitem' => null, 'level' => 0, 'class' => '', 'name' => null, 'horizontal' => null])
@php
    $isMain = $level == 0 && $name === 'main';
    $isHorizontal = $horizontal ?? $isMain;
    // A bare "#anchor" URL only scrolls within the current page. Prefixing it
    // with "/" makes it always target the homepage's section, so anchor links
    // still work when clicked from other pages (e.g. Impressum).
    $resolveUrl = fn ($url) => str_starts_with($url ?? '', '#') ? '/'.$url : $url;
@endphp
<div>

@isset($menuitem)

    <ul class="{{ $level == 0 ? ($isHorizontal ? 'menu menu-horizontal items-center px-0' : 'menu') : 'menu' }} {{ $class }}">
        @foreach ($menuitem as $item)
        <li x-data="{ open: false }"
            @mouseenter="open = true"
            @mouseleave="open = false"
            @click.away="open = false"
            @keydown.escape.window="open = false"
            class="relative">

            <a @class([
                    $item->iconclass,
                    'btn btn-lg rounded-none border-none bg-black hover:bg-neutral-800 text-white gap-2 ml-2' => $isMain && $loop->last,
                ])
               @click="{{ $item['children']->count() ? 'open = !open; $event.preventDefault();' : "typeof mobileMenuOpen !== 'undefined' && (mobileMenuOpen = false)" }}"
               href="{{ $resolveUrl($item->url) }}" target="{{ $item->target }}"
               rel="noopener noreferrer">

                @if ($item->iconclass)
                    @svg($item->iconclass, 'size-4')
                @endif

                {{ $item->title }}

                @if ($isMain && $loop->last)
                    <x-tabler-arrow-right class="size-4" />
                @endif

                @if ($item['children']->count())
                    <x-tabler-chevron-down class="size-3 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
                @endif
            </a>

            @if ($item['children']->count())
                @if ($level == 0 && $isHorizontal)
                    {{-- Desktop Dropdown --}}
                    <ul x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="menu absolute top-full left-0 mt-1 min-w-48 bg-base-100 text-base-content rounded-box border border-base-200 shadow-lg z-[100]">
                        @foreach ($item['children']->sortBy('order') as $child)
                            <li>
                                <a href="{{ $resolveUrl($child->url) }}" target="{{ $child->target }}"
                                   @click="typeof mobileMenuOpen !== 'undefined' && (mobileMenuOpen = false)">
                                    {{ $child->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @elseif ($level == 0)
                    {{-- Vertical (mobile) inline expand --}}
                    <ul x-show="open"
                        x-collapse
                        x-cloak
                        class="menu pl-4">
                        @foreach ($item['children']->sortBy('order') as $child)
                            <li>
                                <a href="{{ $resolveUrl($child->url) }}" target="{{ $child->target }}"
                                   @click="typeof mobileMenuOpen !== 'undefined' && (mobileMenuOpen = false)">
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
