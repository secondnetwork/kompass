<?php

namespace Secondnetwork\Kompass;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\ComponentAttributeBag;
use Intervention\Image\ImageManager;
use Livewire\Livewire;
use Secondnetwork\Kompass\Commands\CreateUserCommand;

use Secondnetwork\Kompass\Commands\KompassCommand;
use Secondnetwork\Kompass\DataWriter\FileWriter;
use Secondnetwork\Kompass\Http\Middleware\Language;
use Secondnetwork\Kompass\Http\Middleware\RoleMiddleware;
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
        $this->registerBladeIfDirectives();
        $this->loadJSONTranslationsFrom(__DIR__.'/../resources/lang', 'kompass');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'kompass');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadHelpers();

        // $kernel = $this->app->make(Kernel::class);

        // Register middleware alias
        $this->app['router']->aliasMiddleware('role', \Spatie\Permission\Middleware\RoleMiddleware::class);
        $this->app['router']->aliasMiddleware('permission', \Spatie\Permission\Middleware\PermissionMiddleware::class);
        $this->app['router']->aliasMiddleware('role_or_permission', \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/kompass/setup.php' => config_path('kompass.setup.php'),
                __DIR__.'/../config/kompass/settings.php' => config_path('kompass.setup.settings'),
                __DIR__.'/../config/kompass/appearance.php' => config_path('kompass.setup.appearance'),

            ], 'config');

            $this->publishes([
                __DIR__.'/Models/User.php' => app_path('Models/User.php'),
            ], 'models');

            $this->publishes([
                __DIR__.'/../public/assets/build' => public_path('vendor/kompass/assets'),
            ], 'assets');

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
        Gate::define('role', function ($user, ...$roles) {
            return $user->hasRole($roles);
        });

        Blade::if('role', function (...$roles) {
            if (auth()->check()) {
                return auth()->user()->hasRole($roles);
            }

            return false;
        });

        Relation::morphMap(
            [
                'post' => Post::class,
                'page' => Page::class,
            ]
        );
        // View::composer('*', function ($view) {
        //     $view_name = str_replace('.', ' ', $view->getName());
        //     View::share('view_name', $view_name);
        // });

        // if (Schema::hasTable('settings')) {
        //     $settings = Cache::rememberForever('settings', function () {
        //         return Setting::all();
        //     });

        //     foreach ($settings as $setting) {
        //         Config::set('settings.'.$setting->key, $setting);
        //     }
        // }

    }

    private function bootBladeComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
                $prefix = config('kompass.setup.prefix', '');
                $assets = config('kompass.setup.assets', []);

                foreach (config('kompass.setup.components', []) as $alias => $component) {
                    $componentClass = is_string($component) ? $component : $component['class'];

                    $blade->component($componentClass, $alias, $prefix);

                    // $this->registerAssets($componentClass, $assets);
                }
            });
        });
    }

    protected function registerBladeDirectives(): self
    {
        foreach (get_class_methods(BladeDirectives::class) as $method) {
            Blade::directive($method, [BladeDirectives::class, $method]);
        }

        return $this;
    }

    protected function registerBladeIfDirectives(): self
    {
        foreach (get_class_methods(BladeIfDirectives::class) as $method) {
            Blade::if($method, [BladeIfDirectives::class, $method]);
        }

        return $this;
    }

    private function bootLivewireComponents(): void
    {
        // Skip if Livewire isn't installed.
        if (! class_exists(Livewire::class)) {
            return;
        }

        // $prefix = config('kompass.setup.prefix', '');
        // $assets = config('kompass.setup.assets', []);

        /** @var LivewireComponent $component */
        foreach (config('kompass.setup.livewire', []) as $alias => $component) {
            // $alias = $prefix ? "$prefix-$alias" : $alias;

            Livewire::component($alias, $component);
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // $router = $this->app->make(Router::class);

        // $router->aliasMiddleware(
        //     'role',
        //     RoleMiddleware::class,
        //     Language::class

        // );
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/fortify.php', 'fortify');
        $this->mergeConfigFrom(__DIR__.'/../config/kompass/setup.php', 'kompass');
        $this->mergeConfigFrom(__DIR__.'/../config/kompass/settings.php', 'kompass.setup.settings');
        $this->mergeConfigFrom(__DIR__.'/../config/kompass/appearance.php', 'kompass.setup.appearance');

        // Register the main class to use with the facade
        $this->app->singleton('kompass', function () {
            return new Kompass;
        });

        if (Schema::hasTable('settings')) {
            $this->app->singleton('settings', function ($app) {
                return $app['cache']->remember('settings', 10, function () {
                    return Setting::pluck('data', 'key', 'group')->toArray();
                });
            });
        }

        $this->mergeConfigFrom(
            __DIR__.'/../config/kompass/setup.php',
            $this::BINDING
        );

        $this->app->singleton($this::BINDING, function ($app) {
            return new ImageManager(
                driver: config('kompass.setup.driver'),
                autoOrientation: config('kompass.setup.options.autoOrientation', true),
                decodeAnimation: config('kompass.setup.options.decodeAnimation', true),
                blendingColor: config('kompass.setup.options.blendingColor', 'ffffff')
            );
        });

        // Bind it only once so we can reuse in IoC
        $this->app->singleton($this->repository(), function ($app, $items) {
            $writer = new FileWriter($this->getFiles(), $this->getConfigPath());

            return new Repository($writer, $items);
        });

        $this->app->extend('config', function ($config, $app) {
            // Capture the loaded configuration items
            $config_items = $config->all();

            return $app->make($this->repository(), $config_items);
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

    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    private function bootMacros(): void
    {
        ComponentAttributeBag::macro('hasStartsWith', function ($key) {
            return (bool) $this->whereStartsWith($key)->first();
        });
    }

    /**
     * Register the given component.
     *
     * @return void
     */
    protected function registerComponent(string $component)
    {
        Blade::component('kompass::components.'.$component, 'kp-'.$component);
    }
}
