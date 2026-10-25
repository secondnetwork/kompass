---
name: kompass-development
description: "Build and extend Kompass, the block-based Laravel 13 + Livewire 4 + Tailwind 4 CMS (composer package secondnetwork/kompass). Use when adding or changing block types, datafield types, block controls, the page builder UI, frontend block components, pages/menus/media/seeders, config/kompass.php, the Block/Field type registries, or the page rendering pipeline — in the package itself or in any host app that requires it."
---

# Kompass Development

## When to use this skill

Use this skill whenever you touch Kompass content architecture: a new or renamed
block type, a new field type, builder/`kompass::` views, frontend block components,
section styling via meta, menus/media/seeding, or anything reading
`config('kompass.block_types')` / `config('kompass.field_types')`.
Do **not** load it for generic Laravel/Livewire/Tailwind work — use the Boost
guidelines and their skills for that (`laravel-best-practices`,
`livewire-development`, `tailwindcss-development`).

Kompass is a block-based CMS: pages are made of ordered **blocks**, each block has
**datafields** (the editable content) and **meta** (styling/layout attributes).
Everything an editor sees in the builder is driven by two config registries.

Read `AGENTS.md` for commands/style, `README.md` for install, `GEMINI.md` for the
Livewire cookbook, and `docs/` (relationship block, query sources) for deep dives.
This skill is the architectural map and the "how do I add X" cookbook.

## 1. Mental model

```
Page (morph owner, soft deletes)
 └── Block  (type = registry key, order, status, subgroup = parent block id)
      ├── Datafield[]   (type, name, order, data = text | array | JSON)
      ├── Meta[]        (key/value, morph: css-classname, id-anchor, layout, color …)
      └── Block[]       (children when subgroup = parent block id)
```

* A block's **type** is a key in `config('kompass.block_types')`.
* A datafield's **type** is a key in `config('kompass.field_types')`.
* Containers (`container => true`) may hold children *and* their own datafields.

## 2. Where the code lives

| Repo | Path | Contains |
|---|---|---|
| Package (git: `github.com/secondnetwork/kompass`) | `webseiten/packages/kompass` | `src/` (registries, Livewire admin, models, migrations, routes), `config/kompass.php`, `resources/views` (`kompass::` namespace: admin UI, block-controls, field widgets), `resources/boost/` (this skill + guidelines), `stubs/` |
| Host app | `webseiten/kompass-strater-kit` (branch `sn`) | `config/kompass.php` (full copy), **frontend block views** `resources/views/components/blocks/*`, page/layout views, seeders, theme CSS/JS |

The package is linked into the host via composer symlink
`vendor/secondnetwork/kompass → packages/kompass/`.

**Split of responsibility**

* Package: everything under the `kompass::` namespace (builder, admin, field widgets).
* Host: everything a visitor sees — `resources/views/components/blocks/{type}.blade.php`,
  `resources/views/pages/{…}`, `resources/views/layouts/main.blade.php`, `resources/css/*`.
  (These come from `stubs/` on `php artisan kompass:install`, then grow in the host.)

## 3. Single source of truth: the two registries

```php
block_registry(): Secondnetwork\Kompass\Blocks\BlockTypeRegistry   // config('kompass.block_types')
field_registry(): Secondnetwork\Kompass\Blocks\FieldTypeRegistry   // config('kompass.field_types')
```

Both are singletons (`src/KompassServiceProvider.php`) with global helpers in
`src/Helpers/helpers.php`. `BlockTypeRegistry` merges config built-ins with DB rows
(`blocktemplates` + `blockfields`); **built-ins always win on a type collision**.

Registry API (all you need):

```php
block_registry()->exists($type);            // is this type known?
block_registry()->isContainer($type);       // renders blocksgroup vs plain block
block_registry()->component($type);         // 'blocks.{type}' (frontend component name)
block_registry()->styling($type);           // ['rail','badge','bar','accent'] builder colours
block_registry()->controls($type);          // ['layout','color','advanced',…]
block_registry()->defaultFields($type, $blocktemplateId); // datafields created on add
block_registry()->palette();                // tiles of the add-block panel

field_registry()->fieldComponent($type);    // 'block.text' → kompass::block.text
field_registry()->fieldEditWidget($type);   // input | image | oembed | editor
field_registry()->fieldSelectOptions();     // options for the field-type <select>
```

`controls` keys map **1:1** to files: `resources/views/components/block-controls/{key}.blade.php`.
`display_component` maps to `resources/views/components/block/{key}.blade.php`.

## 4. Data model (tables)

| Table | Model | Notable columns / relations |
|---|---|---|
| `pages` | `Page` (soft deletes) | `title, slug, status, layout, land, meta_description, order` — `blocks()` morphMany, `metas()` morphMany |
| `blocks` | `Block` | `blockable_id/type` (morph), `subgroup` (parent block id), `type, name, status, iconclass, layoutgrid, grid, order` — `datafield()` hasMany, `children()` hasMany(Block, `subgroup`), `metas()` |
| `datafields` | `Datafield` | `block_id` (FK, cascade), `type, name, order, grid, data` (longText) |
| `meta` | `Meta` | morph `metable`, `key/value`; access via `HasMeta` |
| `menus` / `menu_items` | `Menu` / `Menuitem` | menu items have `url`, `target`, `order`, `page_id` |
| `medialibrary` | `File` | uploaded assets; `path`, `slug`, `extension`, `alt` |
| `blocktemplates` / `blockfields` | `Blocktemplates` / `Blockfields` | user-defined block types (DB, merged into the registry) |
| `settings` | `Setting` | global key/value, read with `setting('global.x')` |
| `query_sources` | `QuerySource` | data sources for the relationship block |

Meta API (`src/Traits/HasMeta.php`): `getMeta($key, $default)`, `setMeta($key, $value)`,
`saveMeta(array)`, `deleteMeta($key)`.

**Cache:** `Page`, `Block`, `Meta` flush the whole cache in `boot()`; page blocks are
read with `cache()->rememberForever("blocks-page-{pageId}")` (published top-level
blocks only, eager-loaded `children`, `datafield`, `meta`). After changing rows by
hand (e.g. tinker), run `cache()->flush()` or the seeder does it for you.

## 5. Request → HTML pipeline

```
routes/web.php (HOST)           Route::livewire('/', 'pages::page')  +  /{slug}
  └─ resources/views/pages/page/{page.php,page.blade.php}   #[Layout('layouts::Main')]
       └─ resources/views/layouts/main.blade.php            (fonts, header, footer)
            └─ page.blade.php loop over blocks:
                 <section class="{{ getMeta('css-classname') }}" id="{{ getMeta('id-anchor') }}">
                   <div class="{{ getMeta('layout') ?? 'fullpage' }}">
                     <x-dynamic-component :component="'blocks.'.$item->type" :item="$item" />
```

* `pages.layout` is **not** a view name — it is the *Page Template* enum
  (`page` | `is_front_page`). `is_front_page` is used to find the front page
  (`resources/views/pages/page/page.php`) and to build titles/URLs.
* Block layout comes from block **meta** (`fullpage` | `popout`), never from the page.
* `page.blade.php` skips a block silently if `view()->exists('components.blocks.'.$type)`
  is false and only prints a dashed red hint when debug mode is on.

> Caveat: the package provider imports `Livewire\Frontend\Pageview` and aliases
> `pages::page` to it, but that class was deleted on `main`. The host resolves
> `pages::page` to its own view component. If you ever see "class not found" for
> `pages::page`, this stale import is the reason — fix it in `src/KompassServiceProvider.php`.

## 6. Recipes

### 6.1 Add a block type

1. **Config** — add an entry to `block_types` in the **host** `config/kompass.php`
   (and in the package config if it should ship with Kompass):

```php
'hero' => [
    'label'           => 'Hero',
    'icon'            => 'tabler-access-point',
    'component'       => 'blocks.hero',          // → components/blocks/hero.blade.php
    'container'       => true,                   // may hold children
    'default_fields'  => [                       // created when the block is added
        ['name' => 'Badge', 'type' => 'text', 'order' => 1],
        ['name' => 'Headline', 'type' => 'wysiwyg', 'order' => 2],
        ['name' => 'Button', 'type' => 'link', 'order' => 3],
    ],
    'styling'  => ['rail' => 'border-l-red-500', 'badge' => 'bg-red-500',
                   'bar' => 'bg-red-500/10', 'accent' => 'text-red-600'],
    'controls' => ['layout', 'color', 'advanced'],
    'palette'  => true,                          // false = not offered in add-block
    'palette_image'  => 'icons-blocks/default.png',
    'palette_border' => 'border-red-500',
],
```

2. **Frontend view** — `resources/views/components/blocks/hero.blade.php` (host):

```blade
@props(['item' => ''])

@php
    $fields = $item->datafield->keyBy(fn ($f) => \Illuminate\Support\Str::lower($f->name ?? ''));
    $field  = fn (string $n) => $fields->get($n)?->data;
@endphp

<section {{ $attributes->except('is-first')->merge(['class' => 'not-prose section-padding']) }}>
    <h1>{{ $field('Headline') }}</h1>

    @foreach ($item->children->sortBy('order') as $child)
        <x-blocks.components :item="$child" />
    @endforeach
</section>
```

3. Done — the palette, the builder card, the Edit panel and the default datafields
   all come from the config. Re-run the seeder if content must exist.

**Contract for block views**

* `@props(['item' => ''])` — **never a hyphenated prop name** (see pitfalls).
* Read content with `$item->datafield` (ordered by `order`).
* Children: `$item->children->sortBy('order')`, rendered with `<x-blocks.components :item="$child" />`.
* Rich text: `wysiwyg_blocks(null, $fields->get('intro'))` + `<x-blocks.partials.rich :blocks="…" />`.
* Section colours/backgrounds come from the page wrapper via meta (see 6.4) — keep the
  root element colour-agnostic and add `not-prose` (the layout wraps content in
  `<main class="prose">`).

### 6.2 Add a datafield type

```php
// config/kompass.php → field_types
'code' => ['label' => 'Code', 'icon' => 'tabler-code',
           'display_component' => 'block.code', 'edit_widget' => 'input'],
```
plus the widget view `resources/views/components/block/code.blade.php` (package):

```blade
<label for="">{{ $itemfield->name }}</label>
<textarea rows="14" spellcheck="false"
    wire:blur="updateDatafield({{ $itemfield->id }}, $event.target.value)"
    class="textarea textarea-bordered w-full font-mono text-xs"
>{{ $itemfield->data }}</textarea>
```

* Scalar fields save with `updateDatafield($id, $value)` on blur.
* Array fields save with `updateDatafieldArray($id, $key, $value)`.
* `edit_widget: editor` renders `livewire:kompass-editor` (Notion-style rich editor).

### 6.3 Add a block control (settings panel)

Controls are the gear/adjustments panel of a block — not its content.
Create `resources/views/components/block-controls/{key}.blade.php` (package) and add
the key to the type's `controls` array. Existing keys: `layout`, `alignment`, `link`,
`color`, `advanced`, `container-layout`, `layout-grid`, `gallery`, `visibility`.

### 6.4 Section styling & anchors (meta, not markup)

`resources/views/pages/page/page.blade.php` applies, per block:

* `css-classname` → the wrapping `<section class="…">` (background, text colour, border)
* `id-anchor` → `id="…"` (menu anchors, deep links)
* `layout` → the inner column (`fullpage` | `popout` | `content`)

So section colours are **editable in the builder** (Settings → Advanced → Classname)
and Blade views stay colour-agnostic. Seeder example:

```php
$this->block($page, [
    'name' => 'Hero', 'type' => 'hero', 'icon' => 'tabler-access-point', 'order' => 1,
    'meta' => ['css-classname' => 'bg-[var(--color-brand-red)] text-[var(--color-text-dark)]'],
], [
    ['name' => 'Headline', 'type' => 'wysiwyg', 'data' => $this->rich([...])],
]);
```

### 6.5 Seed content

`database/seeders/SecondnetworkLandingSeeder.php` (host) is the reference: it is
idempotent — it deletes the blocks of the pages it owns, rebuilds them, copies media
into `config('kompass.storage.disk')`, upserts `File` rows and `Menuitem`s, then
`cache()->flush()`.

```bash
php artisan db:seed --class=SecondnetworkLandingSeeder --force
```

Verify with:

```bash
php artisan tinker --execute="foreach (Page::find(1)->blocks()->whereNull('subgroup')->orderBy('order')->get() as \$b) echo \$b->order.' '.\$b->type.PHP_EOL;"
curl -s http://your-app.test/ | grep -c 'section'
```

### 6.6 Builder (admin) map

* Page list `/admin/pages` → `Livewire\PagesTable` (`clonePage`, `handleSort`, `addPage`)
* Builder `/admin/pages/edit/{id}` → `Livewire\PagesData` → `kompass::livewire.pages.pages-show`
* Block template editor `/admin/blocks/{action}/{id}` → `Livewire\BlocksData`
* Menus `/admin/menus`, Media `/admin/medialibrary`, Roles/Settings under `/admin`
* Roles come from Spatie (`admin | manager | editor`) — routes are gated in `routes/web.php`

Key `PagesData` actions (useful when extending the builder):

| Action | Purpose |
|---|---|
| `addBlock($blocktemplatesId, $name, $type, $icon)` | creates block + `defaultFields()` datafields |
| `edit($id)` | opens the **Edit block** offcanvas with the block's datafields |
| `updateDatafield($id, $value)` / `updateDatafieldArray($id, $key, $value)` | save field content |
| `update($pageId)` | saves page attrs and dispatches `saveTheDatafield` |
| `selectitem($action, $itemId, …)` | `addBlock` \| `addMedia` \| `deleteblock` \| `copyToPage` |
| `handleSort($item, $position, $groupId)` | drag & drop incl. moving between containers |
| `updatestatus($id,$status)` / `savename` / `updateLayoutGrid` / `saveset` | block status, name, grid, meta |

## 7. Gotchas & pitfalls

1. **No hyphens in `@props` names.** The host runs Blaze
   (`Blaze::optimize()->in(resource_path('views/components'))`); `@props(['is-first' => …])`
   compiles to `$is-first ??= …` → **ParseError**. Use `$attributes->except('is-first')`
   instead when you need to drop the attribute.
2. **Config merge is shallow.** `mergeConfigFrom()` merges only top-level keys: a host
   `block_types` array **replaces** the package's whole array (that is why the host
   config repeats the package entries verbatim). Missing key + cached config = package
   defaults unavailable → run `php artisan config:clear` after config edits.
3. **Container blocks need their own Edit action.** Content fields of a container
   (hero headline, profile intro) are only reachable through the Edit button in
   `kompass::components.blocksgroup` — gated by `$hasOwnFields = $itemblocks->datafield->isNotEmpty()`.
   If you add a container with `default_fields`, make sure that branch stays intact.
4. **`pages.layout` ≠ view name.** Only `page` and `is_front_page` exist; the view is
   always `resources/views/pages/page/page.blade.php`.
5. **Frontend block views live in the host**, not in the package. A type without
   `resources/views/components/blocks/{type}.blade.php` renders nothing (dev hint only).
6. **Caching:** after editing views, `php artisan view:clear` if something looks stale;
   after DB writes, `cache()->flush()`.
7. **Media** lives on `config('kompass.storage.disk')` (public disk) under `media/…`,
   URL = `asset('storage/'.path)`; requires `php artisan storage:link`.
8. **Rebuild assets** (`npm run build` / `bun run build`) after touching
   `resources/css` or `resources/js` — the layout reads the Vite manifest.

## 8. Commands

```bash
# package
composer install && php artisan migrate
vendor/bin/rector process           # static analysis
php artisan optimize:clear
bun install && npm run build        # package assets → public/assets/build

# host app (kompass-strater-kit)
vendor/bin/pint --dirty --format agent   # after ANY PHP change (required by AGENTS.md)
php artisan test --compact
bun run dev | bun run build
php artisan db:seed --class=SecondnetworkLandingSeeder --force

# boost (host app) — this skill is discovered from resources/boost/skills
php artisan boost:list-skills
php artisan boost:update
```

## 9. Reference implementation: the secondnetwork port

The host app contains a complete, working port of a real landing page
(`webseiten/sn_op/index.html`) — the fastest way to see every concept end to end:

* Config: `kompass-strater-kit/config/kompass.php` → 10 `sn*` block types (hero,
  snprofile, sntimeline, snfeature, sncode, sncta, snstat, snstack, snentry, sncard)
* Frontend: `resources/views/components/blocks/{hero,snprofile,snstat,…}.blade.php`
* Design system: `resources/css/theme.css` (`sn-*` utilities, CSS vars, orb animation)
* Motion: `resources/js/plugins/sn-orb.js` (IntersectionObserver reveal + tilt)
* Content: `database/seeders/SecondnetworkLandingSeeder.php` (pages, blocks, media, menus)
* Layout/fonts/header/footer: `resources/views/layouts/main.blade.php`

## 10. Further reading

* `AGENTS.md` — build/lint/test commands, code style, security rules
* `README.md` — features, requirements, installation
* `docs/relationship-block.md` — relationship block architecture + worked example
* `docs/query-sources-db-concept.md` — DB-driven query sources
* `GEMINI.md` — Livewire 4 cookbook (attributes, `@teleport`, `wire:navigate`, testing)
* https://laravel.com/docs/boost — Boost guidelines/skills/rules model used here
