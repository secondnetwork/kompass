@props([
    'item' => '',
])
@php
    ['colSpan' => $colSpan] = block_grid_classes($item);
@endphp
<div x-data="{ open: null }" class="space-y-4 {{ $colSpan }}">

    @foreach ($item->children as $child)
        @if ($child->type == 'wysiwyg')
            <div class="p-6 rounded-lg border border-base-300 bg-base-100">
                <div class="flex justify-between items-center cursor-pointer"
                    @click="open === {{ $loop->index }} ? open = null : open = {{ $loop->index }}">
                    <span class="font-semibold text-lg">
                        <span class="hidden md:inline mr-4">{{ sprintf('%02d', $loop->iteration) }}</span>
                        {{ $child->name }}
                    </span>
                    <svg class="w-6 h-6 text-gray-500 transition-transform duration-300"
                        :class="open === {{ $loop->index }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <div x-show="open === {{ $loop->index }}" x-collapse x-cloak>
                    <div class="mt-4 prose max-w-none text-gray-700">
                        <x-blocks.components :item="$child" />
                    </div>
                </div>
            </div>
        @endif

        @if ($child->type == 'button')
            <x-blocks.button :item="$child" />
        @endif
    @endforeach

</div>
