# Relationship Block

The **Relationship** block lets editors pull records from any registered Eloquent
model into a page or post and render them as a list — for example "the 5 latest
blog posts", a curated set of team members, or related pages.

It supports two modes:

- **Automatic** — query a source by ordering + limit (a dynamic list).
- **Manual** — hand-pick specific records via a dual-list picker with search and
  drag-and-drop ordering (a curated list).

Which sources are available, and how each record is rendered on the frontend, is
fully driven by configuration — no block code changes are needed to add a new
source.

---

## Using the block (step by step)

This is the everyday workflow once at least one source exists. If no source is
available yet, jump to [Setting up a source](#setting-up-a-source) first.

### 1. Add the block to a page

In the page/post builder, open the block palette and add a **Relationship**
block (teal database icon). The block has no datafields — its whole
configuration lives in the inline edit panel that appears on the block.

### 2. Choose a source

In the block's edit panel, open the **Source** dropdown and pick what to pull in
(e.g. *Blog posts*, *Pages*, *Team*). The list comes from the registered sources
(`/admin/query-sources`). Nothing else shows until a source is selected.

### 3. Pick a mode

A **Mode** toggle appears with two options:

| Mode | Use it when | What you configure |
| --- | --- | --- |
| **Automatic** | The list should stay current on its own ("latest 5 posts"). | Order field, direction, limit. |
| **Manual** | You want an exact, hand-curated set in a specific order. | Which records, and their order. |

You can switch modes at any time; each mode keeps its own settings.

### 4a. Automatic mode

Three controls appear, plus a **live preview** of the matched records:

- **Order by** — a field from the source's allowed order fields (e.g.
  `created_at`, `title`).
- **Direction** — the ascending / descending icons toggle the sort order
  (default: descending).
- **Limit** — how many records to show (1–100, default 5).

The preview list updates immediately so you can see exactly what visitors will
get. Records are also filtered by the source's status/scope (e.g. only
*published* posts) — that part is fixed by the source, not editable per block.

### 4b. Manual mode

Two lists sit side by side:

- **Available** (left) — every selectable record. Type in the search box to
  filter server-side (handy when there are many records); a spinner shows while
  it searches. Click a record (or the **+**) to add it.
- **Selected** (right) — your chosen records, in the order they'll render.
  - **Reorder**: drag the grip handle (⋮⋮) to move a record up or down.
  - **Remove**: click the **✕** to send it back to *Available*.

The order on the right is exactly the order on the frontend.

### 5. Save and view

The block saves as you edit (each change writes block meta immediately). Publish
/ view the page and the records render through the source's configured item view
— e.g. blog posts as cards with thumbnail and category, team members with photo
and contact details. If a source has no item view, records render as plain title
links.

> **Switching auto → manual (or back)** never loses data: your manual selection
> and your auto settings are stored under separate meta keys, so toggling just
> changes which one is used.

---

## Setting up a source

Before editors can pick a source, an admin registers it once. Two steps:

1. **Allow-list the model** in `config/kompass.php` under `query_source_models`
   (a key → model class map). This is a security guard — only allow-listed
   models can ever be queried.
2. **Create the source** at **Admin → Query sources** (`/admin/query-sources`,
   admin role only): click **Add**, choose the allow-listed model, set the
   display fields, ordering, optional filters (status / scope), the frontend
   item view, and the wrapper layout. Drag rows to set their order in the
   editor's Source dropdown.

The full end-to-end walkthrough — migration, model, allow-list, source row, and
item view — is the [Team members worked example](#adding-a-new-source--full-worked-example-team-members)
at the end of this document.

---

## Architecture at a glance

| Concern | Where |
| --- | --- |
| Block type registration | `config/kompass.php` → `block_types.relationship` |
| Allow-listed models | `config/kompass.php` → `query_source_models` (key → FQCN) |
| Selectable sources | `query_sources` table, managed at `/admin/query-sources` (merged with optional `config('kompass.query_models')`) |
| Query execution / helpers | `src/Helpers/helpers.php` |
| Admin edit UI (inline) | `resources/views/components/block/relationship.blade.php` |
| Admin builder dispatch | `resources/views/components/blocks-datafield.blade.php` |
| Editor actions (Livewire) | `src/Livewire/PagesData.php`, `src/Livewire/PostsData.php` |
| Frontend rendering | `resources/views/components/blocks/relationship.blade.php` (published to the app) |
| Per-source item views | `resources/views/components/relations/*.blade.php` (published to the app) |

The configuration (chosen source, mode, ordering, limit, manual selection) is
stored entirely in **block meta** — the block has no datafields.

---

## Data model (block meta keys)

| Meta key | Type | Meaning |
| --- | --- | --- |
| `query-model` | string | The chosen source's `key` (resolved via `query_models()`). |
| `query-mode` | string | `auto` (default) or `manual`. |
| `query-order` | string | Column to order by (auto mode). Must be one of the source's `order_fields`. |
| `query-direction` | string | `asc` or `desc` (auto mode, default `desc`). |
| `query-limit` | int | Max records to return (auto mode, clamped 1–100, default 5). |
| `query-ids` | int[] | Selected record IDs in display order (manual mode). Stored as JSON. |

Meta arrays are transparently JSON-encoded/decoded by the `HasMeta` trait, so
`query-ids` is read back as a PHP array.

---

## Configuration

### Registering a source

Sources are **database-managed**: each row in the `query_sources` table is one
selectable source, created and edited at **Admin → Query sources**
(`/admin/query-sources`, admin role only). Before a source can point at a model,
that model must be allow-listed in `config/kompass.php`:

```php
// config/kompass.php — a raw class name is never instantiated from a DB row
'query_source_models' => [
    'pages' => \Secondnetwork\Kompass\Models\Page::class,
    'posts' => \Secondnetwork\Kompass\Models\Post::class,
    // 'teams' => \App\Models\TeamMember::class,
],
```

`query_models()` then resolves each DB row into the source shape below (DB
columns map: `model_key` → `model` via the allow-list, `status_filter` →
`status`). The full Team walkthrough is at the end of this document. For
fresh installs, provision rows in a seeder (see `QuerySourceSeeder`).

> **Optional config sources.** You can still hardcode a source in
> `config('kompass.query_models')` using the same shape as below. Config sources
> are merged with DB rows and **win on key collision**, so a built-in can never
> be shadowed by a database row.

The resolved source shape (DB column → array key in parentheses):

| Key | Required | Description |
| --- | --- | --- |
| `label` | yes | Human label shown in the source `<select>`. |
| `model` | yes | Eloquent model class, resolved from the row's `model_key` via the allow-list. |
| `display_fields` | yes | List of attributes shown in the picker/preview (joined with ` · `) and searched server-side. **The first entry is the record's title / link text** (exposed as the derived `label_field`). |
| `order_fields` | yes | Columns offered in the "Order by" select (auto mode). First entry is the default. |
| `url_pattern` | no | URL built per record; `{slug}` is replaced with the record's `slug`. Empty → records render without a link. |
| `status` | no | From the `status_filter` column. If set, the query filters `where('status', <value>)` (auto mode only). |
| `scope` | no | Eloquent local scope applied to the query (e.g. `active` → `scopeActive()`). Applied in both auto mode and the candidate picker, **only** when the model actually defines it. |
| `item_view` | no | Anonymous Blade component rendering **one** record on the frontend. Falls back to a plain title link when unset or missing. |
| `wrapper_class` | no | CSS classes for the element wrapping the rendered items (e.g. a responsive grid). Defaults to `grid gap-4`. |
| `with` | no | Relations eager-loaded on every queried record to avoid N+1 when the item view reads them (e.g. `['category']`). The **Eager loads** field. |

> Row **order** in the admin list is set by dragging rows (grip handle), and
> determines the order sources appear in the block's source `<select>`.

---

## Helper API

Defined in `src/Helpers/helpers.php`, globally available.

### `query_models(): array`

Returns the registered sources, keyed by source key. Merges the `query_sources`
table (allow-list-guarded, cached under `kompass-query-sources`) with any
`config('kompass.query_models')` entries — config wins on key collision.

### `kompass_query($block): Illuminate\Support\Collection`

Runs the block's configured query and returns the matched records.

- **Auto mode** — applies `status`, `order`, `direction`, `limit`.
- **Manual mode** — loads the records in `query-ids` and preserves their saved
  order.
- Applies the source's `with` eager-loads in both modes.
- Returns an empty collection when nothing is configured or the source key is
  unknown.

### `kompass_query_candidates(string $modelKey, int $limit = 50, ?string $search = null): Collection`

Returns selectable records for the manual picker. When `$search` is provided it
filters server-side across the source's `display_fields`, `OR`-ing a
`like '%term%'` per field. Applies the source's `scope`.

### `kompass_query_url(string $modelKey, $record): ?string`

Builds the frontend URL for a record from the source's `url_pattern` (replacing
`{slug}`). Returns `null` when the source has no pattern.

### `kompass_query_label(string $modelKey, $record): string`

Human label for a record. Joins the source's `display_fields` with ` · `; falls
back to `#id` when nothing is filled. Used by the admin picker and preview.

### `kompass_apply_scope($query, string $modelClass, ?string $scope): void`

Applies a named Eloquent local scope to a query, but **only** when the model
defines `scope{Name}()`. Guards against calling arbitrary methods from
admin-supplied configuration.

---

## Editor actions (Livewire)

These public methods live on **both** `PagesData` and `PostsData` (the page/post
builders) and are invoked from the inline edit component:

| Method | Trigger | Effect |
| --- | --- | --- |
| `saveBlockMeta($blockId, $metaKey, $value)` | source/mode/order/direction/limit changes | Writes (or clears, when empty) a single block meta key. |
| `toggleQueryRecord($blockId, $recordId)` | clicking a record in the picker | Adds the ID to `query-ids` (appended, preserving order) or removes it if present. |
| `reorderQueryRecord(string $item, int $position)` | drag-and-drop in the "Selected" list | Moves a record to a new position in `query-ids`. The `$item` key is encoded as `"{blockId}-{recordId}"`. |

Drag-and-drop uses Livewire's built-in `wire:sort` directive (no external
package). `wire:sort` calls the method with `(itemKey, newIndex)`.

The manual picker's search term is held in `public array $relationshipSearch`
(keyed by block id) and threaded down via the `relationshipSearch` prop:
`pages-show` / `posts-show` → `blocks-datafield` → `block.relationship`.

---

## Frontend rendering

The published block component
`resources/views/components/blocks/relationship.blade.php` resolves the source's
`item_view` and renders each record through it, falling back to a plain title
link when no item view exists:

```blade
@php
    $records     = $selected ? kompass_query($item) : collect();
    $itemView    = $selected['item_view'] ?? null;
    $hasItemView = $itemView && view()->exists('components.' . $itemView);
@endphp

@if ($records->isNotEmpty())
    <div class="{{ $wrapper }}">
        @foreach ($records as $record)
            @php $url = kompass_query_url($modelKey, $record); @endphp
            @if ($hasItemView)
                <x-dynamic-component :component="$itemView" :record="$record" :url="$url" :model-key="$modelKey" />
            @else
                {{-- plain title link fallback --}}
            @endif
        @endforeach
    </div>
@endif
```

### Item view contract

An item view is an anonymous Blade component that renders a single record. It
receives:

| Prop | Description |
| --- | --- |
| `$record` | The Eloquent model instance. |
| `$url` | The record's URL (from `url_pattern`) or `null`. |
| `$modelKey` | The source key (e.g. `posts`). |

Example — `resources/views/components/relations/post.blade.php`:

```blade
@props(['record', 'url' => null, 'modelKey' => null])

<article class="card bg-base-100 overflow-hidden">
    @if ($record->thumbnails)
        <a href="{{ $url ?? '#' }}" class="block aspect-[16/9] overflow-hidden">
            <x-image :id="$record->thumbnails" class="w-full h-full object-cover" />
        </a>
    @endif
    <div class="card-body p-4">
        @if ($record->category)
            <span class="badge badge-sm badge-{{ $record->category->color ?? 'neutral' }}">
                {{ $record->category->name }}
            </span>
        @endif
        <h3 class="card-title text-base">
            <a href="{{ $url }}" class="hover:underline">{{ $record->title }}</a>
        </h3>
    </div>
</article>
```

---

## Adding a new source — full worked example (Team members)

This walks through creating a brand-new `TeamMember` model from scratch and
wiring it up as a relationship source. The team member has these fields:

| Field | Column | Type |
| --- | --- | --- |
| Name | `name` | string |
| Photo | `photo` | file id (Kompass medialibrary) |
| Role / function | `role` | string |
| Phone | `phone` | string |
| Email | `email` | string |
| Address | `address` | string |
| Description | `description` | text |
| Sort order | `order` | integer |
| Active | `is_active` | boolean *(optional — used to demo the `scope` filter)* |

### 1. Create the migration

```bash
php artisan make:migration create_team_members_table
```

```php
// database/migrations/xxxx_xx_xx_create_team_members_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('photo')->nullable(); // medialibrary file id
            $table->string('role')->nullable();              // Funktion
            $table->string('phone')->nullable();             // Telefon
            $table->string('email')->nullable();
            $table->string('address')->nullable();           // Adresse
            $table->text('description')->nullable();         // Beschreibung
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);     // optional, for the Scope demo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
```

```bash
php artisan migrate
```

### 2. Create the model

```bash
php artisan make:model TeamMember
```

```php
// app/Models/TeamMember.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Secondnetwork\Kompass\Models\File;

use Illuminate\Database\Eloquent\Builder;

class TeamMember extends Model
{
    protected $guarded = [];

    /**
     * The photo points at a Kompass medialibrary file by id, mirroring how
     * Post::thumbnailFile() works.
     */
    public function photoFile()
    {
        return $this->belongsTo(File::class, 'photo');
    }

    /**
     * Optional: a local scope the source can reference via its `scope` field
     * (e.g. Scope = "active" → this method). Only applied if it exists.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
```

> The `photo` column stores a medialibrary file id (the same convention as
> `posts.thumbnails`), so it renders with `<x-image :id="$record->photo" />`.

### 3. Allow-list the model

Sources are stored in the database, but the model they may point at must be
allow-listed in `config/kompass.php` (a raw class name is never instantiated
from a DB row). Add the model under `query_source_models`, keyed by a short
`model_key`:

```php
// config/kompass.php
'query_source_models' => [
    'pages' => \Secondnetwork\Kompass\Models\Page::class,
    'posts' => \Secondnetwork\Kompass\Models\Post::class,
    'teams' => \App\Models\TeamMember::class, // ← add this
],
```

### 4. Create the source

Source definitions live in the `query_sources` table and are managed from
**Admin → Query sources** (`/admin/query-sources`, admin role only). Click
**Add** and fill in:

| Field | Value | Notes |
| --- | --- | --- |
| Label | `Team` | Shown in the block's source `<select>`. |
| Key | `team` | Stable identifier saved on blocks — avoid renaming later. |
| Model | `teams (TeamMember)` | The `model_key` you allow-listed in step 3. |
| Display fields | `name, role` | First field is the title/link text; all are searched. |
| Order fields | `order, name` | Offered in the "Order by" select (auto mode). |
| Status filter | *(empty)* | Simple `where('status', …)`; not needed here. |
| Scope | `active` | Optional — calls `scopeActive()` from step 2. |
| URL pattern | *(empty)* | Team members have no detail page → rendered without a link. |
| Item view | `relations.team` | The component from step 5. |
| Wrapper class | `grid gap-8 sm:grid-cols-2 lg:grid-cols-3` | Layout around the rendered items. |
| Eager loads | *(empty)* | Add relations here if the item view reads them (avoids N+1). |

Row **order** in the list is set by dragging rows (the grip handle), not a form
field.

> **Provisioning sources in code.** For fresh installs / version control, create
> the same rows in a seeder instead of by hand (see `QuerySourceSeeder`):
>
> ```php
> use Secondnetwork\Kompass\Models\QuerySource;
>
> QuerySource::updateOrCreate(['key' => 'team'], [
>     'label'          => 'Team',
>     'model_key'      => 'teams',
>     'display_fields' => ['name', 'role'],
>     'order_fields'   => ['order', 'name'],
>     'scope'          => 'active',
>     'item_view'      => 'relations.team',
>     'wrapper_class'  => 'grid gap-8 sm:grid-cols-2 lg:grid-cols-3',
>     'order'          => 3,
> ]);
> ```
>
> The `model_key` must exist in `query_source_models`; rows pointing at a
> non-allow-listed model are silently skipped by `query_models()`.

### 5. Create the item view

At `resources/views/components/relations/team.blade.php` — renders all
fields, with `tel:`/`mailto:` links:

```blade
@props(['record', 'url' => null, 'modelKey' => null])

<div class="card bg-base-100 text-center p-6">
    {{-- Photo --}}
    @if ($record->photo)
        <x-image :id="$record->photo" class="w-32 h-32 rounded-full mx-auto object-cover" />
    @endif

    {{-- Name + role/function --}}
    <h3 class="mt-4 text-lg font-semibold">{{ $record->name }}</h3>
    @if ($record->role)
        <p class="text-sm text-primary">{{ $record->role }}</p>
    @endif

    {{-- Description --}}
    @if ($record->description)
        <p class="mt-2 text-sm text-base-content/70">{{ $record->description }}</p>
    @endif

    {{-- Contact details --}}
    <ul class="mt-3 space-y-1 text-sm">
        @if ($record->phone)
            <li>
                <a href="tel:{{ preg_replace('/[^\d+]/', '', $record->phone) }}" class="hover:underline">
                    {{ $record->phone }}
                </a>
            </li>
        @endif
        @if ($record->email)
            <li>
                <a href="mailto:{{ $record->email }}" class="hover:underline">{{ $record->email }}</a>
            </li>
        @endif
        @if ($record->address)
            <li class="text-base-content/60">{{ $record->address }}</li>
        @endif
    </ul>
</div>
```

### 6. Done

The editor can now add a Relationship block, pick **Team** as the source, and
either auto-query (ordered by `order`, filtered to active members via the
`scope`) or manually select members with search and drag-and-drop ordering. No
block code changes were required — the new source came from one allow-list entry,
one database row (admin UI or seeder), and one item view.

> Tip: if an item view reads a relation (e.g. `$record->department`), add it to
> the source's **Eager loads** (`with`) field to keep the frontend query free of
> N+1s.

---

## Adding translations

Editor-facing strings in the block UI are wrapped in `__()`. Add the keys to the
relevant locale files under `resources/lang/<locale>.json` (e.g. `de.json`,
`fr.json`, `es.json`). English falls back to the key itself, so `en.json` needs
no entries.

---

## Notes & limitations

- Manual mode ignores the `status` filter — the editor's explicit selection wins.
- The candidate picker is capped (default 50) and relies on server-side search to
  reach records beyond the cap.
- The block has no datafields; everything is block meta. Deleting the block
  removes its meta as usual.
- `query-limit` is clamped to 1–100 in `kompass_query()`.
