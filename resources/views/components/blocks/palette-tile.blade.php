@props(['entry'])

{{-- One add-block palette tile. $entry comes from block_registry()->palette(). --}}
<div class="{{ $entry['border'] }} border-2 rounded-lg p-2 m-2 cursor-pointer"
    wire:click="addBlock('{{ $entry['id'] }}','{{ $entry['name'] }}','{{ $entry['type'] }}','{{ $entry['icon'] }}')">

    @if (! empty($entry['image']))
        <img class="{{ $entry['image_class'] ?? '' }}" src="{{ $entry['image'] }}" alt="">
    @elseif (! empty($entry['icon_svg']))
        <div class="bg-gray-100 rounded-t-md w-full aspect-[16/12] relative flex justify-center pt-6 px-4 overflow-hidden">
            <div class="w-full h-full border-t-2 border-l-2 border-r-2 border-blue-500 bg-base-100 rounded-t-md flex justify-center items-center">
                <div class="relative flex justify-center items-center text-blue-500 size-16">
                    @svg(str_starts_with($entry['icon_svg'], 'tabler-') ? $entry['icon_svg'] : 'tabler-' . $entry['icon_svg'], 'text-blue-500 flex justify-center items-end w-full h-full')
                </div>
            </div>
        </div>
    @endif

    <span class="text-xs block mt-2">{{ $entry['name'] }}</span>
</div>
