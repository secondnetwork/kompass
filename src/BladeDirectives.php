<?php

namespace Secondnetwork\Kompass;

class BladeDirectives
{
    private static function getManifestPath(): ?string
    {
        // 1. Prüfe im Host-Projekt (public/assets/build/)
        $hostPath = public_path('assets/build/manifest.json');
        if (file_exists($hostPath)) {
            return $hostPath;
        }

        // 2. Prüfe im Kompass-Paket selbst (public/vendor/kompass/assets/build/)
        $vendorPath = public_path('vendor/kompass/assets/build/manifest.json');
        if (file_exists($vendorPath)) {
            return $vendorPath;
        }

        // 3. Prüfe im Kompass-Paket selbst
        $packagePath = __DIR__.'/../public/assets/build/manifest.json';
        if (file_exists($packagePath)) {
            return $packagePath;
        }

        return null;
    }

    public static function kompassCss()
    {
        $url = 'http://localhost:'.env('VITE_PORT_KOMPASS', '5088').'/resources/js/main.js';
        $url200 = @get_headers($url);

        if (! $url200) {
            $manifestPath = self::getManifestPath();

            if (! $manifestPath) {
                return '<!-- Kompass CSS: Manifest not found -->';
            }

            $content = file_get_contents($manifestPath);
            $manifest = json_decode($content, true);
            $entry = 'resources/js/main.js';

            // CSS hat eigenen Entry Point: resources/css/kompass.css
            $cssEntry = 'resources/css/kompass.css';
            if (! empty($manifest[$cssEntry]['file'])) {
                $cssFile = $manifest[$cssEntry]['file'];

                // Relativer Pfad vom Manifest-Verzeichnis
                $basePath = str_contains($manifestPath, 'vendor/kompass')
                    ? asset('vendor/kompass/assets/build/'.$cssFile)
                    : asset('assets/build/'.$cssFile);

                return '<link rel="stylesheet" href="'.$basePath.'"><style>[x-cloak] { display: none !important; }</style>';
            }

            return '<!-- Kompass CSS: No CSS entry in manifest -->';
        }

        return '<!-- Kompass CSS: Dev mode (Vite server running) -->';
    }

    public static function kompassJs()
    {
        $url = 'http://localhost:'.env('VITE_PORT_KOMPASS', '5088').'/resources/js/main.js';
        $url200 = @get_headers($url);

        if (! $url200) {
            $manifestPath = self::getManifestPath();

            if (! $manifestPath) {
                return '<!-- Kompass JS: Manifest not found -->';
            }

            $content = file_get_contents($manifestPath);
            $manifest = json_decode($content, true);
            $entry = 'resources/js/main.js';

            if (! empty($manifest[$entry]['file'])) {
                $jsFile = $manifest[$entry]['file'];

                // Relativer Pfad vom Manifest-Verzeichnis
                $basePath = str_contains($manifestPath, 'vendor/kompass')
                    ? asset('vendor/kompass/assets/build/js/'.basename($jsFile))
                    : asset('assets/build/'.$jsFile);

                return '<script type="module" defer src="'.$basePath.'"></script>';
            }

            return '<!-- Kompass JS: No JS file in manifest -->';

        } else {
            return '<script type="module" defer src="'.$url.'"></script>';
        }
    }
}
