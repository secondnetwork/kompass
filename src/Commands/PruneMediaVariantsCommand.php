<?php

namespace Secondnetwork\Kompass\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneMediaVariantsCommand extends Command
{
    public $signature = 'kompass:media:prune-variants
        {--dry-run : List orphaned variants without deleting them}
        {--force : Delete without asking for confirmation}';

    public $description = 'Remove orphaned webp/avif/jpeg image variants left behind by deleted or moved media files';

    private const VARIANT_PATTERN = '/^(.+)-(?:\d+|auto)x(?:\d+|auto)\.(webp|avif|jpe?g|png)$/i';

    public function handle(): int
    {
        $disk = config('kompass.storage.disk', 'public');
        $storage = Storage::disk($disk);

        $allFiles = collect($storage->allFiles());

        $filesByDir = $allFiles->groupBy(function (string $path) {
            $dir = pathinfo($path, PATHINFO_DIRNAME);

            return $dir === '.' ? '' : $dir;
        });

        $orphans = [];

        foreach ($allFiles as $filePath) {
            if (! preg_match(self::VARIANT_PATTERN, basename($filePath), $matches)) {
                continue;
            }

            $slug = $matches[1];
            $dir = pathinfo($filePath, PATHINFO_DIRNAME);
            $dir = $dir === '.' ? '' : $dir;

            $hasOriginalSibling = $filesByDir->get($dir, collect())->contains(function (string $siblingPath) use ($slug, $filePath) {
                if ($siblingPath === $filePath) {
                    return false;
                }

                $siblingBasename = basename($siblingPath);

                if (preg_match(self::VARIANT_PATTERN, $siblingBasename)) {
                    return false;
                }

                return pathinfo($siblingBasename, PATHINFO_FILENAME) === $slug;
            });

            if (! $hasOriginalSibling) {
                $orphans[] = $filePath;
            }
        }

        if (empty($orphans)) {
            $this->info('No orphaned image variants found.');

            return self::SUCCESS;
        }

        $this->info(count($orphans).' orphaned variant(s) found (no matching original in the same folder):');
        foreach ($orphans as $orphan) {
            $this->line("  {$orphan}");
        }

        if ($this->option('dry-run')) {
            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm('Delete these files?')) {
            $this->warn('Aborted, nothing was deleted.');

            return self::SUCCESS;
        }

        $storage->delete($orphans);
        $this->info(count($orphans).' orphaned variant(s) deleted.');

        return self::SUCCESS;
    }
}
