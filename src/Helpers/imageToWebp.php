<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Facades\Image;


function imageToWebp($imagePath = '', $width = null, $height = null, $config = [])
{
    // Available configs
    $quality = $config['quality'] ?? 80;
    $crop = !empty($config['crop']);

    $cacheKey = "imageWebp/" . ($imagePath ?: '') . "/$width/$height/$quality/$crop";
    $cachedUrl = Cache::get($cacheKey);

    $storage = Storage::disk(config('kompass.storage.disk'));
    $urlPrefix = '/storage/';
    
    // Ensure $cachedUrl and $imagePath are not null
    $diskPath = str_replace($urlPrefix, '', $cachedUrl ?? '');
    $diskPathImages = str_replace($urlPrefix, '', $imagePath ?? '');

    // Check if the original file exists
    if (!Storage::disk('public')->exists($diskPathImages)) {
        return;
    }

    // Read the image only if it is not cached
    if ($cachedUrl === null || !Storage::exists($diskPath)) {
        $image = Image::read(file_get_contents(config('app.url') . $imagePath));

        // Validate image mime type
        if (!in_array($image->exif('FILE.MimeType'), ['image/jpeg', 'image/png', 'image/webp'])) {
            return $imagePath;
        }

        // Set default dimensions if not provided
        $width = $width ?: 1600;
        $height = $height ?: 1600;

        // Create the new image path
        $splitAt = strrpos($imagePath, '/storage/');
        $imageDir = substr($imagePath, 0, $splitAt);
        $filename = pathinfo($imagePath, PATHINFO_FILENAME);
        $resizedImagePath = "media/$imageDir{$filename}-$width" . "x$height.webp";

        // Return cached URL if the resized image already exists
        if ($storage->exists($resizedImagePath)) {
            return $urlPrefix . $resizedImagePath;
        }

        // Resize or crop the image
        $crop ? $image->resize($width, $height) : $image->scale($width, $height);

        // Convert image to WebP format and save to storage
        $imageData = $image->toWebp($quality);
        $storage->put($resizedImagePath, $imageData, 'public');

        // Cache the URL for future requests
        $cachedUrl = $urlPrefix . $resizedImagePath;
        Cache::put($cacheKey, $cachedUrl, 3600); // Cache for 1 hour
    }

    return $cachedUrl;
}
