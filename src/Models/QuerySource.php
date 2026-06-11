<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A database-defined source for the relationship block, merged into the
 * config-based registry by query_models(). The model is referenced via
 * `model_key`, which resolves through the kompass.query_source_models allowlist —
 * a raw class name is never stored or instantiated from user input.
 */
class QuerySource extends Model
{
    use HasFactory;

    protected $table = 'query_sources';

    protected $guarded = [];

    protected $casts = [
        'order_fields' => 'array',
        'display_fields' => 'array',
        'with' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function (): void {
            cache()->forget('kompass-query-sources');
        });
        static::deleted(function (): void {
            cache()->forget('kompass-query-sources');
        });
    }
}
