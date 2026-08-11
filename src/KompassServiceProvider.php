<?php

namespace Secondnetwork\Kompass;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\ComponentAttributeBag;
use Laravel\Head\Facades\Head;
use Laravel\Head\HeadBuilder;
use Laravel\Passkeys\Contracts\PasskeyLoginResponse as PasskeyLoginResponseContract;
use Livewire\Livewire;
use Secondnetwork\Kompass\Blocks\BlockTypeRegistry;
use Secondnetwork\Kompass\Blocks\FieldTypeRegistry;
use Secondnetwork\Kompass\Commands\CreateUserCommand;
use Secondnetwork\Kompass\Commands\KompassCommand;
use Secondnetwork\Kompass\Commands\UpdateCommand;
use Secondnetwork\Kompass\DataWriter\FileWriter;
use Secondnetwork\Kompass\DataWriter\Repository;
use Secondnetwork\Kompass\Livewire\Frontend\Pageview;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\Page;
use Secondnetwork\Kompass\Models\Post;
use Secondnetwork\Kompass\Models\Setting;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

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
        $this->bootHeadDefaults();

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

    /**
     * Default <head> metadata (title fallback, canonical URL, indexable by
     * default) applied to every page unless a route or runtime layer
     * overrides it — e.g. the noindex routes registered via withHead(), or
     * the full title strings pages already build via Head::title().
     *
     * No suffix/prefix is set here: pages that call Head::title() (see
     * pages/page/page.php, pages/blog/*) already build the complete
     * "Page | Site" string themselves, and Head appends the default's
     * suffix onto every override unless exact: true is passed.
     */
    private function bootHeadDefaults(): void
    {
        Head::defaults(function (HeadBuilder $head): void {
            $head->title(config('app.name'))
                ->canonical()
                ->searchableByRobots();
        });
    }

    private function bootBladeComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade): void {
            $prefix = config('kompass.prefix', '');

            foreach (config('kompass.components', []) as $alias => $component) {
                $componentClass = is_string($component) ? $component : $component['class'];
                $blade->component($componentClass, $alias, $prefix);
            }

            $blade->component('kompass::components.field', 'field', $prefix);
        });
    }

    protected function registerBladeDirectives(): void
    {
        if (class_exists(BladeDirectives::class)) {
            foreach (get_class_methods(BladeDirectives::class) as $method) {
                Blade::directive($method, [BladeDirectives::class, $method]);
            }

            Blade::directive('getImageID', function ($expression) {
                return "<?php echo \Secondnetwork\Kompass\Helpers\ImageFactory::getImageID({$expression}); ?>";
            });

            Blade::directive('getImageUrl', function ($expression) {
                return "<?php echo \Secondnetwork\Kompass\Helpers\ImageFactory::getImageUrl({$expression}); ?>";
            });
        }
    }

    private function bootLivewireComponents(): void
    {
        if (! class_exists(Livewire::class)) {
            return;
        }

        Livewire::addLocation(
            classNamespace: 'Secondnetwork\\Kompass\\Livewire'
        );

        Livewire::component('pages::page', Pageview::class);
    }

    public function register(): void
    {
        $this->mergeConfigurations();
        $this->registerSingletons();
        $this->registerPasskeyLoginResponse();
        $this->registerCacheSerialization();

        try {
            if (Schema::hasTable('settings')) {
                $this->app->singleton('settings', function ($app) {
                    return $app['cache']->rememberForever('settings', function () {
                        return Setting::all()
                            ->groupBy('group')
                            ->map(function ($groupSettings) {
                                return $groupSettings->keyBy('key')->map(function ($setting) {
                                    return $setting->data;
                                });
                            })
                            ->toArray();
                    });
                });
            }
        } catch (\Exception $e) {
            // Database not available yet, skip settings initialization
        }
    }

    private function mergeConfigurations(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/kompass.php', 'kompass');
    }

    /**
     * Allow-list Kompass's own model classes for cache deserialization.
     *
     * Laravel disables unserializing PHP objects from the cache by default
     * (cache.serializable_classes = false) to prevent gadget-chain attacks if
     * APP_KEY leaks. Kompass caches Eloquent results (e.g. page blocks) via
     * cache()->rememberForever(), so its own trusted classes need to be
     * explicitly allowed — without this, cached results silently come back
     * as __PHP_Incomplete_Class on the next request. The allow-list itself
     * lives in config('kompass.serializable_classes') so new cached models
     * only need to be added there.
     */
    private function registerCacheSerialization(): void
    {
        $allowed = Config::get('cache.serializable_classes', false);

        if ($allowed === true) {
            return;
        }

        Config::set('cache.serializable_classes', array_unique(array_merge(
            is_array($allowed) ? $allowed : [],
            [Collection::class],
            Config::get('kompass.serializable_classes', []),
        )));
    }

    private function registerPasskeyLoginResponse(): void
    {
        $this->app->singleton(PasskeyLoginResponseContract::class, function () {
            return new class implements PasskeyLoginResponseContract
            {
                public function toResponse($request)
                {
                    $target = redirect()->intended(route('admin.dashboard'))->getTargetUrl();

                    if ($request->wantsJson()) {
                        return new JsonResponse(['redirect' => $target], 200);
                    }

                    return redirect()->intended(route('admin.dashboard'));
                }
            };
        });
    }

    private function registerSingletons(): void
    {
        $this->app->singleton('kompass', fn () => new Kompass);

        $this->app->singleton(BlockTypeRegistry::class);
        $this->app->alias(BlockTypeRegistry::class, 'kompass.blocks');
        $this->app->singleton(FieldTypeRegistry::class);
        $this->app->alias(FieldTypeRegistry::class, 'kompass.fields');

        $this->app['config']->set('images.default', config('kompass.driver', 'gd'));

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
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ];

        foreach ($middlewareMappings as $alias => $middleware) {
            $router->aliasMiddleware($alias, $middleware);
        }
    }

    private function publishAssets(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/kompass.php' => config_path('kompass.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../public/assets' => public_path('vendor/kompass/assets'),
            ], 'kompass.assets');

            $this->publishes([
                __DIR__.'/../stubs/app/Models/User.php' => app_path('Models/User.php'),
                __DIR__.'/../stubs/app/Livewire/Actions/Logout.php' => app_path('Livewire/Actions/Logout.php'),
            ], 'kompass.stubs');

            $this->publishes([
                __DIR__.'/../stubs/config/passkeys.php' => config_path('passkeys.php'),
            ], 'kompass.passkeys-config');

            $this->publishes([
                __DIR__.'/database/seeders/DatabaseSeeder.php' => database_path('seeders/DatabaseSeeder.php'),
                __DIR__.'/database/seeders/PageSeeder.php' => database_path('seeders/PageSeeder.php'),
            ], 'DatabaseSeeder');

            $this->publishes([
                __DIR__.'/../app/Providers' => base_path('app/Providers'),
            ], 'kompass-provider');

            $this->commands([
                KompassCommand::class,
                CreateUserCommand::class,
                UpdateCommand::class,
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
