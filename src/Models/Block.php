<?php

namespace Secondnetwork\Kompass\Models;

use Kolossal\Multiplex\HasMeta;
use Illuminate\Database\Eloquent\Model;
use Secondnetwork\Kompass\Models\Datafield;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Block extends Model
{
    use HasFactory;
    use HasMeta;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function () {
            cache()->flush();
        });
        static::updating(function () {
            cache()->flush();
        });
        static::deleting(function () {
            cache()->flush();
        });
    }

    public function blockable()
    {
        return $this->morphTo();
    }

    public function datafield()
    {
        return $this->hasMany(Datafield::class);
    }

    public function children()
    {
        return $this->hasMany(Block::class, 'subgroup')->with('children','datafield')->orderBy('order', 'asc');
    }
}
