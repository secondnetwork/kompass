<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Secondnetwork\Kompass\Models\File as Files;
use Secondnetwork\Kompass\Helpers\ImageFactory;

if (!function_exists('getImageID')) {
    /**
     * Helper für Bild-IDs aus der Datenbank
     */
    function getImageID($id, $sizeKey = null)
    {
        // Ruft die Factory auf, die den Builder zurückgibt
        return ImageFactory::getImageID($id, $sizeKey);
    }
}

if (!function_exists('getImageUrl')) {
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
    function get_field($type, $data, $class = null, $size = null)
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
        return route('kompass_asset').'?path='.urlencode($path);
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
            ? Illuminate\Support\Str::replaceLast($matches[0], '', $name).'_'.(intval($matches[2]) + 1)
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

            $value = Arr::get($settings, $group . '.' . $actualKey);

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
