@props([
    'item' => '',
])

@php
    ['gridCols' => $gridCols, 'colSpan' => $colSpan] = block_grid_classes($item);

    $galleryField = $item->datafield->firstWhere('type', 'gallery');

    if (is_array($galleryField?->data)) {
        // New model: single row, data = [id, id, ...]
        $galleryImages = $galleryField->data;
    } else {
        // Legacy model: multiple rows, each with a single integer in data
        $galleryImages = $item->datafield
            ->where('type', 'gallery')
            ->filter(fn ($d) => !empty($d->data) && !is_array($d->data))
            ->pluck('data')
            ->toArray();
    }

    $isSlider = get_meta($item, 'slider') === 'true';
    // Lightbox and slider are mutually exclusive; slider wins.
    $isLightbox = ! $isSlider && get_meta($item, 'lightbox') === 'true';
    $cols = (int) ($item->grid ?: 3);

    // Full-size URLs of the image files (for the lightbox), in gallery order.
    $lightboxImages = [];
    if ($isLightbox) {
        foreach ($galleryImages as $imgId) {
            $f = \Illuminate\Support\Facades\Cache::rememberForever('kompass_file_' . $imgId, fn () => \Secondnetwork\Kompass\Models\File::find($imgId));
            if ($f && in_array(strtolower($f->extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg'])) {
                $p = $f->path ? $f->path . '/' : '';
                $lightboxImages[] = asset('storage/' . $p . $f->slug . '.' . $f->extension);
            }
        }
    }
@endphp

<div
    @if ($isLightbox)
        x-data="{
            lbOpen: false,
            lbImages: @js($lightboxImages),
            lbIndex: 0,
            lbOpenAt(url) { const i = this.lbImages.indexOf(url); this.lbIndex = i < 0 ? 0 : i; this.lbOpen = true; },
            lbNext() { this.lbIndex = (this.lbIndex + 1) % this.lbImages.length; },
            lbPrev() { this.lbIndex = (this.lbIndex - 1 + this.lbImages.length) % this.lbImages.length; }
        }"
        @open-lightbox="lbOpenAt($event.detail)"
        @keydown.escape.window="lbOpen = false"
        @keydown.arrow-right.window="lbOpen && lbNext()"
        @keydown.arrow-left.window="lbOpen && lbPrev()"
    @endif
    {{ $attributes->merge(['class' => 'relative group ' . $gridCols . ' ' . $colSpan]) }}>
    @if ($isSlider)
        {{-- Slider: autoplaying scroll-snap track with prev/next + dots, looping --}}
        <div
            x-data="{
                active: 0,
                count: {{ count($galleryImages) }},
                pages: [0],
                timer: null,
                delay: 5000,
                init() {
                    this.recalc();
                    this.$refs.track.addEventListener('scroll', () => this.updateActive(), { passive: true });
                    window.addEventListener('resize', () => { this.recalc(); });
                    this.start();
                },
                step() {
                    const first = this.$refs.track.firstElementChild;
                    if (! first) return 1;
                    const gap = parseFloat(getComputedStyle(this.$refs.track).columnGap) || 0;
                    return first.offsetWidth + gap;
                },
                recalc() {
                    const perView = Math.max(1, Math.round(this.$refs.track.clientWidth / this.step()));
                    const pageCount = Math.max(1, Math.ceil(this.count / perView));
                    this.pages = Array.from({ length: pageCount }, (_, i) => i);
                    this.updateActive();
                },
                updateActive() {
                    const w = this.$refs.track.clientWidth || 1;
                    this.active = Math.round(this.$refs.track.scrollLeft / w);
                },
                go(page) {
                    this.$refs.track.scrollTo({ left: page * this.$refs.track.clientWidth, behavior: 'smooth' });
                },
                next() {
                    const t = this.$refs.track;
                    if (t.scrollLeft >= t.scrollWidth - t.clientWidth - 4) { t.scrollTo({ left: 0, behavior: 'smooth' }); }
                    else { this.go(this.active + 1); }
                },
                prev() {
                    const t = this.$refs.track;
                    if (t.scrollLeft <= 4) { t.scrollTo({ left: t.scrollWidth, behavior: 'smooth' }); }
                    else { this.go(this.active - 1); }
                },
                start() { this.stop(); this.timer = setInterval(() => this.next(), this.delay); },
                stop() { if (this.timer) clearInterval(this.timer); }
            }"
            @mouseenter="stop()" @mouseleave="start()"
            class="relative {{ get_meta($item, 'css-classname') }}"
            style="--cols: {{ $cols }}"
        >
            <div x-ref="track"
                class="flex gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @foreach ($galleryImages as $imageId)
                    <div class="snap-start shrink-0 w-[85%] sm:w-[calc((100%-1rem)/2)] lg:w-[calc((100%-(var(--cols)-1)*1rem)/var(--cols))]">
                        <x-media-item :id="$imageId" :lightbox="$isLightbox" wire:key="gallery-{{ $item->id }}-{{ $loop->index }}" class="w-full h-full rounded-lg" />
                    </div>
                @endforeach
            </div>

            <button type="button" @click="prev()" aria-label="{{ __('Previous') }}"
                class="absolute start-2 top-1/2 -translate-y-1/2 grid place-content-center size-10 rounded-full bg-base-100/90 border border-base-300 shadow opacity-0 group-hover:opacity-100 transition hover:bg-base-100">
                <x-tabler-chevron-left class="size-5" />
            </button>
            <button type="button" @click="next()" aria-label="{{ __('Next') }}"
                class="absolute end-2 top-1/2 -translate-y-1/2 grid place-content-center size-10 rounded-full bg-base-100/90 border border-base-300 shadow opacity-0 group-hover:opacity-100 transition hover:bg-base-100">
                <x-tabler-chevron-right class="size-5" />
            </button>

            {{-- Dots: one per view/page --}}
            <div class="flex justify-center gap-2 mt-4" x-show="pages.length > 1">
                <template x-for="(p, i) in pages" :key="i">
                    <button type="button" @click="go(i)" :aria-label="`{{ __('Go to slide') }} ${i + 1}`"
                        class="h-2 rounded-full transition-all"
                        :class="active === i ? 'w-5 bg-primary' : 'w-2 bg-base-300 hover:bg-base-content/40'"></button>
                </template>
            </div>
        </div>
    @else
        {{-- Grid --}}
        <div class="md:grid gap-4 transition-all ease-in-out duration-500 grid-cols-{{ $item->grid }} one-image {{ get_meta($item, 'css-classname') }}">
            @foreach ($galleryImages as $imageId)
                <x-media-item :id="$imageId" :lightbox="$isLightbox" wire:key="gallery-{{ $item->id }}-{{ $loop->index }}" class="w-full h-full rounded-lg" />
            @endforeach
        </div>
    @endif

    @if ($isLightbox)
        {{-- Lightbox overlay --}}
        <template x-teleport="body">
            <div x-show="lbOpen" x-cloak @click="lbOpen = false"
                x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 p-4" role="dialog" aria-modal="true">

                <button type="button" @click="lbOpen = false" aria-label="{{ __('Close') }}"
                    class="absolute top-4 end-4 grid place-content-center size-11 rounded-full text-white/80 hover:text-white hover:bg-white/10 transition">
                    <x-tabler-x class="size-7" />
                </button>

                <button type="button" @click.stop="lbPrev()" x-show="lbImages.length > 1" aria-label="{{ __('Previous') }}"
                    class="absolute start-2 sm:start-6 grid place-content-center size-11 rounded-full text-white/80 hover:text-white hover:bg-white/10 transition">
                    <x-tabler-chevron-left class="size-8" />
                </button>

                <img :src="lbImages[lbIndex]" @click.stop alt=""
                    class="max-h-[90vh] max-w-[90vw] object-contain rounded shadow-2xl" />

                <button type="button" @click.stop="lbNext()" x-show="lbImages.length > 1" aria-label="{{ __('Next') }}"
                    class="absolute end-2 sm:end-6 grid place-content-center size-11 rounded-full text-white/80 hover:text-white hover:bg-white/10 transition">
                    <x-tabler-chevron-right class="size-8" />
                </button>
            </div>
        </template>
    @endif
</div>
