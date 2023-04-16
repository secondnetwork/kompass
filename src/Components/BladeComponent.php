<?php

declare(strict_types=1);

namespace Secondnetwork\Kompass\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component as IlluminateComponent;

abstract class BladeComponent extends IlluminateComponent
{
    protected static array $assets = [];

    public static function assets(): array
    {
        return static::$assets;
    }

    public function render()
    {
        return view(static::viewName());
    }

    public static function viewName(): string
    {
        return 'kompass::components.'.static::getName();
    }

    /*
     * This method is pretty much a direct copy of how livewire/livewire
     * determines which view to render in Component.php.
     */
    public static function getName(): string
    {
        $namespace = collect(explode('.', Str::replace(['/', '\\'], '.', 'Secondnetwork\\Kompass\\Components')))
            ->map([Str::class, 'kebab'])
            ->implode('.');

        $fullName = collect(explode('.', str_replace(['/', '\\'], '.', static::class)))
            ->map([Str::class, 'kebab'])
            ->implode('.');

        if (Str::startsWith($fullName, $namespace)) {
            return (string) Str::of($fullName)->substr(strlen($namespace) + 1);
        }

        return $fullName;
    }
}
