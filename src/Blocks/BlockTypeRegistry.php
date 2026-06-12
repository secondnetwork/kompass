<?php

namespace Secondnetwork\Kompass\Blocks;

use Secondnetwork\Kompass\Models\Blockfields;
use Secondnetwork\Kompass\Models\Blocktemplates;

/**
 * Single source of truth for block types. Merges the built-in types defined in
 * config('kompass.block_types') with the user-defined Blocktemplates rows.
 *
 * Feeds: the add-block palette, the default datafields created on add, the
 * builder styling (rail/badge/bar/accent), the edit-control list, and the
 * frontend component name. Built-ins always win over DB templates of the same
 * type.
 */
class BlockTypeRegistry
{
    /** @var array<int,array<string,mixed>>|null Request-scoped palette memo. */
    protected ?array $paletteCache = null;

    /**
     * The built-in block types from config.
     *
     * @return array<string,array<string,mixed>>
     */
    public function builtins(): array
    {
        return config('kompass.block_types', []);
    }

    /**
     * Resolve a block type (built-in or DB template) to its definition.
     *
     * @return array<string,mixed>|null
     */
    public function get(string $key): ?array
    {
        $builtins = $this->builtins();
        if (isset($builtins[$key])) {
            return $builtins[$key] + ['key' => $key, 'builtin' => true];
        }

        $template = Blocktemplates::where('type', $key)->first();
        if ($template) {
            return [
                'key' => $key,
                'label' => $template->name,
                'icon' => $template->iconclass,
                'component' => 'blocks.'.$key,
                'container' => false,
                'builtin' => false,
                'blocktemplate_id' => $template->id,
            ];
        }

        return null;
    }

    public function exists(string $key): bool
    {
        return $this->get($key) !== null;
    }

    public function isBuiltin(string $key): bool
    {
        return isset($this->builtins()[$key]);
    }

    public function isContainer(string $key): bool
    {
        return (bool) ($this->builtins()[$key]['container'] ?? false);
    }

    public function component(string $key): string
    {
        return $this->builtins()[$key]['component'] ?? ('blocks.'.$key);
    }

    /**
     * Builder styling for a block type.
     *
     * @return array{rail:string,badge:string,bar:string,accent:string}
     */
    public function styling(string $key): array
    {
        $default = ['rail' => 'border-l-slate-400', 'badge' => 'bg-slate-500', 'bar' => 'bg-base-200', 'accent' => 'text-slate-500'];

        return ($this->builtins()[$key]['styling'] ?? []) + $default;
    }

    /**
     * Ordered edit-control keys rendered in the block settings offcanvas.
     *
     * @return array<int,string>
     */
    public function controls(string $key): array
    {
        return $this->builtins()[$key]['controls'] ?? ['layout', 'color', 'advanced'];
    }

    /**
     * Default datafield definitions to create when a block of this type is added.
     * Built-in types use their configured default_fields; DB templates use their
     * associated Blockfields rows.
     *
     * @return array<int,array<string,mixed>>
     */
    public function defaultFields(string $type, int|string|null $blocktemplateId = null): array
    {
        $builtins = $this->builtins();
        if (! empty($builtins[$type]['default_fields'])) {
            return $builtins[$type]['default_fields'];
        }

        if ($blocktemplateId !== null && $blocktemplateId !== '') {
            return Blockfields::where('blocktemplate_id', $blocktemplateId)
                ->orderBy('order')
                ->get()
                ->map(fn (Blockfields $field) => [
                    'name' => $field->name,
                    'type' => $field->type,
                    'order' => $field->order,
                    'grid' => $field->grid,
                    'data' => $field->type === 'gallery' ? [] : null,
                ])
                ->all();
        }

        return [];
    }

    /**
     * Tiles for the add-block palette: built-ins (config order, palette=true)
     * followed by DB templates (by order). A DB template whose type collides
     * with a built-in is skipped so the built-in is never shadowed.
     *
     * @return array<int,array<string,mixed>>
     */
    public function palette(): array
    {
        if ($this->paletteCache !== null) {
            return $this->paletteCache;
        }

        $tiles = [];

        foreach ($this->builtins() as $key => $definition) {
            if (! ($definition['palette'] ?? true)) {
                continue;
            }
            $tiles[] = [
                'id' => '',
                'name' => __($definition['label'] ?? $key),
                'type' => $key,
                'icon' => $definition['icon'] ?? '',
                'image' => isset($definition['palette_image']) ? kompass_asset($definition['palette_image']) : null,
                'image_class' => $definition['palette_image_class'] ?? '',
                'icon_svg' => null,
                'border' => $definition['palette_border'] ?? 'border-gray-400',
            ];
        }

        $builtinKeys = array_keys($this->builtins());

        foreach (Blocktemplates::orderBy('order')->get() as $template) {
            if (in_array($template->type, $builtinKeys, true)) {
                continue;
            }
            $tiles[] = [
                'id' => $template->id,
                'name' => $template->name,
                'type' => $template->type,
                'icon' => $template->iconclass ?? '',
                'image' => $template->icon_img_path
                    ? asset('storage/'.$template->icon_img_path)
                    : ($template->iconclass ? null : kompass_asset('icons-blocks/contact.png')),
                'image_class' => $template->icon_img_path ? 'w-full border-base-300 border-solid border-2 rounded object-cover' : '',
                'icon_svg' => (! $template->icon_img_path && $template->iconclass) ? $template->iconclass : null,
                'border' => 'border-gray-400',
            ];
        }

        return $this->paletteCache = $tiles;
    }
}
