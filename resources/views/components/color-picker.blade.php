@props([
    'value' => '',          // current colour: #rrggbb | #rrggbbaa | '' (empty)
    'name' => null,         // optional hidden input for plain form posts
    'swatches' => null,     // optional override of the preset palette
    'allowEmpty' => true,   // show a "clear" control and support an empty value
    'placeholder' => '#rrggbb',
])

@php
    $palette = $swatches ?: config('kompass.color_picker.swatches', [
        '#ef4444', '#f97316', '#eab308', '#22c55e', '#14b8a6',
        '#3b82f6', '#6366f1', '#a855f7', '#ec4899', '#111827',
    ]);
@endphp

<div
    data-slot="color-picker"
    x-data="{
        open: false,
        allowEmpty: @js((bool) $allowEmpty),
        empty: true,
        hex: '#000000',
        hue: 0,
        alpha: 1,
        input: '',
        swatches: @js(array_values((array) $palette)),

        init() {
            this.parse(@js($value));
        },

        // Seed internal state from any incoming value (or empty).
        parse(v) {
            const n = this.normalize(v);
            if (!n) {
                this.empty = this.allowEmpty;
                this.hex = '#000000';
                this.alpha = 1;
                this.hue = 0;
                this.input = this.empty ? '' : '#000000';
                return;
            }
            this.empty = false;
            this.hex = n.hex;
            this.alpha = n.alpha;
            this.hue = this.hexToHue(n.hex);
            this.input = this.build();
        },

        // Accept #rgb / #rgba / #rrggbb / #rrggbbaa; return {hex:'#rrggbb', alpha:0..1} or null.
        normalize(v) {
            if (typeof v !== 'string') return null;
            let s = v.trim().replace(/^#/, '').toLowerCase();
            if (s === '') return null;
            if (/^[0-9a-f]{3}$/.test(s)) { s = s.split('').map((c) => c + c).join(''); }
            else if (/^[0-9a-f]{4}$/.test(s)) { s = s.split('').map((c) => c + c).join(''); }
            if (/^[0-9a-f]{6}$/.test(s)) { return { hex: '#' + s, alpha: 1 }; }
            if (/^[0-9a-f]{8}$/.test(s)) { return { hex: '#' + s.slice(0, 6), alpha: parseInt(s.slice(6, 8), 16) / 255 }; }
            return null;
        },

        // Compose the emitted value: '' when empty, #rrggbb when opaque, else #rrggbbaa.
        build() {
            if (this.empty) return '';
            const a = Math.round(this.alpha * 255);
            if (a >= 255) return this.hex;
            return this.hex + a.toString(16).padStart(2, '0');
        },

        // CSS colour for previews (always renders the alpha).
        get css() {
            if (this.empty) return 'transparent';
            const a = Math.round(this.alpha * 255);
            return a >= 255 ? this.hex : this.hex + a.toString(16).padStart(2, '0');
        },

        hslToHex(h) {
            const s = 0.9, l = 0.5;
            const c = (1 - Math.abs(2 * l - 1)) * s;
            const x = c * (1 - Math.abs(((h / 60) % 2) - 1));
            const m = l - c / 2;
            let r = 0, g = 0, b = 0;
            if (h < 60)       { r = c; g = x; b = 0; }
            else if (h < 120) { r = x; g = c; b = 0; }
            else if (h < 180) { r = 0; g = c; b = x; }
            else if (h < 240) { r = 0; g = x; b = c; }
            else if (h < 300) { r = x; g = 0; b = c; }
            else              { r = c; g = 0; b = x; }
            const to = (n) => Math.round((n + m) * 255).toString(16).padStart(2, '0');
            return '#' + to(r) + to(g) + to(b);
        },

        hexToHue(hex) {
            const n = this.normalize(hex);
            if (!n) return 0;
            const r = parseInt(n.hex.slice(1, 3), 16) / 255;
            const g = parseInt(n.hex.slice(3, 5), 16) / 255;
            const b = parseInt(n.hex.slice(5, 7), 16) / 255;
            const max = Math.max(r, g, b), min = Math.min(r, g, b), d = max - min;
            if (d === 0) return 0;
            let h = 0;
            if (max === r)      { h = ((g - b) / d) % 6; }
            else if (max === g) { h = (b - r) / d + 2; }
            else                { h = (r - g) / d + 4; }
            h = Math.round(h * 60);
            return h < 0 ? h + 360 : h;
        },

        setHue(h) {
            this.empty = false;
            this.hue = Number(h);
            this.hex = this.hslToHex(this.hue);
            this.input = this.build();
            this.emit();
        },

        setAlpha(a) {
            this.empty = false;
            this.alpha = Number(a) / 100;
            this.input = this.build();
            this.emit();
        },

        // Live-validate the text field; only commit when it parses.
        commit(v) {
            if (this.allowEmpty && v.trim() === '') {
                this.clear();
                return;
            }
            const n = this.normalize(v);
            if (n) {
                this.empty = false;
                this.hex = n.hex;
                this.alpha = n.alpha;
                this.hue = this.hexToHue(n.hex);
                this.emit();
            }
        },

        // On blur, snap the visible text back to the canonical value.
        sync() { this.input = this.build(); },

        pick(c) {
            const n = this.normalize(c);
            if (!n) return;
            this.empty = false;
            this.hex = n.hex;
            this.alpha = n.alpha;
            this.hue = this.hexToHue(n.hex);
            this.input = this.build();
            this.emit();
        },

        clear() {
            this.empty = true;
            this.input = '';
            this.emit();
        },

        // Notify the parent via a bubbling event; $event.detail is the value string.
        emit() { this.$dispatch('changed', this.build()); },

        get alphaPct() { return Math.round(this.alpha * 100); },
        get isValid() { return (this.allowEmpty && this.input.trim() === '') || this.normalize(this.input) !== null; },
    }"
    x-init="init()"
    {{ $attributes->merge(['class' => 'relative inline-block text-start']) }}
>
    @if ($name)
        <input type="hidden" name="{{ $name }}" :value="build()">
    @endif

    {{-- Trigger --}}
    <button
        type="button"
        aria-haspopup="dialog"
        :aria-expanded="open"
        @click="open = !open"
        class="btn btn-sm btn-block justify-start font-mono normal-case border border-base-300"
    >
        <span class="relative size-4 overflow-hidden rounded-sm border border-base-300"
            style="background-image: linear-gradient(45deg,#ccc 25%,transparent 25%),linear-gradient(-45deg,#ccc 25%,transparent 25%),linear-gradient(45deg,transparent 75%,#ccc 75%),linear-gradient(-45deg,transparent 75%,#ccc 75%);background-size:8px 8px;background-position:0 0,0 4px,4px -4px,-4px 0;">
            <span class="absolute inset-0" :style="`background-color:${css}`"></span>
        </span>
        <span class="truncate" :class="empty ? 'text-base-content/40 italic' : ''"
            x-text="empty ? @js(__('No color')) : build()"></span>
    </button>

    {{-- Panel --}}
    <div
        x-show="open"
        x-cloak
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        role="dialog"
        aria-label="{{ __('Choose color') }}"
        class="absolute start-0 top-full z-50 mt-2 w-64 rounded-lg border border-base-300 bg-base-100 p-4 shadow-md"
        style="display: none;"
    >
        <div class="flex flex-col gap-4">
            {{-- Large preview swatch (checkerboard shows transparency) --}}
            <div class="relative h-16 w-full overflow-hidden rounded-md border border-base-300"
                style="background-image: linear-gradient(45deg,#ccc 25%,transparent 25%),linear-gradient(-45deg,#ccc 25%,transparent 25%),linear-gradient(45deg,transparent 75%,#ccc 75%),linear-gradient(-45deg,transparent 75%,#ccc 75%);background-size:16px 16px;background-position:0 0,0 8px,8px -8px,-8px 0;">
                <div class="absolute inset-0" :style="`background-color:${css}`" aria-hidden="true"></div>
                <span x-show="empty" class="absolute inset-0 grid place-content-center text-xs italic text-base-content/40">{{ __('No color') }}</span>
            </div>

            {{-- Hue slider --}}
            <div class="flex flex-col gap-1.5">
                <input type="range" min="0" max="360" step="1" :value="hue"
                    @input="setHue($event.target.value)" aria-label="{{ __('Hue') }}"
                    class="range range-sm w-full rounded-full [--range-fill:0] [--range-bg:transparent] [--range-thumb:#fff] [--range-thumb-size:1rem]"
                    style="background: linear-gradient(to right, #ff0000 0%, #ffff00 17%, #00ff00 33%, #00ffff 50%, #0000ff 67%, #ff00ff 83%, #ff0000 100%);" />
            </div>

            {{-- Alpha slider --}}
            <div class="flex flex-col gap-1.5">
                <div class="flex items-center justify-between text-xs text-base-content/60">
                    <span>{{ __('Opacity') }}</span>
                    <span x-text="alphaPct + '%'" class="font-mono"></span>
                </div>
                <input type="range" min="0" max="100" step="1" :value="alphaPct"
                    @input="setAlpha($event.target.value)" aria-label="{{ __('Opacity') }}"
                    class="range range-sm w-full rounded-full [--range-fill:0] [--range-bg:transparent] [--range-thumb:#fff] [--range-thumb-size:1rem]"
                    :style="`background-image:
                        linear-gradient(to right, transparent, ${hex}),
                        linear-gradient(45deg,#ccc 25%,transparent 25%),
                        linear-gradient(-45deg,#ccc 25%,transparent 25%),
                        linear-gradient(45deg,transparent 75%,#ccc 75%),
                        linear-gradient(-45deg,transparent 75%,#ccc 75%);
                        background-size: 100% 100%, 8px 8px, 8px 8px, 8px 8px, 8px 8px;
                        background-position: 0 0, 0 0, 0 4px, 4px -4px, -4px 0;`" />
            </div>

            {{-- Hex text input --}}
            <input type="text" inputmode="text" autocomplete="off" spellcheck="false" maxlength="9"
                x-model="input" @input="commit($event.target.value)" @blur="sync()"
                :aria-invalid="!isValid" placeholder="{{ $placeholder }}"
                class="input input-sm input-bordered w-full font-mono"
                :class="!isValid ? 'input-error' : ''" />

            {{-- Preset swatch grid --}}
            <div class="grid grid-cols-5 gap-2" role="group" aria-label="{{ __('Presets') }}">
                <template x-for="c in swatches" :key="c">
                    <button type="button" @click="pick(c)" :aria-label="c"
                        class="aspect-square w-full rounded-md border border-base-300 outline-none transition hover:scale-110"
                        :class="!empty && hex === (normalize(c) || {}).hex ? 'ring-2 ring-base-content ring-offset-1' : ''"
                        :style="`background-color: ${c}`"></button>
                </template>
            </div>

            {{-- Clear --}}
            <template x-if="allowEmpty">
                <button type="button" @click="clear()" x-show="!empty"
                    class="btn btn-ghost btn-xs w-full gap-1 text-base-content/60 hover:bg-error/10 hover:text-error">
                    <x-tabler-x class="size-3.5" />
                    {{ __('Remove color') }}
                </button>
            </template>
        </div>
    </div>
</div>
