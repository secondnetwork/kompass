<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Facades\Image;

function imageToWebp(string $imageUrl = '', ?int $width = null, ?int $height = null, array $config = []): ?string
{
    // Schnell-Check: Wenn leer, abbrechen
    if (empty($imageUrl)) return null;

    $quality = $config['quality'] ?? 80;
    $crop = $config['crop'] ?? false;
    
    // Dimensionen definieren (Fallback)
    $width = $width ?? 1600;
    $height = $height ?? 1600;

    // Cache Key
    $cacheKey = "imageWebp/{$imageUrl}/{$width}/{$height}/{$quality}/" . ($crop ? '1' : '0');
    
    // 1. Cache Check (URL zurückgeben, wenn bekannt)
    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }

    $storage = Storage::disk(config('kompass.storage.disk', 'public'));
    
    // 2. Pfad bereinigen: URL zu relativem Storage-Pfad machen
    // Entfernt '/storage/' am Anfang, falls vorhanden
    $diskPathImages = str_replace(Storage::url(''), '', $imageUrl);
    $diskPathImages = ltrim($diskPathImages, '/');

    if (! $storage->exists($diskPathImages)) {
        return null;
    }

    // 3. MimeType Check VOR dem Laden (schneller & sicherer)
    $mimeType = $storage->mimeType($diskPathImages);
    if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'])) {
        return $imageUrl; // Original zurückgeben, wenn kein Bild
    }

    // Pfade für neues Bild bestimmen
    // KORREKTUR: Wir holen den Ordner aus dem $diskPathImages, damit die Struktur erhalten bleibt
    $imageDir = pathinfo($diskPathImages, PATHINFO_DIRNAME);
    $filename = pathinfo($diskPathImages, PATHINFO_FILENAME);
    
    // Wenn Bild im Root liegt, ist Dir '.', das fixen wir
    $imageDirPrefix = ($imageDir === '.') ? '' : $imageDir . '/';

    // KORREKTUR: Ordnerstruktur einbeziehen
    $resizedImagePath = "{$imageDirPrefix}{$filename}-{$width}x{$height}.webp";
    $urlPrefix = Storage::url(''); // Dynamisch ermitteln (z.B. /storage/)

    // 4. Prüfen ob konvertierte Datei physisch existiert
    if ($storage->exists($resizedImagePath)) {
        $fullUrl = $storage->url($resizedImagePath);
        Cache::put($cacheKey, $fullUrl, now()->addDay());
        return $fullUrl;
    }

    // 5. Bild verarbeiten
    try {
        $image = Image::read($storage->get($diskPathImages));

        if ($crop) {
            // KORREKTUR: 'cover' schneidet zu (Smart Crop), 'resize' verzerrt in V3!
            $image->cover($width, $height);
        } else {
            // 'scaleDown' vergrößert kleine Bilder nicht (sieht besser aus als scale)
            $image->scaleDown($width, $height);
        }

        // Konvertieren
        $encoded = $image->toWebp($quality);

        // Speichern (String casten ist in V3 sicherer)
        $storage->put($resizedImagePath, (string) $encoded, 'public');

        $fullUrl = $storage->url($resizedImagePath);
        Cache::put($cacheKey, $fullUrl, now()->addDay());

        return $fullUrl;

    } catch (\Exception $e) {
        // Fallback im Fehlerfall: Original URL zurückgeben
        return $imageUrl;
    }
}