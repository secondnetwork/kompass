# Concept: Database-managed query sources (Relationship block)

> **Status: proposal / not implemented.** This document describes how the
> `query_models` registry (see [relationship-block.md](relationship-block.md))
> could be made manageable from the backend / database, including the hard
> constraints and a recommended architecture. No code exists yet.

## Goal

Today, relationship-block sources live in `config/kompass.php` → `query_models`.
Adding a source means editing a PHP file. The goal is to let an administrator
**register and configure sources from the admin UI**, stored in the database —
mirroring how `Blocktemplates` already extends the built-in `block_types`.

## The hard constraint: data vs. code

A `query_models` entry mixes two fundamentally different things:

| Part | DB-manageable? | Reason |
| --- | --- | --- |
| `label`, `label_field`, `order_fields`, `url_pattern`, `status`, `wrapper_class`, `with` | ✅ yes | plain configuration values (strings / arrays) |
| `model` (e.g. `App\Models\TeamMember`) | ⚠️ allowlist only | a PHP/Eloquent class — must already exist in code |
| `item_view` (e.g. `relations.team`) | ⚠️ selection only | a Blade component — must already exist as a file |

**Conclusion:** the backend can *wire up* which existing model + existing view
are offered as a source, and set all the plain config values. It **cannot create
a new model or a new layout from the database** — a model is a table + Eloquent
class, an item view is a Blade template; both are code.

## Security: never trust a class name from the DB

`kompass_query()` does `$modelClass::query()`. If `$modelClass` came from a free
text field stored in the DB, that is an arbitrary-class-instantiation risk.

**Rule:** the model must be chosen from a server-side **allowlist** registered in
config — never a free-text class name. Proposed config key:

```php
// config/kompass.php
'query_source_models' => [
    // key => FQCN; only these may back a DB source
    'pages' => \Secondnetwork\Kompass\Models\Page::class,
    'posts' => \Secondnetwork\Kompass\Models\Post::class,
    'team'  => \App\Models\TeamMember::class,
],
```

The admin form offers a `<select>` of these keys; the DB stores the **key**, and
the merge layer resolves it to the FQCN. An unknown key resolves to nothing
(source is skipped), so stale rows can never instantiate an arbitrary class.

## Proposed schema: `query_sources`

```php
Schema::create('query_sources', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();        // e.g. "team" (used as query-model meta value)
    $table->string('label');
    $table->string('model_key');             // FK into config('kompass.query_source_models')
    $table->string('label_field')->default('title');
    $table->json('order_fields')->nullable();
    $table->string('url_pattern')->nullable();
    $table->string('status_filter')->nullable();
    $table->string('item_view')->nullable(); // chosen from scanned components/relations/*
    $table->string('wrapper_class')->nullable();
    $table->json('with')->nullable();
    $table->integer('order')->default(0);
    $table->timestamps();
});
```

## Merge layer

`query_models()` changes from "config only" to "config + DB", following the exact
pattern `BlockTypeRegistry` uses for built-ins + `Blocktemplates`:

```php
function query_models(): array
{
    $config = config('kompass.query_models', []);

    // Cache to avoid a query on every helper call within a request.
    $db = cache()->rememberForever('kompass-query-sources', function () {
        $allow = config('kompass.query_source_models', []);

        return QuerySource::orderBy('order')->get()
            ->filter(fn ($s) => isset($allow[$s->model_key]))   // allowlist guard
            ->mapWithKeys(fn ($s) => [$s->key => [
                'label'         => $s->label,
                'model'         => $allow[$s->model_key],         // resolve key -> FQCN
                'label_field'   => $s->label_field,
                'order_fields'  => $s->order_fields ?? ['created_at'],
                'url_pattern'   => $s->url_pattern,
                'status'        => $s->status_filter,
                'item_view'     => $s->item_view,
                'wrapper_class' => $s->wrapper_class,
                'with'          => $s->with ?? [],
            ]])
            ->all();
    });

    // Config wins on key collision (built-ins are never shadowed by DB rows).
    return $db + $config;
}
```

The cache must be flushed on `QuerySource` create/update/delete (the same
`cache()->flush()` hook the `Block` model already uses).

**No change needed** in `kompass_query()`, `kompass_query_candidates()`,
`kompass_query_url()` or the block UI — they all read through `query_models()`,
so DB sources work everywhere automatically.

## Admin UI

- A Livewire CRUD screen (e.g. `QuerySources`) under `/admin/...`, registered in
  the **admin-only** route group (same group `/admin/blocks` now lives in).
- Form fields:
  - **Key** — slug, unique (validated; this becomes the `query-model` meta value).
  - **Label** — free text.
  - **Model** — `<select>` from `config('kompass.query_source_models')` (allowlist).
  - **Label field / order fields** — text inputs; could be auto-suggested from the
    model's `Schema::getColumnListing()`.
  - **URL pattern**, **status filter**, **wrapper class** — text.
  - **Item view** — `<select>` populated by scanning
    `resources/views/components/relations/*.blade.php` (existing files only).
  - **Eager loads (`with`)** — repeater of relation names.
- Validation: `key` unique & slug; `model_key` ∈ allowlist; `item_view` ∈ scanned
  views; `order_fields` ⊆ the model's columns.

## Migration / backward compatibility

- Existing config sources keep working unchanged (config wins on collision).
- DB sources are additive — removing the feature later just stops merging them.
- Editors' saved blocks reference a source by its `key` in block meta
  (`query-model`); a deleted source ⇒ `query_models()[$key]` is missing ⇒
  `kompass_query()` already returns an empty collection (graceful).

## Optional stage B: field-mapping renderer

To remove the "item view must be a code file" constraint for standard cases:

- Add a `field_map` JSON column to `query_sources` (e.g.
  `{ "title": "name", "image": "photo", "text": "description" }`).
- Provide one generic frontend view `relations/_generic.blade.php` that
  renders title / image / text / link from the mapped columns.
- A source with no custom `item_view` falls back to the generic renderer driven
  by `field_map`.
- Custom Blade item views remain available for bespoke layouts.

This lets admins build a "team grid" or "post list" entirely from the DB, while
complex/branded layouts still use a hand-written component.

## Open questions

1. Should sources be **per-locale** (e.g. different label per language)?
2. Should the model allowlist be **auto-discovered** (scan `app/Models`) or stay
   an explicit config list? (Explicit is safer.)
3. Where exactly should the admin screen live in the navigation — under "Blocks"
   or a new "Content sources" entry?
4. Is stage B (field mapping) wanted now, or is stage A (wiring) enough?

## Effort estimate

- **Stage A (wiring):** migration + `QuerySource` model + cache-aware merge in
  `query_models()` + one Livewire admin CRUD screen + route + nav link +
  validation. Moderate.
- **Stage B (field mapping):** + `field_map` column, generic renderer view, and
  the field-mapping UI. Larger.
