<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Facades\Image;

function imageToAvif(string $imageUrl = '', ?int $width = null, ?int $height = null, array $config = []): ?string
{
    // 1. Validierung & Support Check
    if (empty($imageUrl)) return null;

    // Prüfen, ob der Server überhaupt AVIF kann (GD oder Imagick)
    if (!function_exists('imageavif') && !isAvifSupported()) {
        return null;
    }

    $quality = $config['quality'] ?? 50;
    $crop = $config['crop'] ?? false;
    
    // Defaults
    $width = $width ?? 1600;
    $height = $height ?? 1600;

    // Cache Key
    $cacheKey = "imageAvif/{$imageUrl}/{$width}/{$height}/{$quality}/" . ($crop ? '1' : '0');

    // 2. Cache Check (URL)
    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }

    $storage = Storage::disk(config('kompass.storage.disk', 'public'));

    // 3. Pfad bereinigen
    // Wir entfernen '/storage/' (oder was auch immer die URL ist) vom Pfad
    $diskPathImages = str_replace(Storage::url(''), '', $imageUrl);
    $diskPathImages = ltrim($diskPathImages, '/');

    if (! $storage->exists($diskPathImages)) {
        return null;
    }

    // 4. MimeType Check (Ressourcensparend über Storage, nicht Image-Lib)
    $mimeType = $storage->mimeType($diskPathImages);
    if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'])) {
        // WICHTIG: Return null statt Original, damit <source> Tag nicht kaputt geht
        return null; 
    }

    // 5. Zielpfad definieren
    $imageDir = pathinfo($diskPathImages, PATHINFO_DIRNAME);
    $filename = pathinfo($diskPathImages, PATHINFO_FILENAME);
    $imageDirPrefix = ($imageDir === '.') ? '' : $imageDir . '/';

    $resizedImagePath = "{$imageDirPrefix}{$filename}-{$width}x{$height}.avif";
    
    // 6. Physischer Check (bevor wir das Bild laden!)
    if ($storage->exists($resizedImagePath)) {
        $fullUrl = $storage->url($resizedImagePath);
        Cache::put($cacheKey, $fullUrl, now()->addDay());
        return $fullUrl;
    }

    // 7. Verarbeitung (Nur wenn Bild noch nicht existiert)
    try {
        $image = Image::read($storage->get($diskPathImages));

        if ($crop) {
            // 'cover' ist Smart-Crop in V3
            $image->cover($width, $height);
        } else {
            // 'scaleDown' behält Aspect Ratio, skaliert aber nicht hoch (pixelig)
            $image->scaleDown($width, $height);
        }

        // Konvertieren
        $encoded = $image->toAvif($quality);

        // Speichern
        $storage->put($resizedImagePath, (string) $encoded, 'public');

        $fullUrl = $storage->url($resizedImagePath);
        Cache::put($cacheKey, $fullUrl, now()->addDay());

        return $fullUrl;

    } catch (\Exception $e) {
        // AVIF Konvertierung ist rechenintensiv und kann fehlschlagen (Memory Limit, Timeout).
        // Return null -> Browser nutzt WebP oder JPG Fallback.
        return null;
    }
}

/**
 * Helper: Prüft ob Imagick installiert ist und AVIF unterstützt
 */
function isAvifSupported(): bool
{
    // Erst checken ob Extension geladen ist, um Fatal Errors zu vermeiden
    if (extension_loaded('imagick') && class_exists('Imagick')) {
        try {
            $imagick = new \Imagick;
            $formats = $imagick->queryFormats();
            return in_array('AVIF', $formats, true);
        } catch (\Exception $e) {
            return false;
        }
    }

    return false;
}