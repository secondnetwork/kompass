<?php

namespace Secondnetwork\Kompass\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Facades\Image;
use Secondnetwork\Kompass\Models\File;

class ImageFactory
{
    /**
     * Hauptfunktion für die Blade Direktive
     */
    public static function render($fileId, $cssClass = 'object-cover w-full h-full', $alt = null)
    {
        if (!$fileId) {
            // Optional: Platzhalter zurückgeben, wenn keine ID existiert
            return self::getPlaceholder($cssClass);
        }
     
        // 1. File Objekt holen (mit Cache wie in deinem Beispiel)
        $file = Cache::rememberForever('kompass_imgId_' . $fileId, function () use ($fileId) {
            return File::find($fileId);
        });

        if (!$file) {
            return self::getPlaceholder($cssClass);
        }

        // 2. Pfade zusammenbauen
        $dirpath = $file->path ? $file->path . '/' : '';
        $originalRelativePath = $dirpath . $file->slug . '.' . $file->extension;

        // Prüfen ob Original existiert
        $storage = Storage::disk(config('kompass.storage.disk', 'public'));
        if (!$storage->exists($originalRelativePath)) {
            return self::getPlaceholder($cssClass);
        }

        $imageUrl = Storage::url($originalRelativePath);
        $altText = $alt ?? ($file->alt ?? '');;
        // 3. Konvertierungen durchführen
        // Wir nutzen eine interne generische Funktion für beide Formate
        $avifUrl = self::convertFormat($originalRelativePath, 'avif');
        $webpUrl = self::convertFormat($originalRelativePath, 'webp');

        // 4. HTML Generieren
        $html = '<picture>';
        if ($avifUrl) {
            $html .= '<source type="image/avif" srcset="' . $avifUrl . '">';
        }
        if ($webpUrl) {
            $html .= '<source type="image/webp" srcset="' . $webpUrl . '">';
        }
        $html .= '<img loading="lazy" src="' . $imageUrl . '" alt="' . $altText . '" class="' . $cssClass . '">';
        $html .= '</picture>';

        return $html;
    }

    /**
     * Generische Konvertierungsfunktion (DRY Prinzip)
     */
    private static function convertFormat($relativePath, $format, $width = null, $height = null, $quality = null)
    {
        // Config Defaults
        $width = $width ?? 1600;
        $height = $height ?? 1600;
        $quality = $quality ?? ($format === 'avif' ? 50 : 80);
        
        // Cache Key
        $cacheKey = "img_{$format}_{$relativePath}_{$width}_{$height}_{$quality}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $storage = Storage::disk(config('kompass.storage.disk', 'public'));
        
        // Check Support
        if ($format === 'avif' && !function_exists('imageavif') && !self::isAvifSupported()) {
            return null;
        }

        // Pfad für neues Bild definieren
        $dir = pathinfo($relativePath, PATHINFO_DIRNAME);
        $filename = pathinfo($relativePath, PATHINFO_FILENAME);
        $newPath = "media/{$filename}-{$width}x{$height}.{$format}";

        // Wenn Datei physisch schon da ist -> URL zurückgeben
        if ($storage->exists($newPath)) {
            $url = '/storage/' . $newPath;
            Cache::put($cacheKey, $url, now()->addDay());
            return $url;
        }

        // Bild laden und konvertieren
        try {
            $originalContent = $storage->get($relativePath);
            $image = Image::read($originalContent);

            // Check MimeType (nur Bilder konvertieren)
            if (!in_array($image->exif('FILE.MimeType'), ['image/jpeg', 'image/png', 'image/webp'])) {
                return null;
            }

            // Resize/Scale
            $image->scale($width, $height);

            // Speichern
            if ($format === 'avif') {
                $imageData = $image->toAvif($quality);
            } else {
                $imageData = $image->toWebp($quality);
            }

            $storage->put($newPath, $imageData, 'public');
            
            $url = '/storage/' . $newPath;
            Cache::put($cacheKey, $url, now()->addDay());
            
            return $url;

        } catch (\Exception $e) {
            // Fehlerbehandlung (Logging etc.)
            return null;
        }
    }

    private static function isAvifSupported(): bool
    {
        if (extension_loaded('imagick') && class_exists('Imagick')) {
            $imagick = new \Imagick;
            return in_array('AVIF', $imagick->queryFormats(), true);
        }
        return false;
    }

    private static function getPlaceholder($cssClass)
    {
        // Dein SVG Placeholder als Fallback
        return '<div class="flex items-center justify-center bg-gray-200 dark:bg-gray-800 rounded-lg '.$cssClass.'">
                    <svg class="w-10 h-10 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>';
    }
}