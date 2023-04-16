<?php

namespace Secondnetwork\Kompass;

use Illuminate\Support\Facades\Blade;

class BladeDirectives
{
    public static function kompassCss()
    {
        $url = 'http://localhost:5088/resources/js/main.js';
        $url200 = @get_headers($url);
        if (! $url200) {
            $content = file_get_contents(kompass_asset('build/manifest.json'));
            $manifest = json_decode($content, true);

            $entry = 'resources/js/main.js';

            if (! empty($manifest[$entry]['css'])) {
                foreach ($manifest[$entry]['css'] as $file) {
                    $urls[] = $file;
                    // $tags .= "<link rel=\"stylesheet\" href=\"$url\">";
                }
            }

            return '<link rel="stylesheet" href="'.kompass_asset('build/'.$urls[0]).'"><style>[x-cloak] { display: none !important; }</style>';
        } else {
            return '';
        }
    }

    public static function kompassJs()
    {
        $url = 'http://localhost:'.env('VITE_PORT_KOMPASS', '5088').'/resources/js/main.js';
        $url200 = @get_headers($url);
        if (! $url200) {
            $content = file_get_contents(kompass_asset('build/manifest.json'));
            $manifest = json_decode($content, true);
            $entry = 'resources/js/main.js';

            return '<script type="module" defer src="'.kompass_asset('build/'.$manifest[$entry]['file']).'"></script>';
        } else {
            return '<script type="module" defer src="'.$url.'"></script>';
        }
    }

    /**
     * Get the honeypot field.
     */
    public static function formHoneypot(): string
    {
        return Blade::compileString("
            @include(\$this->component->getView('honeypot'))
        ");
    }
}
