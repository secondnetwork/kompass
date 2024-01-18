<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kolossal\Multiplex\HasMeta;

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
        return $this->hasMany(Block::class, 'subgroup')->with('children')->orderBy('order', 'asc');
    }
}
