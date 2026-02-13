<?php

namespace Secondnetwork\Kompass;

class BladeDirectives
{
    public static function kompassCss()
    {
        $url = 'http://localhost:'.env('VITE_PORT_KOMPASS', '5088').'/resources/js/main.js';
        $url200 = @get_headers($url);

        if (! $url200) {
            // Neuer Build-Pfad: public/assets/build/
            $manifestPath = public_path('assets/build/manifest.json');
            
            if (file_exists($manifestPath)) {
                $content = file_get_contents($manifestPath);
            } else {
                $content = '';
            }

            $manifest = json_decode($content, true);

            $entry = 'resources/js/main.js';

            if (! empty($manifest[$entry]['css'])) {
                foreach ($manifest[$entry]['css'] as $file) {
                    $urls[] = $file;
                }

                return '<link rel="stylesheet" href="'.asset('assets/build/'.$urls[0]).'"><style>[x-cloak] { display: none !important; }</style>';
            }
        } else {
            return '';
        }
    }

    public static function kompassJs()
    {
        $url = 'http://localhost:'.env('VITE_PORT_KOMPASS', '5088').'/resources/js/main.js';
        $url200 = @get_headers($url);

        if (! $url200) {
            // Neuer Build-Pfad: public/assets/build/
            $manifestPath = public_path('assets/build/manifest.json');
            
            if (file_exists($manifestPath)) {
                $content = file_get_contents($manifestPath);
            } else {
                $content = '';
            }
            
            $manifest = json_decode($content, true);
            $entry = 'resources/js/main.js';
            
            if ($content && !empty($manifest[$entry]['file'])) {
                return '<script type="module" defer src="'.asset('assets/build/'.$manifest[$entry]['file']).'"></script>';
            }

        } else {
            return '<script type="module" defer src="'.$url.'"></script>';
        }
    }
}
