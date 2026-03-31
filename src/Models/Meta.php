<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meta extends Model
{
    protected $table = 'meta';

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Meta $model): void {
            if (empty($model->id)) {
                $model->id = (string) Str::ulid();
            }
        });
    }

    public function metable()
    {
        return $this->morphTo();
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }
}
