@props(['itemblocks'])

@php
    $current = $itemblocks->getMeta('color') ?: '';
    $presets = [
        '#6366f1' => 'Indigo',
        '#10b981' => 'Emerald',
        '#0ea5e9' => 'Sky',
        '#8b5cf6' => 'Violet',
        '#f59e0b' => 'Amber',
    ];
@endphp

<div class="flex flex-col gap-3"
    x-data="{
        hex: @js($current),
        applyHex() {
            let v = (this.hex || '').trim();
            if (v && v[0] !== '#') v = '#' + v;
            if (/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(v)) {
                this.hex = v;
                this.$wire.saveset({{ $itemblocks->id }}, 'color', v);
            } else if (v === '' || v === '#') {
                this.hex = '';
                this.$wire.saveset({{ $itemblocks->id }}, 'color', '');
            } else {
                this.hex = @js($current);
            }
        }
    }">

    <div class="flex items-center gap-3">
        {{-- Current colour preview --}}
        <span class="shrink-0 size-8 rounded-full border border-base-300 flex items-center justify-center overflow-hidden"
            :style="hex ? ('background-color: ' + hex) : ''">
            <x-tabler-ban class="size-5 text-base-content/30" x-show="!hex" />
        </span>

        {{-- Palette --}}
        <div class="flex flex-wrap items-center gap-1.5">
            @foreach ($presets as $hex => $name)
                <button type="button" title="{{ $name }}"
                    @click="hex = '{{ $hex }}'"
                    wire:click="saveset({{ $itemblocks->id }}, 'color', '{{ $hex }}')"
                    class="size-6 rounded-full border-2 transition"
                    :class="hex === '{{ $hex }}' ? 'border-base-content ring-2 ring-offset-1 ring-base-content/30' : 'border-base-300 hover:scale-110'"
                    style="background-color: {{ $hex }}"></button>
            @endforeach

            {{-- Custom colour (native picker) --}}
            <label class="size-6 rounded-full border-2 border-dashed border-base-300 flex items-center justify-center cursor-pointer hover:border-base-content transition" title="{{ __('Custom') }}">
                <input type="color" class="sr-only" :value="hex || '#000000'"
                    @input="hex = $event.target.value; $wire.saveset({{ $itemblocks->id }}, 'color', $event.target.value)">
                <x-tabler-plus class="size-3" />
            </label>

            {{-- Reset --}}
            <button type="button" x-show="hex" @click="hex = ''" wire:click="saveset({{ $itemblocks->id }}, 'color', '')"
                class="size-6 rounded-full border-2 border-base-300 flex items-center justify-center hover:border-error hover:text-error transition" title="{{ __('Reset') }}">
                <x-tabler-x class="size-3" />
            </button>
        </div>
                    {{-- Hex input --}}
         <div class="w-30">
                <input type="text" maxlength="7" x-model="hex"
        @keydown.enter.prevent="applyHex()" @blur="applyHex()"
        placeholder="#RRGGBB"
        class="px-2 py-1 text-sm font-mono rounded-md border border-base-300 bg-transparent focus:outline-none focus:border-base-content/40" />
        </div>   
    </div>


</div>
