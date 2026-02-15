@props(['menuitem' => null, 'level' => 0])
<div>

@isset($menuitem)

    <ul class="{{ $level == 0 ? 'flex flex-col md:flex-row gap-4' : 'flex flex-col' }}">
        @foreach ($menuitem as $item)
        <li x-data="{ open: false }"
            @mouseenter="open = true"
            @mouseleave="open = false"
            @click.away="open = false"
            @keydown.escape.window="open = false"
            class="relative flex items-center {{ $level > 0 ? 'py-1' : '' }}">

            <a @if ($item->iconclass) class="{{ 'tabler-'.$item->iconclass }} flex items-center gap-1  group relative" @else class="flex items-center gap-1 group relative" @endif 
               @click="{{ $item['children']->count() ? 'open = !open; $event.preventDefault();' : 'true' }}"
               href="{{$item->url}}" target="{{$item->target}}"
               rel="noopener noreferrer">

                @if ($item->iconclass)
                @svg('tabler-'.$item->iconclass)
                @endif
                <span class="absolute bottom-0 w-full transition duration-150 ease-out transform border-b border-black/50 opacity-0 group-hover:opacity-100 group-hover:-translate-y-1"></span>
                <span>{{$item->title}}</span>

            </a>




            @if ($item['children']->count())
                <x-tabler-chevron-down class="size-4 ml-1 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />

                @if($level == 0)
                    {{-- Desktop Mega Menu Dropdown --}}
                    <div x-show="open"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute max-md:left-0 md:right-0 top-full z-50 mt-1 w-2xs max-w-3xl bg-violet-900 shadow-xl rounded-xl  overflow-hidden"
                         style="display: none;">

                        <div class="p-6">
                             <div class="">
                                @foreach($item['children']->sortBy('order') as $child)
                                    <a href="{{ $child->url }}" target="{{ $child->target }}" class="group block p-3 rounded-lg hover:bg-violet-800 transition-colors">
                                        <div class="font-bold text-white group-hover:text-primary transition-colors">
                                            {{ $child->title }}
                                        </div>
                                        @if($child->description)
                                            <div class="text-sm text-gray-500 line-clamp-2 mt-1">
                                                {{ $child->description }}
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                             </div>
                        </div>
                    </div>

                    {{-- Mobile Accordion List --}}
                    {{-- <div x-show="open"
                         x-cloak
                         class="md:hidden absolute left-0 top-full z-50 mt-1 w-full bg-white shadow-lg rounded-lg border border-gray-100"
                         style="display: none;">
                        @foreach($item['children']->sortBy('order') as $child)
                            <a href="{{ $child->url }}" target="{{ $child->target }}" class="block py-3 px-4 text-gray-700 hover:bg-gray-50 hover:text-primary border-b border-gray-100 last:border-0">
                                {{ $child->title }}
                            </a>
                        @endforeach
                    </div> --}}
                @else
                    {{-- Standard Dropdown for Level > 0 (if needed) --}}
                    <div class="absolute left-0 top-full z-50 mt-0">
                        <x-menus.menus-chlidren :childrensub="$item['children']->sortBy('order')" :level="$level + 1"/>
                    </div>
                @endif
            @endif


        </li>
        @endforeach
    </ul>

@endisset

</div>