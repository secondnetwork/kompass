<?php

namespace Secondnetwork\Kompass\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Secondnetwork\Kompass\Models\File;

class ImageFactory
{
    // Instanz-Variablen für das Fluent Interface
    protected $idOrUrl;
    protected $type; // 'id' oder 'url'
    protected $sizeKey;
    protected $cssClass = '';
    protected $altText = null;
    protected $attributes = [];

    // Statische Manager Instanz
    protected static $manager;

    /**
     * Konstruktor (wird von den statischen Methoden aufgerufen)
     */
    public function __construct($idOrUrl, $type, $sizeKey = null)
    {
        $this->idOrUrl = $idOrUrl;
        $this->type = $type;
        $this->sizeKey = $sizeKey;
    }

    /**
     * Entry Point 1: ID
     * Gibt eine NEUE Instanz der Factory zurück
     */
    public static function getImageID($id, $sizeKey = null)
    {
        return new self($id, 'id', $sizeKey);
    }

    /**
     * Entry Point 2: URL
     * Gibt eine NEUE Instanz der Factory zurück
     */
    public static function getImageUrl($url, $sizeKey = null)
    {
        return new self($url, 'url', $sizeKey);
    }

    /**
     * Fluent Setter: CSS Klasse
     */
    public function class(string $class): self
    {
        $this->cssClass = $class;
        return $this;
    }

    /**
     * Fluent Setter: Alt Text
     */
    public function alt(string $alt): self
    {
        $this->altText = $alt;
        return $this;
    }

    /**
     * Fluent Setter: Attribute
     */
    public function attr(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Für x-components: Attribute Array mergen
     */
    public function mergeAttributes(array $attributes): self
    {
        if (isset($attributes['class'])) {
            $this->cssClass = trim($this->cssClass . ' ' . $attributes['class']);
            unset($attributes['class']);
        }
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * Magic Method: Wird aufgerufen bei {{ }} oder echo
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Die Rendering Logik
     */
    public function render(): string
    {
        // 1. Validierung: Wenn leer -> Placeholder
        if (empty($this->idOrUrl)) {
            return self::getPlaceholder($this->cssClass);
        }

        $relativePath = '';
        $finalAlt = $this->altText;

        // 2. Pfad ermitteln (ID oder URL)
        if ($this->type === 'id') {
            $file = Cache::rememberForever('kompass_file_' . $this->idOrUrl, function () {
                return File::find($this->idOrUrl);
            });

            if (!$file) {
                return self::getPlaceholder($this->cssClass);
            }

            $relativePath = $file->path ? $file->path . '/' . $file->slug . '.' . $file->extension : $file->slug . '.' . $file->extension;
            if ($finalAlt === null) {
                $finalAlt = $file->alt ?? $file->title ?? '';
            }
        } else {
            $path = str_replace(Storage::url(''), '', $this->idOrUrl);
            $relativePath = ltrim($path, '/');
            if ($finalAlt === null) $finalAlt = '';
        }

        // 3. HTML generieren (interne Methode aufrufen)
        return self::generateHtml(
            $relativePath, 
            $this->sizeKey, 
            $this->cssClass, 
            $finalAlt,
            $this->attributes
        );
    }

    // -------------------------------------------------------------------------
    // Ab hier: Interne Helfer-Methoden (Processing, HTML Bauen, Placeholder)
    // -------------------------------------------------------------------------

    protected static function getManager()
    {
        if (!self::$manager) {
            $driver = config('kompass.driver', \Intervention\Image\Drivers\Gd\Driver::class);
            self::$manager = new ImageManager($driver);
        }
        return self::$manager;
    }

    protected static function generateHtml($relativePath, $sizeKey, $cssClass, $alt, $attributes = [])
    {
        $storage = Storage::disk(config('kompass.storage.disk', 'public'));

        if (!$storage->exists($relativePath)) {
            return self::getPlaceholder($cssClass);
        }

        $preset = config("kompass.sizes.{$sizeKey}");
        $fallback = config('kompass.fallback');
        $config = $preset ?? $fallback;

        $avifUrl = self::processImage($relativePath, 'avif', $config);
        $webpUrl = self::processImage($relativePath, 'webp', $config);
        $originalUrl = $storage->url($relativePath);

        $placeholderStyle = '';
        if (config('kompass.generate_blur_placeholder', true)) {
            $base64 = self::getTinyPlaceholder($relativePath, $storage);
            if ($base64) {
                $placeholderStyle = "style=\"background-image: url('{$base64}'); background-size: cover; background-position: center;\"";
            }
        }

        $attrString = '';
        foreach ($attributes as $key => $val) {
            $attrString .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
        }

        $html = '<picture>';
        if ($avifUrl) $html .= '<source type="image/avif" srcset="' . $avifUrl . '">';
        if ($webpUrl) $html .= '<source type="image/webp" srcset="' . $webpUrl . '">';
        $html .= '<img loading="lazy" src="' . $originalUrl . '" alt="' . htmlspecialchars($alt) . '" class="' . $cssClass . '" ' . $placeholderStyle . $attrString . '>';
        $html .= '</picture>';

        return $html;
    }

    public static function getPlaceholder($cssClass = '')
    {
        $defaultClasses = 'flex items-center justify-center bg-gray-200 text-gray-400 aspect-video rounded-lg';
        $svg = '<svg class="w-10 h-10 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>';
        return '<div class="' . $defaultClasses . ' ' . $cssClass . '">' . $svg . '</div>';
    }

    protected static function getTinyPlaceholder($path, $storage)
    {
        $cacheKey = 'img_blur_' . $path;
        return Cache::rememberForever($cacheKey, function () use ($path, $storage) {
            try {
                $content = $storage->get($path);
                $manager = self::getManager();
                $image = $manager->read($content);
                $image->scale(width: 20);
                $image->blur(5);
                return $image->toJpeg(quality: 50)->toDataUri();
            } catch (\Exception $e) { return null; }
        });
    }

    protected static function processImage($sourcePath, $format, $config)
    {
        $width = $config['width'] ?? null;
        $height = $config['height'] ?? null;
        $method = $config['method'] ?? 'scaleDown';
        $quality = $config['quality'] ?? config("kompass.quality.{$format}", 75);

        $dimString = ($width ?? 'auto') . 'x' . ($height ?? 'auto');
        $cacheKey = "img_{$sourcePath}_{$format}_{$dimString}_{$method}_{$quality}";
        
        if (Cache::has($cacheKey)) return Cache::get($cacheKey);

        $storage = Storage::disk(config('kompass.storage.disk', 'public'));
        $dir = pathinfo($sourcePath, PATHINFO_DIRNAME);
        $filename = pathinfo($sourcePath, PATHINFO_FILENAME);
        $newFilename = "{$filename}-{$dimString}.{$format}";
        $newPath = $dir === '.' ? "media/{$newFilename}" : "media/{$dir}/{$newFilename}";

        if ($storage->exists($newPath)) {
            $url = $storage->url($newPath);
            Cache::put($cacheKey, $url, now()->addDay());
            return $url;
        }

        try {
            $content = $storage->get($sourcePath);
            $manager = self::getManager();
            $image = $manager->read($content);

            if ($width || $height) {
                if ($method === 'cover') $image->cover($width, $height);
                elseif ($method === 'resize') $image->resize($width, $height);
                else $image->scaleDown($width, $height);
            }

            if ($format === 'avif') $encoded = $image->toAvif($quality);
            elseif ($format === 'webp') $encoded = $image->toWebp($quality);
            else return null;

            $storage->put($newPath, (string) $encoded, 'public');
            $url = $storage->url($newPath);
            Cache::put($cacheKey, $url, now()->addDay());
            return $url;
        } catch (\Exception $e) { return null; }
    }
}