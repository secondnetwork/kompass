<?php

namespace Secondnetwork\Kompass\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

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

    public function posts()
    {
        return $this->hasMany(Post::class);
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
            ->logOnly(['name']);
    }
}
