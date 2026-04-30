<?php

namespace Secondnetwork\Kompass;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class KompassThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $activeTheme = $this->resolveActiveTheme();

        if ($activeTheme === null) {
            return;
        }

        $themePath = base_path('themes/' . $activeTheme . '/views');

        if (is_dir($themePath)) {
            View::prependNamespace('kompass', $themePath);
        }
    }

    private function resolveActiveTheme(): ?string
    {
        $theme = config('kompass.theme');
        if ($theme) {
            return $theme;
        }

        try {
            $settings = app('settings');
            $theme = data_get($settings, 'global.theme');
            if ($theme) {
                return $theme;
            }
        } catch (\Exception) {
            // settings table not available during initial migration
        }

        return null;
    }
}
