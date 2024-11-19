<?php

use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Facades\Image;

function imageToAvif($imagePath = '', $width = null, $height = null, $config = [])
{

    $avifImagickSupport = '0';
    if (extension_loaded('imagick') && class_exists('Imagick')) {
        $imagick = new \Imagick;
        $formats = $imagick->queryFormats();
        if (in_array('AVIF', $formats)) {
            $avifImagickSupport = '1';
        } else {
            $avifImagickSupport = '0';
        }
    }

    if (! function_exists('imageavif') && $avifImagickSupport <= '0') {
        return;
    }

    // Available configs
    $quality = isset($config['quality']) ? $config['quality'] : 50;
    $crop = isset($config['crop']) ? (bool) ($config['crop']) : false;

    $cacheKey = "imageAvif/$imagePath/$width/$height/$quality/$crop";

    $cachedUrl = Cache::get($cacheKey);

    $storage = Storage::disk(config('kompass.storage.disk'));

    // Setup the image URLs
    // - You can add ASSET_URL=http://... to your .env to reference images through a CDN
    $hostname = config('app.url');
    $urlPrefix = '/storage/';
    $diskPath = str_replace($urlPrefix, '', $cachedUrl);
    $diskPathimages = str_replace($urlPrefix, '', $imagePath);

    $imageMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    // Don't continue when original file doesn't exist
    if (! Storage::disk('public')->exists($diskPathimages)) {
        return;
    }

    $image = Image::read(file_get_contents($hostname.$imagePath));

    if (in_array($image->exif('FILE.MimeType'), $imageMimeTypes)) {

        if ($cachedUrl === null || ! Storage::exists($diskPath)) {

            // Absolute path to full size image
            $storagePath = storage_path().'/app/public/';

            // Create the new image path
            $splitAt = strrpos($imagePath, '/storage/');
            $imageDir = substr($imagePath, 0, $splitAt);
            $filename = pathinfo($imagePath, PATHINFO_FILENAME);

            // Crop/Resize always needs height AND width
            $width = empty($width) ? 1600 : $width;
            $height = empty($height) ? 1600 : $height;

            $resizedImagePath = 'media/'.$imageDir.$filename."-$width"."x$height".'.avif';

            // No need to continue if image already exists
            if ($storage->exists($resizedImagePath)) {
                return $urlPrefix.$resizedImagePath;
            }

            // Shall we crop or resize?
            if ($crop) {
                $image->resize($width, $height);
            } else {
                $image->scale($width, $height);
            }

            // Convert image to string format and save to storage
            $imageData = $image->toAvif($quality);

            $storage->put($resizedImagePath, $imageData, 'public');

            $cachedUrl = $urlPrefix.$resizedImagePath;
        }

        return $cachedUrl;
    } else {
        return $imagePath;
    }
}
