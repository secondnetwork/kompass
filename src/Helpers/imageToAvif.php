<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

function imageToAvif(string $imageUrl = '', ?int $width = null, ?int $height = null, array $config = []): ?string
{
    if (empty($imageUrl)) {
        return null;
    }

    if (! isAvifSupported()) {
        return null;
    }

    $quality = $config['quality'] ?? 50;
    $crop = $config['crop'] ?? false;
    $width = $width ?? 1600;
    $height = $height ?? 1600;

    $cacheKey = "imageAvif/{$imageUrl}/{$width}/{$height}/{$quality}/".($crop ? '1' : '0');

    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }

    $storage = Storage::disk(config('kompass.storage.disk', 'public'));
    $diskPathImages = str_replace(Storage::url(''), '', $imageUrl);
    $diskPathImages = ltrim($diskPathImages, '/');

    if (! $storage->exists($diskPathImages)) {
        return null;
    }

    $mimeType = $storage->mimeType($diskPathImages);
    if (! in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'])) {
        return null;
    }

    $imageDir = pathinfo($diskPathImages, PATHINFO_DIRNAME);
    $filename = pathinfo($diskPathImages, PATHINFO_FILENAME);
    $imageDirPrefix = ($imageDir === '.') ? '' : $imageDir.'/';
    $resizedImagePath = "{$imageDirPrefix}{$filename}-{$width}x{$height}.avif";

    if ($storage->exists($resizedImagePath)) {
        $fullUrl = $storage->url($resizedImagePath);
        Cache::put($cacheKey, $fullUrl, now()->addDay());

        return $fullUrl;
    }

    try {
        $image = \Secondnetwork\Kompass\Facades\Image::read($storage->get($diskPathImages));

        if ($crop) {
            $image->cover($width, $height);
        } else {
            $image->scaleDown($width, $height);
        }

        $encoded = $image->toAvif($quality);
        $storage->put($resizedImagePath, (string) $encoded, 'public');
        $fullUrl = $storage->url($resizedImagePath);
        Cache::put($cacheKey, $fullUrl, now()->addDay());

        return $fullUrl;

    } catch (\Error $e) {
        // Wenn imageavif() fehlt (Error, nicht Exception), markiere AVIF als nicht verfügbar
        if (str_contains($e->getMessage(), 'imageavif') || str_contains($e->getMessage(), 'AvifEncoder')) {
            Cache::put('server_avif_support', false, now()->addHours(24));
        }

        return null;
    } catch (\Exception $e) {
        return null;
    }
}

function isAvifSupported(): bool
{
    // Speichere das Ergebnis im Cache, damit wir nicht jedes Mal testen müssen
    $cacheKey = 'server_avif_support';

    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }

    $supported = false;

    // Test 1: GD mit AVIF
    if (extension_loaded('gd')) {
        // Prüfe ob GD überhaupt AVIF unterstützt (PHP 8.1+)
        $gdInfo = gd_info();
        if (isset($gdInfo['AVIF Support']) && $gdInfo['AVIF Support'] === true) {
            // GD sagt es unterstützt AVIF, aber wir müssen testen ob imageavif() wirklich funktioniert
            if (function_exists('imageavif')) {
                try {
                    $testImage = imagecreatetruecolor(10, 10);
                    if ($testImage !== false) {
                        $tempFile = sys_get_temp_dir().'/avif_test_'.uniqid().'.avif';
                        $result = @imageavif($testImage, $tempFile, 50);
                        imagedestroy($testImage);

                        if ($result === true && file_exists($tempFile) && filesize($tempFile) > 0) {
                            $supported = true;
                        }

                        // Cleanup
                        if (file_exists($tempFile)) {
                            unlink($tempFile);
                        }
                    }
                } catch (\Throwable $e) {
                    // Test fehlgeschlagen
                }
            }
        }
    }

    // Test 2: Imagick mit AVIF (wenn GD nicht funktioniert hat)
    if (! $supported && extension_loaded('imagick') && class_exists('Imagick')) {
        try {
            $imagick = new \Imagick;
            $formats = $imagick->queryFormats();
            if (in_array('AVIF', $formats, true)) {
                $supported = true;
            }
        } catch (\Exception $e) {
            // Test fehlgeschlagen
        }
    }

    // Speichere für 1 Stunde (wir wollen nicht bei jedem Request testen,
    // aber auch nicht für immer, falls sich die Konfiguration ändert)
    Cache::put($cacheKey, $supported, now()->addHour());

    return $supported;
}
