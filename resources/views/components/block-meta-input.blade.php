@props([
    'itemblocks',
    'metaKey',
    'placeholder' => '',
])

@php
    $value = $itemblocks->getMeta($metaKey) ?: '';

    // Existing values (classname / id-anchor) for quick reuse.
    $suggestions = in_array($metaKey, ['css-classname', 'id-anchor'], true)
        ? \Secondnetwork\Kompass\Models\Meta::where('key', $metaKey)
            ->whereNotNull('value')
            ->where('value', '!=', '')
            ->distinct()
            ->orderBy('value')
            ->pluck('value')
            ->all()
        : [];
@endphp

<div class="relative w-full"
    x-data="{
        open: false,
        val: @js($value),
        save() { this.$wire.saveBlockMeta({{ $itemblocks->id }}, '{{ $metaKey }}', this.val); }
    }"
    @click.outside="open = false">

    <div class="flex items-center gap-1">
        <input type="text" x-model="val" placeholder="{{ $placeholder }}"
            @change="save()" @keydown.enter.prevent="$event.target.blur()"
            class="w-full text-sm px-2 py-1 rounded-md border border-base-300 bg-transparent focus:outline-none focus:border-base-content/40" />

        @if (! empty($suggestions))
            <button type="button" @click="open = !open"
                class="shrink-0 flex items-center justify-center size-7 rounded hover:bg-base-200 text-base-content transition"
                :class="{ 'bg-base-200': open }">
                <x-tabler-playlist-add class="size-4 stroke-2" />
            </button>
        @endif
    </div>

    @if (! empty($suggestions))
        <ul x-show="open" x-cloak x-transition
            class="absolute z-20 left-0 right-0 mt-1 max-h-44 overflow-y-auto bg-base-100 border border-base-300 rounded-md shadow-lg py-1">
            @foreach ($suggestions as $suggestion)
                <li>
                    <button type="button" @click="val = @js($suggestion); open = false; save()"
                        class="w-full text-left px-3 py-1.5 text-sm hover:bg-base-200 transition"
                        :class="{ 'text-primary font-medium': val === @js($suggestion) }">
                        {{ $suggestion }}
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>
