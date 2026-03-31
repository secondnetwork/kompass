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
        return $this->hasMany(Block::class, 'subgroup')->with('children', 'datafield', 'metas')->orderBy('order', 'asc');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'data']);
    }

    public function getLayoutAttribute()
    {
        return $this->getMeta('layout');
    }

    public function getAlignmentAttribute()
    {
        return $this->getMeta('alignment');
    }

    public function getSliderAttribute()
    {
        return $this->getMeta('slider');
    }

    public function getLayoutgridAttribute()
    {
        return $this->getMeta('layoutgrid') ?? $this->attributes['layoutgrid'] ?? null;
    }

    public function getStatusAttribute()
    {
        return $this->getMeta('status') ?? $this->attributes['status'] ?? null;
    }
}
