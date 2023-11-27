<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

if (! function_exists('vendor_path')) {
    /**
     * Get the path to the litstack package vendor folder.
     *
     * @return string
     */
    function vendor_path(string $path = '')
    {
        return realpath(__DIR__.'/../../').$path;
    }
}

if (! function_exists('kompass_asset')) {
    function kompass_asset($path, $secure = null)
    {
        return route('kompass_asset').'?path='.urlencode($path);
    }
}
if (! defined('AVIFE_IMAGICK_VER')) {
    if (class_exists('Imagick')) {
        $v = Imagick::getVersion();
        preg_match('/ImageMagick ([0-9]+\.[0-9]+\.[0-9]+)/', $v['versionString'], $v);
        if (version_compare($v[1], '7.0.25') >= 0) {
            define('AVIFE_IMAGICK_VER', $v[1]);
        } else {
            define('AVIFE_IMAGICK_VER', 0);
        }
    } else {
        define('AVIFE_IMAGICK_VER', 0);
    }
}
function standardize(
    string|array|BackedEnum $value,
    bool $toArray = false
): string|array {
    if ($value instanceof BackedEnum) {
        return $toArray ? [$value->value] : $value->value;
    }

    if (is_array($value)) {
        return Collection::make($value)->map(function ($item) {
            return $item instanceof BackedEnum
                ? $item->value
                : $item;
        })->toArray();
    }

    if ((strpos($value, '|') === false) && ! $toArray) {
        return $value;
    }

    return explode('|', $value);
}

function sendFile(string $path)
{
    //dd(response(File::public_path()->get($path), 200));
    return response(File::get($path), 200)
        // ->header('Content-Length', $file->get_filesize())
        //     ->header('Cache-Control', 'max-age=' . $image->get_expires())
        //     ->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + $image->get_expires()))
        ->header('Last-Modified', filemtime($path));
}
function bytesToHuman($bytes)
{
    $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

    for ($i = 0; $bytes > 1024; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, 2).' '.$units[$i];
}
if (! function_exists('get_file_name')) {
    function get_file_name($name)
    {
        preg_match('/(_)([0-9])+$/', $name, $matches);
        if (count($matches) == 3) {
            return Illuminate\Support\Str::replaceLast($matches[0], '', $name).'_'.(intval($matches[2]) + 1);
        } else {
            return $name.'_1';
        }
    }
}
if (! function_exists('genSlug')) {
    function genSlug($value)
    {
        $porcess1 = str_replace(' ', '-', $value);
        $process2 = strtolower($porcess1);

        return $process2;
        // $this->slug = $process2;
    }
}
if (! function_exists('nameWithLastInitial')) {
    function nameWithLastInitial($name)
    {
        $usernames = explode(' ', $name);
        $last_name = array_pop($usernames);
        $first_name = array_pop($usernames);

        $first_retVal = (! empty($first_name)) ? $first_initial = $first_name[0] : $first_initial = '';
        $last_retVal = (! empty($last_name)) ? $last_initial = $last_name[0] : $last_initial = '';

        return $first_retVal.$last_retVal;
    }
}
if (! function_exists('setting')) {
    function setting($key = null, $default = null)
    {

        $keydata = explode('.', $key);
        foreach (explode('.', $key) as $segment) {
            $data = $segment;
        }

        if (! empty(Arr::get(config('settings'), $data))) {
            $data = Arr::get(config('settings'), $data);

            if ($data->group == $keydata[0]) {
                dump($data->data);

                return $data->data;
            }
        // return Arr::get(config('settings'), $data );
        } else {
            $data = Arr::get(app('settings'), $data);

            if (! empty($data->group) == $keydata[0]) {

                return $data->data;
            }
        }

        return $data;
    }
}

if (! function_exists('video_id')) {
    /* Parse the video uri/url to determine the video type/source and the video id */
    function video_id($url)
    {

        // Parse the url
        $parse = parse_url($url);

        // Set blank variables
        $video_type = '';
        $video_id = '';

        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=|live/)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            $video_type = 'youtube';

            // Here is a sample of the URLs this regex matches: (there can be more content after the given URL that will be ignored)

            // http://youtu.be/dQw4w9WgXcQ
            // http://www.youtube.com/embed/dQw4w9WgXcQ
            // http://www.youtube.com/watch?v=dQw4w9WgXcQ
            // http://www.youtube.com/?v=dQw4w9WgXcQ
            // http://www.youtube.com/v/dQw4w9WgXcQ
            // http://www.youtube.com/e/dQw4w9WgXcQ
            // http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ
            // http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ
            // http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ
            // http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ

            // It also works on the youtube-nocookie.com URL with the same above options.
            // It will also pull the ID from the URL in an embed code (both iframe and object tags)

            $youtube_id = $match[1];

            $video_id = $youtube_id;
        }
        //  $vimeo = 'https://vimeo.com/123942643';

        if (preg_match("/\b(?:vimeo)\.com\b/i", $url)) {

            if (preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/?(showcase\/)*([0-9))([a-z]*\/)*([0-9]{6,11})[?]?.*/", $url, $output_array)) {

                $video_id = $output_array[6];
                $video_type = 'vimeo';
            }
        }
        if (! empty($video_type)) {
            $video_array = [
                'type' => $video_type,
                'id' => $video_id,
            ];

            return $video_array;
        } else {
            return false;
        }
    }
}
