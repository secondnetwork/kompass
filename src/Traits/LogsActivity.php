<?php

namespace Secondnetwork\Kompass\Traits;

use Spatie\Activitylog\Support\LogOptions;

trait LogsActivity
{
    private function shouldUseActivityLogging(): bool
    {
        return class_exists(\Spatie\Activitylog\Models\Concerns\LogsActivity::class);
    }

    public static function bootLogsActivity(): void
    {
        if (class_exists(\Spatie\Activitylog\Models\Concerns\LogsActivity::class)) {
            $class = static::class;
            $class::macro('getActivitylogOptions', function () {
                return LogOptions::defaults()
                    ->logAll();
            });
        }
    }
}
