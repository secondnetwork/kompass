<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Facades\Image;

function imageToWebp(string $imageUrl = '', ?int $width = null, ?int $height = null, array $config = []): ?string
{
    if (empty($imageUrl)) return null;

    $quality = $config['quality'] ?? 80;
    $crop = $config['crop'] ?? false;
    $width = $width ?? 1600;
    $height = $height ?? 1600;

    $cacheKey = "imageWebp/v2/" . md5("$imageUrl/$width/$height/$quality/" . ($crop ? '1' : '0'));
    
    return Cache::remember($cacheKey, now()->addMonth(), function () use ($imageUrl, $width, $height, $quality, $crop) {
        $storage = Storage::disk(config('kompass.storage.disk', 'public'));
        $diskPathImages = ltrim(str_replace(Storage::url(''), '', $imageUrl), '/');

        if (!$storage->exists($diskPathImages)) return $imageUrl;

        $mimeType = $storage->mimeType($diskPathImages);
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'])) return $imageUrl;

        $imageDir = pathinfo($diskPathImages, PATHINFO_DIRNAME);
        $filename = pathinfo($diskPathImages, PATHINFO_FILENAME);
        $imageDirPrefix = ($imageDir === '.') ? '' : $imageDir . '/';
        $resizedImagePath = "{$imageDirPrefix}{$filename}-{$width}x{$height}.webp";

        if ($storage->exists($resizedImagePath)) return $storage->url($resizedImagePath);

        try {
            $image = Image::read($storage->get($diskPathImages));
            if ($crop) {
                $image->cover($width, $height);
            } else {
                $image->scaleDown($width, $height);
            }
            $encoded = $image->toWebp($quality);
            $storage->put($resizedImagePath, (string) $encoded, 'public');
            return $storage->url($resizedImagePath);
        } catch (\Exception $e) {
            return $imageUrl;
        }
    });
}
