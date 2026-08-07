<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $table = 'meta';

    protected $guarded = [];

    protected $keyType = 'int';

    public $incrementing = true;

    protected static array $distinctValueCache = [];

    protected static function boot(): void
    {
        parent::boot();
    }

    public function metable()
    {
        return $this->morphTo();
    }

    /**
     * Distinct, non-empty values stored under a given key, e.g. for
     * autocomplete suggestions. Memoized per request since the same
     * key is looked up once per block that shows the relevant control.
     *
     * @return array<int, string>
     */
    public static function distinctValuesForKey(string $key): array
    {
        return self::$distinctValueCache[$key] ??= self::where('key', $key)
            ->whereNotNull('value')
            ->where('value', '!=', '')
            ->distinct()
            ->orderBy('value')
            ->pluck('value')
            ->all();
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }
}
