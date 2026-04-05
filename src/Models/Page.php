<?php

namespace Secondnetwork\Kompass\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Secondnetwork\Kompass\Traits\HasMeta;
use Secondnetwork\Kompass\Traits\LogsActivity;

class Page extends Model
{
    use HasFactory;
    use HasMeta;
    use SoftDeletes;
    use LogsActivity;

    protected $casts = [
        'content' => 'array',
    ];

    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

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

    public $timestamps = false;

    public function blocks()
    {
        return $this->morphMany(Block::class, 'blockable');
    }

    public function meta()
    {
        return $this->morphMany(Meta::class, 'metable');
    }

    public function metas()
    {
        return $this->meta();
    }

    public function menuitems()
    {
        return $this->hasMany(Menuitem::class);
    }

    public function getCreatedAtAttribute($date)
    {
        $timezone = config('app.timezone');
        $dateformat = config('kompass.dateformat');

        return Carbon::parse($date)->tz($timezone)->format($dateformat);
    }

    public function getUpdatedAtAttribute($date)
    {
        $timezone = config('app.timezone');
        $dateformat = config('kompass.dateformat');

        return Carbon::parse($date)->tz($timezone)->format($dateformat);
    }
}
