<?php

namespace Secondnetwork\Kompass\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class KompassCommand extends Command
{
    public $signature = 'kompass:install';

    public $description = 'Setup Kompass routes, service providers and views';

    public function handle()
    {
        $this->installAssets();
        $this->publishAssets();

        $this->updateServiceProviders();

        $this->databaserun();

        Artisan::call('optimize:clear');
        Artisan::call('storage:link');
        $this->info('');
        $this->components->info('Kompass is now installed.');
        $this->info('
****************************

Logging into your Application       

email: admin@admin.com
password: password

****************************
        ');
    }

    protected function installAssets()
    {
        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@tailwindcss/forms' => '^0.5.2',
                '@tailwindcss/typography' => '^0.5.0',
                'alpinejs' => '^3.0.6',
                '@alpinejs/focus' => '^3.10.5',
                'autoprefixer' => '^10.4.7',
                'postcss' => '^8.4.14',
                'tailwindcss' => '^3.1.0',
                'vite-plugin-sass-glob-import' => '^2.0.0',
                '@nextapps-be/livewire-sortablejs' => '^0.2.0',
                '@alpinejs/collapse' => '^3.9.2',
                'postcss' => '^8.4.14',
                'sass' => '^1.34.1',
            ] + $packages;
        });

        // Tailwind Configuration...
        copy(__DIR__.'/../../stubs/livewire/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/livewire/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/livewire/vite.config.js', base_path('vite.config.js'));

        // Directories...
        (new Filesystem)->deleteDirectory(resource_path('sass'));
        (new Filesystem)->deleteDirectory('resources');
        (new Filesystem)->deleteDirectory(app_path('Actions/Fortify'));

        (new Filesystem)->ensureDirectoryExists(app_path('Actions/Fortify'));
        (new Filesystem)->ensureDirectoryExists(app_path('Models'));
        (new Filesystem)->ensureDirectoryExists(app_path('View/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/layouts'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources', 'resources');
        (new Filesystem)->copyDirectory(__DIR__.'/../database/seeders', 'database/seeders');
        // Service Providers...

        // Models...
        copy(__DIR__.'/../../stubs/app/Models/User.php', app_path('Models/User.php'));
        copy(__DIR__.'/../../stubs/routes/web.php', base_path('routes/web.php'));

        // Actions...
        copy(__DIR__.'/../../stubs/app/Actions/Fortify/UpdateUserProfileInformation.php', app_path('Actions/Fortify/UpdateUserProfileInformation.php'));

        // Layouts...
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/livewire/resources/views/layouts', resource_path('views/layouts'));

        // Single Blade Views...
        // copy(__DIR__ . '/../../stubs/livewire/resources/views/dashboard.blade.php', resource_path('views/dashboard.blade.php'));

        // if (!Str::contains(file_get_contents(base_path('routes/web.php')), "'/dashboard'")) {
        //     (new Filesystem)->append(base_path('routes/web.php'), $this->livewireRouteDefinition());
        // }
        // $this->runCommands(['yarn install', 'yarn run build']);

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Livewire scaffolding installed successfully.');
    }

    protected function publishAssets()
    {
        $this->callSilent('vendor:publish', ['--provider' => 'Laravel\Fortify\FortifyServiceProvider']);
        $this->callSilent('vendor:publish', ['--provider' => 'Secondnetwork\Kompass\KompassServiceProvider']);
        $this->callSilent('vendor:publish', ['--tag' => 'migrations', '--force' => true]);
        $this->replaceInFile("public const HOME = '/home';", "public const HOME = '/admin/dashboard';", app_path('Providers/RouteServiceProvider.php'));
    }

    public function databaserun()
    {
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
        $this->info('migrate Database and seed data ...');
    }

    public function updateServiceProviders()
    {
        $appConfig = file_get_contents(config_path('app.php'));

        if (
            ! Str::contains($appConfig, 'App\\Providers\\FortifyServiceProvider::class')
            &&
            ! Str::contains($appConfig, 'App\\Providers\\KompassServiceProvider::class')
        ) {
            File::put(config_path('app.php'), str_replace(
                "App\Providers\RouteServiceProvider::class,",
                "App\Providers\RouteServiceProvider::class,".PHP_EOL.
                "App\Providers\FortifyServiceProvider::class,".PHP_EOL.
                'App\\Providers\\KompassServiceProvider::class,',
                $appConfig
            ));
        }
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param  mixed  $packages
     * @return void
     */
    protected function requireComposerPackages($packages)
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = [$this->phpBinary(), $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    /**
     * Install the given Composer Packages as "dev" dependencies.
     *
     * @param  mixed  $packages
     * @return void
     */
    protected function requireComposerDevPackages($packages)
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = [$this->phpBinary(), $composer, 'require', '--dev'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require', '--dev'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    /**
     * Update the "package.json" file.
     *
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    /**
     * Delete the "node_modules" directory and remove the associated lock files.
     *
     * @return void
     */
    protected static function flushNodeModules()
    {
        tap(new Filesystem, function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('pnpm-lock.yaml'));
            $files->delete(base_path('yarn.lock'));
            $files->delete(base_path('package-lock.json'));
        });
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Get the path to the appropriate PHP binary.
     *
     * @return string
     */
    protected function phpBinary()
    {
        return (new PhpExecutableFinder())->find(false) ?: 'php';
    }

    /**
     * Run the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function runCommands($commands)
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> '.$e->getMessage().PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    '.$line);
        });
    }
}
