<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Secondnetwork\Kompass\Blocks\BlockTypeRegistry;
use Secondnetwork\Kompass\Blocks\FieldTypeRegistry;
use Secondnetwork\Kompass\Helpers\EditorMigrationHelper;
use Secondnetwork\Kompass\Helpers\ImageFactory;
use Secondnetwork\Kompass\Models\File as Files;
use Secondnetwork\Kompass\Seo\SeoService;

if (! function_exists('getImageID')) {
    /**
     * Helper für Bild-IDs aus der Datenbank
     */
    function getImageID($id, $sizeKey = null)
    {
        // Ruft die Factory auf, die den Builder zurückgibt
        return ImageFactory::getImageID($id, $sizeKey);
    }
}

if (! function_exists('getImageUrl')) {
    /**
     * Helper für direkte URLs
     */
    function getImageUrl($url, $sizeKey = null)
    {
        return ImageFactory::getImageUrl($url, $sizeKey);
    }
}

if (! function_exists('vendor_path')) {
    /**
     * Get the path to the litstack package vendor folder.
     */
    function vendor_path(string $path = ''): string
    {
        return realpath(__DIR__.'/../../').$path;
    }
}

if (! function_exists('get_thumbnails')) {
    function get_thumbnails($id_media, $class = null, $size = null): string
    {
        if ($file = Files::find($id_media)) {
            return generateImageHtml($file, $class, $size);
        }

        return '';
    }
}

if (! function_exists('get_field')) {
    function get_field($type, $data, $class = null, $size = null, $default = null)
    {
        foreach ($data as $value) {
            if ($value->type === $type && $value->data) {
                if (in_array($value->type, ['image', 'gallery'])) {
                    if ($file = Files::find($value->data)) {
                        return generateImageHtml($file, $class, $size, $value->type === 'gallery');
                    }
                }
                if (in_array($value->type, ['video'])) {
                    if ($file = Files::find($value->data)) {
                        return Storage::url($file->path.'/'.$file->slug.'.'.$file->extension);
                    }
                }
                if (in_array($value->type, ['poster'])) {
                    if ($file = Files::find($value->data)) {
                        return Storage::url($file->path.'/'.$file->slug.'.'.$file->extension);
                    }
                }

                return $value->data;
            }
        }

        return $default;
    }
}

if (! function_exists('get_fields')) {
    /**
     * Get all fields of a given type from datafield collection.
     *
     * @param  string  $type
     * @param  Collection|array  $data
     */
    function get_fields($type, $data): array
    {
        $results = [];

        foreach ($data as $value) {
            if ($value->type === $type && $value->data) {
                $results[] = $value->data;
            }
        }

        return $results;
    }
}

if (! function_exists('get_meta')) {
    /**
     * Get a meta value from a block item with an optional default.
     *
     * @param  object  $item
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function get_meta($item, $key, $default = null)
    {
        if (is_object($item) && method_exists($item, 'getMeta')) {
            return $item->getMeta($key) ?? $default;
        }

        return $default;
    }
}

if (! function_exists('get_field_as')) {
    /**
     * Get a field value with type casting.
     *
     * @param  string  $type
     * @param  Collection|array  $data
     * @param  string  $cast  int, string, bool, array
     * @param  mixed  $default
     * @return mixed
     */
    function get_field_as($type, $data, $cast = 'string', $default = null)
    {
        $value = get_field($type, $data, null, null, $default);

        if ($value === $default) {
            return $default;
        }

        return match ($cast) {
            'int', 'integer' => (int) $value,
            'bool', 'boolean' => (bool) $value,
            'array' => (array) $value,
            'object' => is_string($value) ? json_decode($value) : (object) $value,
            default => (string) $value,
        };
    }
}

function generateImageHtml($file, $class = null, $size = null, $includeDescription = false): string
{
    $sizes = $size ? "_$size" : '';
    $avifUrl = asset("storage/{$file->path}/{$file->slug}.avif");
    $imageUrl = asset("storage/{$file->path}/{$file->slug}{$sizes}.{$file->extension}");
    $description = $includeDescription ? "<span>{$file->description}</span>" : '';

    return "<picture>
                <source type='image/avif' srcset='{$avifUrl}'>
                <img class='{$class}' src='{$imageUrl}' alt='{$file->alt}' />
                {$description}
            </picture>";
}

if (! function_exists('kompass_asset')) {
    function kompass_asset($path, $secure = null): string
    {
        return asset('vendor/kompass/assets/'.$path);
    }
}

if (! defined('AVIFE_IMAGICK_VER')) {
    define('AVIFE_IMAGICK_VER', checkImagickVersion());
}

function checkImagickVersion(): string
{
    if (class_exists('Imagick')) {
        $v = Imagick::getVersion();
        preg_match('/ImageMagick ([0-9]+\.[0-9]+\.[0-9]+)/', $v['versionString'], $v);

        return version_compare($v[1], '7.0.25') >= 0 ? $v[1] : '0';
    }

    return '0';
}

function standardize(string|array|BackedEnum $value, bool $toArray = false): string|array
{
    if ($value instanceof BackedEnum) {
        return $toArray ? [$value->value] : $value->value;
    }

    if (is_array($value)) {
        return Collection::make($value)->map(fn ($item) => $item instanceof BackedEnum ? $item->value : $item)->toArray();
    }

    return strpos($value, '|') === false && ! $toArray ? $value : explode('|', $value);
}

function sendFile(string $path)
{
    return response(File::get($path), 200)->header('Last-Modified', filemtime($path));
}

function bytesToHuman($bytes): string
{
    $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];
    for ($i = 0; $bytes > 1024; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, 2).' '.$units[$i];
}

if (! function_exists('get_file_name')) {
    function get_file_name($name): string
    {
        preg_match('/(_)([0-9])+$/', $name, $matches);

        return count($matches) === 3
            ? Str::replaceLast($matches[0], '', $name).'_'.(intval($matches[2]) + 1)
            : $name.'_1';
    }
}

if (! function_exists('genSlug')) {
    function genSlug($title, $currentSlug = '', $model = null): string
    {
        $locale = config('app.locale');
        $slugCandidate = Str::slug($title, '-', $locale);

        if ($model && is_string($model) && class_exists($model) && $slugCandidate !== $currentSlug) {
            $numericalPrefix = 1;
            $originalSlug = $slugCandidate;
            while ($model::whereSlug($slugCandidate)->exists()) {
                $slugCandidate = $originalSlug.'-'.$numericalPrefix++;
            }
        }

        return $slugCandidate;
    }
}

if (! function_exists('nameWithLastInitial')) {
    function nameWithLastInitial($name): string
    {
        $usernames = explode(' ', $name);
        $last_name = array_pop($usernames);
        $first_name = array_pop($usernames);

        $first_initial = ! empty($first_name) ? $first_name[0] : '';
        $last_initial = ! empty($last_name) ? $last_name[0] : '';

        return $first_initial.$last_initial;
    }
}

if (! function_exists('setting')) {
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return app()->bound('settings') ? app('settings') : ($default ?? collect());
        }

        $keyParts = explode('.', $key);

        if (count($keyParts) !== 2) {
            return $default;
        }

        $group = $keyParts[0];
        $actualKey = $keyParts[1];

        $settings = app()->bound('settings') ? app('settings') : null;

        if ($settings !== null) {

            $value = Arr::get($settings, $group.'.'.$actualKey);

            return $value ?? $default;
        }

        return $default;
    }
}

if (! function_exists('videoEmbed')) {
    /**
     * Parse the video URI/URL to determine the video type/source and the video ID.
     *
     * @param  string  $url
     * @return array|false
     */
    function videoEmbed($url)
    {
        if (empty($url) || ! is_string($url)) {
            return false;
        }

        // Set blank variables
        $video_type = '';
        $video_id = '';

        // YouTube
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=|live/)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            $video_type = 'youtube';
            $video_id = $match[1];
        }

        // Vimeo
        if (preg_match("/\b(?:vimeo)\.com\b/i", $url)) {
            if (preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/?(showcase\/)*([0-9))([a-z]*\/)*([0-9]{6,11})[?]?.*/", $url, $output_array)) {
                $video_id = $output_array[6];
                $video_type = 'vimeo';
            }
        }

        // Facebook
        if (preg_match("/(?:facebook\.com\/.*\/videos\/)([0-9]+)/i", $url, $match)) {
            $video_type = 'facebook';
            $video_id = $match[1];
        }

        // Dailymotion
        if (preg_match("/dailymotion\.com\/video\/([a-zA-Z0-9]+)/", $url, $match)) {
            $video_type = 'dailymotion';
            $video_id = $match[1];
        }

        // TikTok
        if (preg_match("/tiktok\.com\/@[a-zA-Z0-9._]+\/video\/([0-9]+)/", $url, $match)) {
            $video_type = 'tiktok';
            $video_id = $match[1];
        }

        if (! empty($video_type)) {
            return [
                'type' => $video_type,
                'id' => $video_id,
            ];
        }

        return false;
    }
}

if (! function_exists('block_grid_classes')) {
    /**
     * @return array{gridCols: string, colSpan: string}
     */
    function block_grid_classes(mixed $item): array
    {
        $layoutgrid = is_object($item) ? intval($item->layoutgrid) : 0;

        return [
            'gridCols' => $layoutgrid > 0 ? 'md:grid-cols-'.$layoutgrid : '',
            'colSpan' => $layoutgrid > 0 ? 'md:col-span-'.$layoutgrid : '',
        ];
    }
}

if (! function_exists('to_compiled_array')) {
    /**
     * Helper to get flat blocks array from any shape.
     */
    function to_compiled_array(mixed $input): array
    {
        return EditorMigrationHelper::toCompiledArray($input);
    }
}

if (! function_exists('to_compiled_object')) {
    /**
     * Helper to get wrapped blocks object from any shape.
     */
    function to_compiled_object(mixed $input): object
    {
        return EditorMigrationHelper::toCompiledObject($input);
    }
}

if (! function_exists('wysiwyg_blocks')) {
    /**
     * Resolve and normalize wysiwyg editor data into the canonical compiled
     * render array. Accepts any of:
     *   - block item (object with ->datafield)        → resolved via get_field('wysiwyg', ...)
     *   - explicit Datafield-like object              → passed as $field, uses $field->data
     *   - already-loaded payload (array | string)     → used directly
     *   - null / empty                                → returns a single empty paragraph seed
     */
    function wysiwyg_blocks(mixed $item = null, $field = null): array
    {
        $data = match (true) {
            $field !== null && is_object($field) => $field->data ?? null,
            $field !== null => $field,  // already raw data (array or string)
            is_object($item) && isset($item->datafield) => get_field('wysiwyg', $item->datafield),
            is_array($item) || is_string($item) => $item,
            default => null,
        };

        return EditorMigrationHelper::toRenderBlocks($data);
    }
}

if (! function_exists('block_registry')) {
    /**
     * The block-type registry (single source of truth for block types).
     */
    function block_registry(): BlockTypeRegistry
    {
        return app(BlockTypeRegistry::class);
    }
}

if (! function_exists('field_registry')) {
    /**
     * The datafield-type registry.
     */
    function field_registry(): FieldTypeRegistry
    {
        return app(FieldTypeRegistry::class);
    }
}

if (! function_exists('query_models')) {
    /**
     * Registered queryable models for the relationship block.
     *
     * @return array<string,array<string,mixed>>
     */
    function query_models(): array
    {
        return config('kompass.query_models', []);
    }
}

if (! function_exists('kompass_query')) {
    /**
     * Run the relationship block's configured query and return the matched
     * records. Reads the chosen source, ordering and limit from block meta
     * (query-model, query-order, query-direction, query-limit) and resolves it
     * against the kompass.query_models registry. Returns an empty collection
     * when nothing is configured or the source is unknown.
     */
    function kompass_query($block): Collection
    {
        $key = get_meta($block, 'query-model');
        $models = query_models();

        if (! $key || ! isset($models[$key])) {
            return collect();
        }

        $config = $models[$key];
        $modelClass = $config['model'] ?? null;

        if (! $modelClass || ! class_exists($modelClass)) {
            return collect();
        }

        $with = $config['with'] ?? [];

        // Manual mode: render the editor's curated selection in the saved order.
        if (get_meta($block, 'query-mode') === 'manual') {
            $ids = get_meta($block, 'query-ids');
            $ids = is_array($ids) ? array_values(array_filter(array_map('intval', $ids))) : [];

            if (empty($ids)) {
                return collect();
            }

            $records = $modelClass::query()->with($with)->whereIn('id', $ids)->get()->keyBy('id');

            return collect($ids)
                ->map(fn ($id) => $records->get($id))
                ->filter()
                ->values();
        }

        $orderFields = $config['order_fields'] ?? ['created_at'];
        $order = get_meta($block, 'query-order') ?: ($orderFields[0] ?? 'created_at');
        if (! in_array($order, $orderFields, true)) {
            $order = $orderFields[0] ?? 'created_at';
        }

        $direction = strtolower((string) get_meta($block, 'query-direction')) === 'asc' ? 'asc' : 'desc';
        $limit = max(1, min(100, (int) (get_meta($block, 'query-limit') ?: 5)));

        $query = $modelClass::query()->with($with);

        if (! empty($config['status'])) {
            $query->where('status', $config['status']);
        }

        return $query->orderBy($order, $direction)->limit($limit)->get();
    }
}

if (! function_exists('kompass_query_candidates')) {
    /**
     * Selectable records of a query source, used by the relationship block's
     * manual-selection list. An optional search term filters server-side on the
     * source's label field. Capped to keep the picker responsive.
     */
    function kompass_query_candidates(string $modelKey, int $limit = 50, ?string $search = null): Collection
    {
        $config = query_models()[$modelKey] ?? null;
        $modelClass = $config['model'] ?? null;

        if (! $modelClass || ! class_exists($modelClass)) {
            return collect();
        }

        $orderFields = $config['order_fields'] ?? ['created_at'];
        $labelField = $config['label_field'] ?? 'title';

        $query = $modelClass::query();

        if ($search !== null && trim($search) !== '') {
            $query->where($labelField, 'like', '%'.trim($search).'%');
        }

        return $query
            ->orderBy($orderFields[0] ?? 'created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}

if (! function_exists('kompass_query_url')) {
    /**
     * Build the frontend URL for a queried record using the source's
     * url_pattern ("{slug}" is replaced with the record slug). Returns null
     * when the source has no pattern.
     */
    function kompass_query_url(string $modelKey, $record): ?string
    {
        $pattern = query_models()[$modelKey]['url_pattern'] ?? null;

        if (! $pattern) {
            return null;
        }

        return url(str_replace('{slug}', (string) ($record->slug ?? ''), $pattern));
    }
}

if (! function_exists('seo')) {
    /**
     * Get or configure the SEO service.
     */
    function seo(): SeoService
    {
        return app('seo');
    }
}
