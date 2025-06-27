<?php

namespace Secondnetwork\Kompass;

class BladeDirectives
{
    public static function kompassCss()
    {
        $url = 'http://localhost:5088:'.env('VITE_PORT_KOMPASS', '5088').'/resources/js/main.js';
        $url200 = @get_headers($url);

        if (! $url200) {
            if (file_exists(public_path('vendor/kompass/assets/manifest.json'))) {
                $content = file_get_contents(public_path('vendor/kompass/assets/manifest.json'));
            } else {
                $content = '';
            }

            $manifest = json_decode($content, true);

            $entry = 'resources/js/main.js';

            if (! empty($manifest[$entry]['css'])) {
                foreach ($manifest[$entry]['css'] as $file) {
                    $urls[] = $file;
                    // $tags .= "<link rel=\"stylesheet\" href=\"$url\">";
                }

                return '<link rel="stylesheet" href="'.asset('vendor/kompass/assets/'.$urls[0]).'"><style>[x-cloak] { display: none !important; }</style>';
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
            if (file_exists(public_path('vendor/kompass/assets/manifest.json'))) {
                $content = file_get_contents(public_path('vendor/kompass/assets/manifest.json'));
            } else {
                $content = '';
            }
            $manifest = json_decode($content, true);
            $entry = 'resources/js/main.js';
            if ($content) {
                return '<script type="module" defer src="'.asset('vendor/kompass/assets/'.$manifest[$entry]['file']).'"></script>';
            }

        } else {
            return '<script type="module" defer src="'.$url.'"></script>';
        }
    }
}
