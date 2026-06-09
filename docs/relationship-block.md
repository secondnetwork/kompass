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

## Architecture at a glance

| Concern | Where |
| --- | --- |
| Block type registration | `config/kompass.php` → `block_types.relationship` |
| Selectable sources | `config/kompass.php` → `query_models` |
| Query execution / helpers | `src/Helpers/helpers.php` |
| Admin edit UI (inline) | `resources/views/components/block/relationship.blade.php` |
| Admin builder dispatch | `resources/views/components/blocks-datafield.blade.php` |
| Editor actions (Livewire) | `src/Livewire/PagesData.php`, `src/Livewire/PostsData.php` |
| Frontend rendering | `resources/views/components/blocks/relationship.blade.php` (published to the app) |
| Per-source item views | `resources/views/components/blocks/relations/*.blade.php` (published to the app) |

The configuration (chosen source, mode, ordering, limit, manual selection) is
stored entirely in **block meta** — the block has no datafields.

---

## Data model (block meta keys)

| Meta key | Type | Meaning |
| --- | --- | --- |
| `query-model` | string | Key into `config('kompass.query_models')` (the chosen source). |
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

Add an entry to the `query_models` array in `config/kompass.php`:

```php
'query_models' => [
    'posts' => [
        'label'         => 'Blog posts',
        'model'         => \Secondnetwork\Kompass\Models\Post::class,
        'label_field'   => 'title',
        'order_fields'  => ['created_at', 'updated_at', 'title'],
        'url_pattern'   => '/blog/{slug}',
        'status'        => 'published',
        'item_view'     => 'blocks.relations.post',
        'wrapper_class' => 'grid gap-6 sm:grid-cols-2 lg:grid-cols-3',
        'with'          => ['category'],
    ],
],
```

| Key | Required | Description |
| --- | --- | --- |
| `label` | yes | Human label shown in the source `<select>`. |
| `model` | yes | Fully-qualified Eloquent model class. |
| `label_field` | yes | Attribute used as the record's display title (admin picker, fallback rendering, search column). |
| `order_fields` | yes | Columns offered in the "Order by" select (auto mode). First entry is the default. |
| `url_pattern` | no | URL built per record; `{slug}` is replaced with the record's `slug`. Set to `null` to render records without a link. |
| `status` | no | If set, the query filters `where('status', <value>)` (auto mode only). |
| `item_view` | no | Anonymous Blade component rendering **one** record on the frontend. Falls back to a plain title link when unset or missing. |
| `wrapper_class` | no | CSS classes for the element wrapping the rendered items (e.g. a responsive grid). Defaults to `grid gap-4`. |
| `with` | no | Relations eager-loaded on every queried record to avoid N+1 when the item view reads them (e.g. `['category']`). |

---

## Helper API

Defined in `src/Helpers/helpers.php`, globally available.

### `query_models(): array`

Returns the registered sources (`config('kompass.query_models')`).

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
filters server-side with `where(label_field, 'like', '%term%')`.

### `kompass_query_url(string $modelKey, $record): ?string`

Builds the frontend URL for a record from the source's `url_pattern` (replacing
`{slug}`). Returns `null` when the source has no pattern.

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

Example — `resources/views/components/blocks/relations/post.blade.php`:

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
}
```

> The `photo` column stores a medialibrary file id (the same convention as
> `posts.thumbnails`), so it renders with `<x-image :id="$record->photo" />`.

### 3. Register the source

In `config/kompass.php`:

```php
'query_models' => [
    // …existing sources…
    'teams' => [
        'label'         => 'Team',
        'model'         => \App\Models\TeamMember::class,
        'label_field'   => 'name',
        'order_fields'  => ['order', 'name'],
        'url_pattern'   => null,            // team members have no detail page
        'item_view'     => 'blocks.relations.team',
        'wrapper_class' => 'grid gap-8 sm:grid-cols-2 lg:grid-cols-3',
    ],
],
```

### 4. Create the item view

At `resources/views/components/blocks/relations/team.blade.php` — renders all
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

### 5. Done

The editor can now add a Relationship block, pick **Team** as the source, and
either auto-query by `order` or manually select members (with search and
drag-and-drop ordering). No block code changes were required — the new source and
its layout came entirely from config + one item view.

> Tip: if an item view reads a relation (e.g. `$record->department`), add it to
> the source's `with` array (`'with' => ['department']`) to keep the frontend
> query free of N+1s.

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
