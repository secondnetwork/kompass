<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // public $timestamps = fal
    // protected static function boot()
    protected static function boot()
    // public static function __callStatic($method, $parameters)
    {
        parent::boot();
        static::creating(function () {
            Cache::forget('settings');
        });
        static::updating(function () {
            Cache::forget('settings');
        });
        static::deleting(function () {
            Cache::forget('settings');
        });

    }
}
