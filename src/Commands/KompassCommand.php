<?php

namespace Secondnetwork\Kompass\Commands;

use App\Models\User;
use Secondnetwork\Kompass\Models\Setting; // Importiert das Setting Model
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\ServiceProvider;

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

    public function handle(): int
    {
        $this->options = $this->options();

        info('Welcome to the installation of Kompass - A Laravel CMS.');

        // 1. Service Provider registrieren
        $this->updateServiceProviders();

        // 2. Abfragen (Frontend & DB)
        $installFrontend = select(
            label: 'Install Frontend Themes?',
            options: [true => 'Yes', false => 'No'],
            default: true
        );

        $packageManager = 'npm';
        if ($installFrontend) {
            $packageManager = select(
                label: 'Which package manager do you use?',
                options: [
                    'bun' => 'Bun',
                    'yarn' => 'Yarn',
                    'npm' => 'Npm',
                    'pnpm' => 'pnpm',
                ],
                default: 'npm'
            );
        }

        warning('Warning: Have you made a backup of your database?');
        $dropDatabase = select(
            label: 'Drop all tables from the database for a fresh installation?',
            options: [true => 'Yes', false => 'No'],
            default: false
        );

        // 3. Grundlegende Assets & Migrationen veröffentlichen
        // Wir müssen die Migrationen publishen, bevor wir migrieren können.
        $this->publishCoreAssets();

        // 4. Datenbank Migration
        if ($dropDatabase) {
            $this->runDatabaseMigrations(fresh: true);
        } else {
            // Sicherstellen, dass Tabellen existieren, auch ohne Fresh
            $this->call('migrate');
        }

        $addGlobalSettings = select(
            label: 'Update global settings for the Website?',
            options: [true => 'Yes', false => 'No'],
            default: true
        );

        if ($addGlobalSettings) {
           // 5. Globale Seiteneinstellungen abfragen (NEU)
            $this->configureGlobalSettings();
        }
        
        // 6. Admin User erstellen
        $addNewUser = select(
            label: 'Create new Admin User?',
            options: [true => 'Yes', false => 'No'],
            default: true
        );

        if ($addNewUser) {
            $this->createUser();
        }

        // 7. Frontend Assets installieren (falls gewählt)
        if ($installFrontend) {
            $this->installAssets($packageManager);
        }

        // 8. Finalisierung
        $this->call('optimize:clear');

        if (! File::exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        $this->sendSuccessMessage();

        return self::SUCCESS;
    }

    /**
     * Fragt die globalen Einstellungen ab und speichert sie in der DB.
     */
    protected function configureGlobalSettings(): void
    {
        info('--- Global Website Configuration ---');

        $webtitle = text(
            label: 'Website Title',
            placeholder: 'My Great Website',
            default: 'Kompass Website',
            required: true
        );

        $supline = text(
            label: 'Supline (Slogan)',
            placeholder: 'Welcome to our site',
            default: ''
        );

        $description = text(
            label: 'SEO Description',
            placeholder: 'A short description of the website',
            default: ''
        );

        $settings = [
            'webtitle' => $webtitle,
            'supline' => $supline,
            'description' => $description,
        ];

        // Speichern analog zur Livewire Komponente
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                [
                    'key' => $key,
                    'group' => 'global',
                ],
                [
                    'data' => $value,
                    'name' => ucwords(str_replace('_', ' ', $key)),
                ]
            );
        }

        info('Settings saved successfully.');
    }

    protected function getUserData(): array
    {
        return [
            'name' => $this->options['name'] ?? text(
                label: 'Admin Name',
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
                validate: fn (string $value) => strlen($value) < 8 ? 'The password must be at least 8 characters.' : null,
            )),
        ];
    }

    protected function createUser(): void
    {
        $now = Carbon::now()->toDateTimeString();
        $userData = Arr::prepend($this->getUserData(), $now, 'email_verified_at');
        
        $user = User::create($userData);
        
        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles('admin');
        }
    }

    protected function sendSuccessMessage(): void
    {
        $loginUrl = config('app.url').'/login';
        note('Kompass is now installed.');
        info("Log in at {$loginUrl} with your credentials.");
    }

    protected function installAssets(string $packageManager): void
    {
        info('Installing Frontend Assets...');

        // Config Files
        File::copy(__DIR__.'/../../stubs/livewire/postcss.config.cjs', base_path('postcss.config.cjs'));
        File::copy(__DIR__.'/../../stubs/livewire/vite.config.js', base_path('vite.config.js'));

        // Directories & Clean up
        File::deleteDirectory(resource_path('resources')); // clean default laravel resources if needed
        File::ensureDirectoryExists(resource_path('views'));
        File::ensureDirectoryExists(resource_path('views/layouts'));

        // Layouts copying
        File::copyDirectory(__DIR__.'/../../stubs/resources', resource_path());

        $this->flushNodeModules();

        // NPM Packages Update (Deine neue Liste)
        $this->updateNodePackages(function ($packages) {
            return [
                '@lehoczky/postcss-fluid' => '^1.0.3',
                '@tailwindcss/forms' => '^0.5.10',
                '@tailwindcss/postcss' => '^4.1.18',
                '@tailwindcss/typography' => '^0.5.19',
                '@tailwindcss/vite' => '^4.1.18',
                'autoprefixer' => '^10.4.23',
                'daisyui' => '^5.0.0',
                'laravel-vite-plugin' => '^2.0.1',
                'postcss' => '^8.5.6',
                'tailwindcss' => '^4.1.18',
                'vite' => '^7.3.0',
            ] + $packages;
        });

        $commands = [
            'bun' => ['bun install', 'bun run build'],
            'yarn' => ['yarn install', 'yarn run build'],
            'npm' => ['npm install', 'npm run build'],
            'pnpm' => ['pnpm install', 'pnpm run build'],
        ];

        if (isset($commands[$packageManager])) {
            $this->runShellCommands($commands[$packageManager]);
        }

        info('Frontend Theme Installed');
    }

    protected function publishCoreAssets(): void
    {
        File::deleteDirectory(public_path('vendor/kompass'));
        
        // Auth Controller & User Model
        File::ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        File::copy(__DIR__.'/../../stubs/app/Http/Controllers/Auth/VerifyEmailController.php', base_path('app/Http/Controllers/Auth/VerifyEmailController.php'));
        File::copy(__DIR__.'/../../stubs/app/Models/User.php', app_path('Models/User.php'));
        
        // Routes
        File::copy(__DIR__.'/../../stubs/routes/auth.php', base_path('routes/auth.php'));
        File::copy(__DIR__.'/../../stubs/routes/web.php', base_path('routes/web.php'));

        // Publish Vendor Files & Migrations
        $this->callSilent('vendor:publish', ['--provider' => 'Secondnetwork\Kompass\KompassServiceProvider']);
        $this->callSilent('vendor:publish', ['--tag' => 'migrations', '--force' => true]);
    }

    public function runDatabaseMigrations(bool $fresh = false): void
    {
        // Database seeders...
        File::copyDirectory(__DIR__.'/../database/seeders', database_path('seeders'));
        
        if ($fresh) {
            $this->call('migrate:fresh');
            $this->info('Database wiped and migrated.');
        } else {
            $this->call('migrate');
            $this->info('Database migrated.');
        }
    }

    public function updateServiceProviders(): void
    {
        if (! method_exists(ServiceProvider::class, 'addProviderToBootstrapFile')) {
            return;
        }
        ServiceProvider::addProviderToBootstrapFile(\App\Providers\KompassServiceProvider::class);
    }

    protected static function updateNodePackages(callable $callback, bool $dev = true): void
    {
        if (! File::exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';
        $packages = json_decode(File::get(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            $packages[$configurationKey] ?? [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        File::put(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    protected static function flushNodeModules(): void
    {
        File::deleteDirectory(base_path('node_modules'));
        
        $filesToDelete = ['bun.lockb', 'bun.lock', 'pnpm-lock.yaml', 'yarn.lock', 'package-lock.json'];
        foreach ($filesToDelete as $file) {
            File::delete(base_path($file));
        }
    }

    protected function runShellCommands(array $commands): void
    {
                Process::forever()->run(implode(' && ', $commands), function (string $type, string $output) {
            $this->output->write('    '.$output);
        });
    }
}