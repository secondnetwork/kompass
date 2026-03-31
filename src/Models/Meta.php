<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $table = 'meta';

    protected $guarded = [];

    protected $keyType = 'int';

    public $incrementing = true;

    protected static function boot(): void
    {
        parent::boot();
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
