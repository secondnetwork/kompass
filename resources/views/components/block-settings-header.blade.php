@props(['itemblocks'])

@php
    $type = $itemblocks->type ?? '';
    $meta = match ($type) {
        'group' => ['label' => __('Layout Block'), 'icon' => 'tabler-template'],
        'accordiongroup' => ['label' => __('Accordion'), 'icon' => 'tabler-layout-list'],
        'wysiwyg' => ['label' => __('Text'), 'icon' => 'tabler-align-left'],
        'gallery' => ['label' => __('Gallery'), 'icon' => 'tabler-photo'],
        'video' => ['label' => __('Video'), 'icon' => 'tabler-video'],
        'button' => ['label' => __('Button'), 'icon' => 'tabler-box-model-2'],
        default => ['label' => __('Block'), 'icon' => null],
    };

    $iconName = $meta['icon'];
    if (! $iconName) {
        $iconName = $itemblocks->iconclass
            ? (str_starts_with($itemblocks->iconclass, 'tabler-') ? $itemblocks->iconclass : 'tabler-'.$itemblocks->iconclass)
            : 'tabler-section';
    }
@endphp

<div class="flex items-start gap-3 pb-4">
    <span class="shrink-0 flex items-center justify-center size-9 rounded-md border border-base-300 bg-base-200 text-base-content">
        @svg($iconName, 'size-5')
    </span>
    <div class="min-w-0 flex-1" x-data="{
            editing: false,
            name: @js($itemblocks->name ?: ''),
            save() {
                this.editing = false;
                this.$wire.savename({{ $itemblocks->id }}, this.name);
            }
        }">
        <button type="button" x-show="!editing"
            @click="editing = true; $nextTick(() => { $refs.nameInput.focus(); $refs.nameInput.select(); })"
            class="group/name flex items-center gap-1 font-semibold text-sm leading-tight max-w-full text-left">
            <span class="truncate" x-text="name || @js($meta['label'])"></span>
            <x-tabler-pencil class="size-3.5 opacity-40 group-hover/name:opacity-70 shrink-0" />
        </button>
        <input x-show="editing" x-cloak x-ref="nameInput" type="text" x-model="name"
            @keydown.enter.prevent="save()"
            @keydown.escape="editing = false; name = @js($itemblocks->name ?: '')"
            @blur="if (editing) save()"
            class="font-semibold text-sm w-full border-b border-primary bg-transparent focus:outline-none px-0 py-0.5" />
        <p class="text-xs text-base-content/60 mt-0.5">{{ $meta['label'] }}</p>
    </div>
</div>
