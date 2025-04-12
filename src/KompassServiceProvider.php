<?php

namespace Secondnetwork\Kompass;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\ComponentAttributeBag;
use Intervention\Image\ImageManager;
use Livewire\Livewire;
use Log;
use Secondnetwork\Kompass\Commands\CreateUserCommand;
use Secondnetwork\Kompass\Commands\KompassCommand;
use Secondnetwork\Kompass\DataWriter\FileWriter;
use Secondnetwork\Kompass\DataWriter\Repository;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\Page;
use Secondnetwork\Kompass\Models\Post;
use Secondnetwork\Kompass\Models\Setting;

class KompassServiceProvider extends ServiceProvider
{
    protected const BINDING = 'image';

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->bootLivewireComponents();
        $this->bootBladeComponents();
        $this->bootMacros();
        $this->registerBladeDirectives();

        $this->loadJSONTranslationsFrom(__DIR__.'/../resources/lang', 'kompass');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'kompass');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadHelpers();

        $this->registerMiddleware();
        $this->publishAssets();

        $this->registerGates();
        $this->registerBladeConditions();
        $this->registerMorphMaps();
    }

    private function bootBladeComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $prefix = config('kompass.setup.prefix', '');

            foreach (config('kompass.setup.components', []) as $alias => $component) {
                $componentClass = is_string($component) ? $component : $component['class'];
                $blade->component($componentClass, $alias, $prefix);
            }
        });
    }

    protected function registerBladeDirectives(): void
    {
        if (class_exists(BladeDirectives::class)) {
            foreach (get_class_methods(BladeDirectives::class) as $method) {
                Blade::directive($method, [BladeDirectives::class, $method]);
            }
        }
    }

    private function bootLivewireComponents(): void
    {
        if (! class_exists(Livewire::class)) {
            return;
        }

        foreach (config('kompass.setup.livewire', []) as $alias => $component) {
            Livewire::component($alias, $component);
        }
    }

    public function register(): void
    {
        $this->mergeConfigurations();
        $this->registerSingletons();

        if (Schema::hasTable('settings')) {
            $this->app->singleton('settings', function ($app) {
                return $app['cache']->rememberForever('settings', function () {
                    return Setting::get(['key', 'data'])->pluck('data', 'key')->toArray();
                });
            });
        }
    }

    private function mergeConfigurations(): void
    {
        $configs = [
            '/../config/kompass/setup.php' => 'kompass',
            '/../config/kompass/settings.php' => 'kompass.setup.settings',
            '/../config/kompass/appearance.php' => 'kompass.setup.appearance',
        ];

        foreach ($configs as $path => $key) {
            $fullPath = __DIR__.'/'.$path;  //Absoluter Pfad zur Konfigurationsdatei im Package

            if (File::exists($fullPath)) {
                $this->mergeConfigFrom(
                    $fullPath,
                    $key
                );
            } else {
                Log::warning('Konfigurationsdatei nicht gefunden: '.$fullPath); // Optional: Logge eine Warnung
            }
        }
    }

    private function registerSingletons(): void
    {
        $this->app->singleton('kompass', fn () => new Kompass);

        $this->app->singleton($this::BINDING, function ($app) {

            $driverConfig = config('kompass.setup.driver', 'gd'); // Default to 'gd'

            return new ImageManager(
                driver: $driverConfig,
                autoOrientation: config('kompass.setup.options.autoOrientation', true),
                decodeAnimation: config('kompass.setup.options.decodeAnimation', true),
                blendingColor: config('kompass.setup.options.blendingColor', 'ffffff')
            );
        });

        $this->app->singleton($this->repository(), function ($app, $items) {
            $writer = new FileWriter($this->getFiles(), $this->getConfigPath());

            return new Repository($writer, $items);
        });

        $this->app->extend('config', function ($config, $app) {
            return $app->make($this->repository(), $config->all());
        });
    }

    public function repository()
    {
        return Repository::class;
    }

    protected function getFiles(): Filesystem
    {
        return $this->app['files'];
    }

    protected function getConfigPath(): string
    {
        return $this->app['path.config'];
    }

    protected function loadHelpers(): void
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    private function bootMacros(): void
    {
        ComponentAttributeBag::macro('hasStartsWith', fn ($key) => (bool) $this->whereStartsWith($key)->first());
    }

    private function registerMiddleware(): void
    {
        $router = $this->app['router'];
        $middlewareMappings = [
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ];

        foreach ($middlewareMappings as $alias => $middleware) {
            $router->aliasMiddleware($alias, $middleware);
        }
    }

    private function publishAssets(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/kompass' => config_path('kompass'),
            ], 'kompass-config');

            $this->publishes([__DIR__.'/../public/assets/build' => public_path('vendor/kompass/assets')], 'kompass.assets');

            $this->publishes([
                __DIR__.'/../stubs/app/Models/User.php' => app_path('Models/User.php'),
                __DIR__.'/../stubs/app/Livewire/Actions/Logout.php' => app_path('Livewire/Actions/Logout.php'),
            ], 'kompass.stubs');

            $this->publishes([
                __DIR__.'/database/seeders/DatabaseSeeder.php' => database_path('seeders/DatabaseSeeder.php'),
                __DIR__.'/database/seeders/UserSeeder.php' => database_path('seeders/UserSeeder.php'),
                __DIR__.'/database/seeders/RoleSeeder.php' => database_path('seeders/RoleSeeder.php'),
                __DIR__.'/database/seeders/RoleUserSeeder.php' => database_path('seeders/RoleUserSeeder.php'),
                __DIR__.'/database/seeders/PageSeeder.php' => database_path('seeders/PageSeeder.php'),
            ], 'DatabaseSeeder');

            $this->publishes([
                __DIR__.'/../app/Providers' => base_path('app/Providers'),
            ], 'kompass-provider');

            $this->commands([
                KompassCommand::class,
                CreateUserCommand::class,
            ]);
        }
    }

    private function registerGates(): void
    {
        Gate::define('role', fn ($user, ...$roles) => $user->hasRole($roles));
    }

    private function registerBladeConditions(): void
    {
        Blade::if('role', fn (...$roles) => auth()->check() && auth()->user()->hasRole($roles));
    }

    private function registerMorphMaps(): void
    {
        Relation::morphMap([
            'post' => Post::class,
            'page' => Page::class,
            'datafield' => Datafield::class,
        ]);
    }
}
