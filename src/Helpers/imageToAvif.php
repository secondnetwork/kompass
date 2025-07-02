<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Facades\Image;

function imageToAvif(string $imagePath = '', ?int $width = null, ?int $height = null, array $config = []): ?string
{
    // Check for AVIF support
    if (! function_exists('imageavif') && ! isAvifSupported()) {
        return null;
    }

    $quality = $config['quality'] ?? 50;
    $crop = $config['crop'] ?? false;

    $cacheKey = "imageAvif/{$imagePath}/{$width}/{$height}/{$quality}/{$crop}";
    $cachedUrl = Cache::get($cacheKey);
    $storage = Storage::disk(config('kompass.storage.disk'));

    $urlPrefix = '/storage/';
    $diskPathImages = str_replace($urlPrefix, '', $imagePath);

    if (! $storage->exists($diskPathImages)) {
        return null;
    }

    //$image = Image::read(file_get_contents(config('app.url').$imagePath));

    $image = Image::read($storage->get($diskPathImages));   
    $imageMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

    if (! in_array($image->exif('FILE.MimeType'), $imageMimeTypes)) {
        return $imagePath;
    }

    if ($cachedUrl === null || ! $storage->exists(str_replace($urlPrefix, '', $cachedUrl))) {
        $imageDir = pathinfo($imagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($imagePath, PATHINFO_FILENAME);

        $width = $width ?? 1600;
        $height = $height ?? 1600;
        $resizedImagePath = "media/{$imageDir}/{$filename}-{$width}x{$height}.avif";

        if ($storage->exists($resizedImagePath)) {
            return $urlPrefix.$resizedImagePath;
        }

        $crop ? $image->resize($width, $height) : $image->scale($width, $height);

        $imageData = $image->toAvif($quality);
        $storage->put($resizedImagePath, $imageData, 'public');

        $cachedUrl = $urlPrefix.$resizedImagePath;
        Cache::put($cacheKey, $cachedUrl, now()->addDay());
    }

    return $cachedUrl;
}

function isAvifSupported(): bool
{
    if (extension_loaded('imagick') && class_exists('Imagick')) {
        $imagick = new \Imagick;
        $formats = $imagick->queryFormats();

        return in_array('AVIF', $formats, true);
    }

    return false;
}
