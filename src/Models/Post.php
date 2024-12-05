<?php

namespace Secondnetwork\Kompass\Models;

use Carbon\Carbon;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
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

    public function blocks()
    {
        return $this->morphMany(Block::class, 'blockable');
        // return $this->belongsToMany('Secondnetwork\Kompass\Models\Block');
        // return $this->hasOne('Rote');
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
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['title']);
        // Chain fluent methods for configuration options
    }
}
