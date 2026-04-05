<?php

namespace Secondnetwork\Kompass\Traits;

use Illuminate\Support\Str;

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
                return \Spatie\Activitylog\Support\LogOptions::defaults()
                    ->logAll();
            });
        }
    }
}
