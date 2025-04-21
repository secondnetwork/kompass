<?php

namespace Secondnetwork\Kompass\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

class KompassCommand extends Command implements PromptsForMissingInput
{
    public $signature = 'kompass:install
    {--name= : The name of the user}
    {--email= : A valid and unique email address}
    {--password= : The password for the user (min. 8 characters)}';

    public $description = 'Install the Kompass components and resources';

    /**
     * @var array{'name': string | null, 'email': string | null, 'password': string | null}
     */
    protected array $options;

    /**
     * @return array{'name': string, 'email': string, 'password': string}
     */
    protected function getUserData(): array
    {
        return [
            'name' => $this->options['name'] ?? text(
                label: 'Name',
                required: true,
            ),

            'email' => $this->options['email'] ?? text(
                label: __('E-Mail Address'),
                required: true,
                validate: fn (string $email): ?string => match (true) {
                    ! filter_var($email, FILTER_VALIDATE_EMAIL) => 'The email address must be valid.',
                    User::where('email', $email)->exists() => 'A user with this email address already exists',
                    default => null,
                },
            ),

            'password' => Hash::make($this->options['password'] ?? password(
                label: __('Password'),
                required: true,
            )),
        ];
    }

    protected function createUser(): Authenticatable
    {
        $now = Carbon::now()->toDateTimeString();
        $maildata = Arr::prepend($this->getUserData(), $now, 'email_verified_at');
        $user = User::create($maildata);
        // $user->roles()->sync(1);
        $user->syncRoles('admin');

        return $user;
    }

    protected function sendSuccessMessage(): void
    {
        $loginUrl = env('APP_URL').'/login';
        note('Kompass is now installed.');
        info("Logging at {$loginUrl} with you credentials.");
    }

    public function handle(): int
    {
        $this->options = $this->options();

        info('Welcome to the installation of Kompass A Laravel CMS.');

        $this->updateServiceProviders();

        $publishAssets = select(
            label: 'Install Frontend Themen?',
            options: [
                true => 'Yes',
                false => 'no',
            ],
        );

        if ($publishAssets) {
            $packagemanager = select(
                label: 'Which package manager do you have on the system?',
                options: [
                    'bun' => 'Bun',
                    'yarn' => 'Yarn',
                    'npm' => 'Npm',
                    'pnpm' => 'pnpm',
                ]
            );
            $this->installAssets($packagemanager);
            $this->publishAssets();

            $this->call('volt:install');
        }

        warning('Warning: Have you made a backup of you database?');
        $database = select(
            label: 'Drop all tables from the database? For a new installation of Kompass!',
            options: [
                true => 'Yes',
                false => 'no',
            ]
        );

        if ($database) {
            $this->databaserun();
        }

        $addNewUser = select(
            label: 'Create new Admin User?',
            options: [
                true => 'Yes',
                false => 'no',
            ]
        );

        if ($addNewUser) {
            $this->createUser();
        }

        $this->call('optimize:clear');

        $linkPath = public_path('storage');
        if (! file_exists($linkPath)) {
            $this->call('storage:link');
        }

        $this->sendSuccessMessage();

        return static::SUCCESS;
    }

    protected function installAssets($packagemanager)
    {
        // Tailwind Configuration...
        copy(__DIR__.'/../../stubs/livewire/postcss.config.cjs', base_path('postcss.config.cjs'));
        copy(__DIR__.'/../../stubs/livewire/vite.config.js', base_path('vite.config.js'));

        // Directories...
        (new Filesystem)->deleteDirectory('resources');

        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        copy(__DIR__.'/../../stubs/app/Http/Controllers/Auth/VerifyEmailController.php', base_path('app/Http/Controllers/Auth/VerifyEmailController.php'));
        (new Filesystem)->ensureDirectoryExists(app_path('Models'));
        (new Filesystem)->ensureDirectoryExists(app_path('View/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/layouts'));

        // Layouts...
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources', 'resources');

        $this->flushNodeModules();

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@lehoczky/postcss-fluid' => '^1.0.3',
                '@tailwindcss/forms' => '^0.5.10',
                '@tailwindcss/typography' => '^0.5.16',
                'autoprefixer' => '^10.4.20',
                'axios' => '^1.7.9',
                'concurrently' => '^9.1.2',
                'laravel-vite-plugin' => '^1.2.0',
                'postcss' => '^8.5.1',
                'vite' => '^6.0.11',
                '@tailwindcss/postcss' => '^4.0.0',
                '@tailwindcss/vite' => '^4.0.0',
                'tailwindcss' => '^4.0.0',
            ] + $packages;
        });
        switch ($packagemanager) {
            case 'bun':
                $this->runCommands(['bun install', 'bun run build']);
                break;
            case 'yarn':
                $this->runCommands(['yarn install', 'yarn run build']);
                break;
            case 'npm':
                $this->runCommands(['npm install', 'npm run build']);
                break;
            case 'pnpm':
                $this->runCommands(['pnpm install', 'pnpm run build']);
                break;
            default:
                // code...
                break;
        }

        info('Frontend Theme Install');
    }

    // Service Providers...
    protected function publishAssets()
    {

        (new Filesystem)->deleteDirectory('public/vendor/kompass');
        copy(__DIR__.'/../../stubs/app/Models/User.php', app_path('Models/User.php'));
        copy(__DIR__.'/../../stubs/routes/auth.php', base_path('routes/auth.php'));
        copy(__DIR__.'/../../stubs/routes/web.php', base_path('routes/web.php'));

        $this->callSilent('vendor:publish', ['--provider' => 'Secondnetwork\Kompass\KompassServiceProvider']);
        $this->callSilent('vendor:publish', ['--tag' => 'migrations', '--force' => true]);
    }

    public function databaserun()
    {
        // Database seeders...
        (new Filesystem)->copyDirectory(__DIR__.'/../database/seeders', 'database/seeders');
        $this->call('migrate');
        $this->call('db:seed');
        $this->info('migrate Database and seed data ...');
    }

    public function updateServiceProviders()
    {

        if (! method_exists(ServiceProvider::class, 'addProviderToBootstrapFile')) {
            return;
        }

        ServiceProvider::addProviderToBootstrapFile(\App\Providers\KompassServiceProvider::class);
        // ServiceProvider::addProviderToBootstrapFile(Spatie\Permission\PermissionServiceProvider::class);
        // $appConfig = file_get_contents(config_path('app.php'));

        // if (
        //     ! Str::contains($appConfig, 'App\\Providers\\FortifyServiceProvider::class')
        //     &&
        //     ! Str::contains($appConfig, 'App\\Providers\\KompassServiceProvider::class')
        // ) {

        //     $this->callSilent('vendor:publish', [
        //         '--provider' => FortifyServiceProvider::class,
        //         '--provider' => KompassServiceProvider::class,
        //     ]);

        //     $this->registerFortifyServiceProvider();
        //     $this->info('Fortify scaffolding installed successfully');
        // File::put(config_path('app.php'), str_replace(
        //     "App\Providers\RouteServiceProvider::class,",
        //     "App\Providers\RouteServiceProvider::class,".PHP_EOL.
        //         "App\Providers\FortifyServiceProvider::class,".PHP_EOL.
        //         'App\\Providers\\KompassServiceProvider::class,',
        //     $appConfig
        // ));
        // }
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
            ->run(function ($type, $output): void {
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
            ->run(function ($type, $output): void {
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
        tap(new Filesystem, function ($files): void {
            $files->deleteDirectory(base_path('node_modules'));
            $files->delete(base_path('bun.lockb'));
            $files->delete(base_path('bun.lock'));
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
        return (new PhpExecutableFinder)->find(false) ?: 'php';
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

        $process->run(function ($type, $line): void {
            $this->output->write('    '.$line);
        });
    }
}
