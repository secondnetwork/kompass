<?php

namespace Secondnetwork\Kompass\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'content' => 'array',
        'begin_at' => 'datetime:Y-m-d H:i:s',
        'end_at' => 'datetime:Y-m-d H:i:s',
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

        return Carbon::parse($date)->tz($timezone)->format('d.m.Y H:i');
    }

    public function getUpdatedAtAttribute($date)
    {
        $timezone = config('app.timezone');

        return Carbon::parse($date)->tz($timezone)->format('d.m.Y H:i');
    }
}
