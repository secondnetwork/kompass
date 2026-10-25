@php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp
# Kompass CMS (secondnetwork/kompass)

Kompass is a block-based CMS for Laravel 13 + Livewire 4 + Tailwind 4. A page is a
tree of **blocks** (ordered, morphed onto a page), each block holding **datafields**
(the editable content) and **meta** (styling/layout attributes such as
`css-classname`, `id-anchor`, `layout`).

## Where code lives

- **Package** `vendor/secondnetwork/kompass` → `src/` (registries, Livewire admin, models, migrations, routes), `config/kompass.php`, `resources/views` under the `kompass::` namespace (builder UI, `block-controls/`, field widgets `block/`), `stubs/` (what `kompass:install` publishes), `resources/boost/` (this guideline + the `kompass-development` skill).
- **Host app** owns everything a visitor sees: `resources/views/components/blocks/{type}.blade.php`, `resources/views/pages/…`, `resources/views/layouts/…`, `resources/css/*`, seeders, and its own full copy of `config/kompass.php`.

## Conventions

- Registries are the single source of truth: `block_registry()` (config `kompass.block_types`) and `field_registry()` (config `kompass.field_types`); built-ins always win over DB templates on a type collision.
- A type key maps 1:1 to a view: `block_types['x']['component'] = 'blocks.x'` → `resources/views/components/blocks/x.blade.php` (host), `field_types['x']['display_component'] = 'block.x'` → `resources/views/components/block/x.blade.php` (package), `controls: ['x']` → `kompass::components.block-controls.x`.
- Block views start with `@props(['item' => ''])` — **never a hyphenated prop name** (Blaze compiles it to `$is-first ??= …` → ParseError). Content: `$item->datafield`, children: `$item->children->sortBy('order')` via `<x-blocks.components>`.
- Section colours/backgrounds belong in block **meta** (`css-classname`, `id-anchor`, `layout`), applied by the page wrapper — never hard-code them in block views.
- Host `config/kompass.php` merges only at the top level: a host `block_types` array fully **replaces** the package's, so copy package entries before extending.

## Create / use main features

@verbatim
```bash
# content pipeline
php artisan db:seed --class=SecondnetworkLandingSeeder --force   # idempotent pages/blocks/media/menus
php artisan tinker --execute="cache()->flush();"                  # after manual DB edits

# host app hygiene (see AGENTS.md)
vendor/bin/pint --dirty --format agent    # after ANY PHP change
php artisan test --compact
bun run build                             # after touching resources/css|js
```
@endverbatim

| Task | Do this |
|---|---|
| Add a block type | entry in `block_types` + host frontend view + (optional) seeder — palette, builder card, Edit panel and default datafields follow automatically |
| Add a field type | entry in `field_types` + package widget `components/block/{key}.blade.php`; scalar saves via `updateDatafield($id, $value)` on blur |
| Add a builder control | package view `components/block-controls/{key}.blade.php` + add the key to the type's `controls` |
| Edit section styling | builder → block Settings → Advanced → `css-classname` / `id-anchor` / `layout` (meta) |

**Full architecture map, recipes and gotchas: load the `kompass-development` skill.**
Detailed command/style/security rules live in this project's Boost guidelines.
