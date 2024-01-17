<?php

namespace Secondnetwork\Kompass;

use Illuminate\Database\Eloquent\Relations\Relation;
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
use Livewire\Livewire;
use Secondnetwork\Kompass\Commands\KompassCommand;
use Secondnetwork\Kompass\Http\Middleware\RoleMiddleware;
use Secondnetwork\Kompass\Models\Page;
use Secondnetwork\Kompass\Models\Post;
use Secondnetwork\Kompass\Models\Setting;

class KompassServiceProvider extends ServiceProvider
{
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

        $kernel = $this->app->make(Kernel::class);

        $kernel->appendToMiddlewarePriority(RoleMiddleware::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/kompass.php' => config_path('kompass.php'),
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
                $prefix = config('kompass.prefix', '');
                $assets = config('kompass.assets', []);

                foreach (config('kompass.components', []) as $alias => $component) {
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

        $prefix = config('kompass.prefix', '');
        $assets = config('kompass.assets', []);

        /** @var LivewireComponent $component */
        foreach (config('kompass.livewire', []) as $alias => $component) {
            // $alias = $prefix ? "$prefix-$alias" : $alias;

            Livewire::component($alias, $component);
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $router = $this->app->make(Router::class);

        $router->aliasMiddleware(
            'role',
            RoleMiddleware::class
        );
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/fortify.php', 'fortify');
        $this->mergeConfigFrom(__DIR__.'/../config/kompass.php', 'kompass');

        // Register the main class to use with the facade
        $this->app->singleton('kompass', function () {
            return new Kompass;
        });

        $this->app->singleton('kompassVite', function () {
            return new KompassVite;
        });

        $this->app->singleton('settings', function ($app) {
            return $app['cache']->remember('settings', 10, function () {
                return Setting::pluck('data', 'key', 'group')->toArray();
            });
        });
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
