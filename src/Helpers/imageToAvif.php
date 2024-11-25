<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Facades\Image;

function imageToAvif($imagePath = '', $width = null, $height = null, $config = [])
{
    // Check for AVIF support
    if (!function_exists('imageavif') && !isAvifSupported()) {
        return;
    }

    // Available configs
    $quality = $config['quality'] ?? 50;
    $crop = !empty($config['crop']);

    $cacheKey = "imageAvif/" . ($imagePath ?: '') . "/$width/$height/$quality/$crop";
    $cachedUrl = Cache::get($cacheKey);

    $storage = Storage::disk(config('kompass.storage.disk'));
    $urlPrefix = '/storage/';
    
    // Ensure $imagePath is not null
    $diskPathImages = str_replace($urlPrefix, '', $imagePath ?? '');

    // Don't continue when original file doesn't exist
    if (!Storage::disk('public')->exists($diskPathImages)) {
        return;
    }

    // Read the image
    $hostname = config('app.url');
    $image = Image::read(file_get_contents($hostname . $imagePath));

    $imageMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($image->exif('FILE.MimeType'), $imageMimeTypes)) {
        return $imagePath;
    }

    // Check if the cached URL exists or if the resized image needs to be created
    if ($cachedUrl === null || !Storage::exists(str_replace($urlPrefix, '', $cachedUrl))) {
        // Create the new image path
        $splitAt = strrpos($imagePath, '/storage/');
        $imageDir = substr($imagePath, 0, $splitAt);
        $filename = pathinfo($imagePath, PATHINFO_FILENAME);

        // Set default dimensions if not provided
        $width = $width ?: 1600;
        $height = $height ?: 1600;

        $resizedImagePath = "media/$imageDir{$filename}-$width" . "x$height.avif";

        // Return cached URL if the resized image already exists
        if ($storage->exists($resizedImagePath)) {
            return $urlPrefix . $resizedImagePath;
        }

        // Resize or crop the image
        $crop ? $image->resize($width, $height) : $image->scale($width, $height);

        // Convert image to AVIF format and save to storage
        $imageData = $image->toAvif($quality);
        $storage->put($resizedImagePath, $imageData, 'public');

        // Cache the URL for future requests
        $cachedUrl = $urlPrefix . $resizedImagePath;
        Cache::put($cacheKey, $cachedUrl, 3600); // Cache for 1 hour
    }

    return $cachedUrl;
}

function isAvifSupported()
{
    if (extension_loaded('imagick') && class_exists('Imagick')) {
        $imagick = new \Imagick;
        $formats = $imagick->queryFormats();
        return in_array('AVIF', $formats);
    }
    return false;
}
