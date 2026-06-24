<?php

namespace Secondnetwork\Kompass\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\select;
use function Laravel\Prompts\warning;

class UpdateCommand extends Command
{
    public $signature = 'kompass:update';

    public $description = 'Update the Kompass components and resources';

    public function handle(): int
    {
        info('Updating Kompass...');

        $this->publishAssets();
        $this->runMigrations();
        $this->clearCaches();

        $rebuildFrontend = select(
            label: 'Rebuild frontend assets?',
            options: [true => 'Yes', false => 'No'],
            default: false
        );

        if ($rebuildFrontend) {
            $packageManager = select(
                label: 'Which package manager do you use?',
                options: [
                    'bun' => 'Bun',
                    'yarn' => 'Yarn',
                    'npm' => 'Npm',
                    'pnpm' => 'pnpm',
                ],
                default: 'bun'
            );

            $this->rebuildFrontend($packageManager);
        }

        note('Kompass has been updated successfully.');

        return self::SUCCESS;
    }

    protected function publishAssets(): void
    {
        info('Publishing vendor assets...');

        File::deleteDirectory(public_path('vendor/kompass'));

        $this->callSilent('vendor:publish', [
            '--provider' => 'Secondnetwork\Kompass\KompassServiceProvider',
            '--force' => true,
        ]);

        $this->callSilent('vendor:publish', [
            '--tag' => 'migrations',
            '--force' => true,
        ]);

        info('Assets published.');
    }

    protected function runMigrations(): void
    {
        info('Running migrations...');

        File::copyDirectory(__DIR__.'/../database/seeders', database_path('seeders'));

        $this->call('migrate');
    }

    protected function clearCaches(): void
    {
        info('Clearing caches...');
        $this->call('optimize:clear');
    }

    protected function rebuildFrontend(string $packageManager): void
    {
        info('Rebuilding frontend assets...');

        $commands = [
            'bun' => 'bun run build',
            'yarn' => 'yarn run build',
            'npm' => 'npm run build',
            'pnpm' => 'pnpm run build',
        ];

        if (isset($commands[$packageManager])) {
            warning('Running: '.$commands[$packageManager]);

            $result = \Illuminate\Support\Facades\Process::forever()->run(
                $commands[$packageManager],
                function (string $type, string $output): void {
                    $this->output->write('    '.$output);
                }
            );

            if ($result->successful()) {
                info('Frontend assets rebuilt.');
            } else {
                $this->error('Frontend build failed. Run the build command manually.');
            }
        }
    }
}
