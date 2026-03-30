<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Secondnetwork\Kompass\Traits\HasMeta;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Block extends Model
{
    use HasFactory;
    use HasMeta;
    use LogsActivity;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function (): void {
            cache()->flush();
        });
        static::updating(function (): void {
            cache()->flush();
        });
        static::deleting(function (): void {
            cache()->flush();
        });
    }

    public function blockable()
    {
        return $this->morphTo();
    }

    public function meta()
    {
        return $this->morphMany(Meta::class, 'metable');
    }

    public function metas()
    {
        return $this->meta();
    }

    public function datafield()
    {
        return $this->hasMany(Datafield::class)->orderBy('order', 'asc');
    }

    public function children()
    {
        return $this->hasMany(Block::class, 'subgroup')->with('children', 'datafield', 'meta')->orderBy('order', 'asc');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'data']);
        // Chain fluent methods for configuration options
    }
}
