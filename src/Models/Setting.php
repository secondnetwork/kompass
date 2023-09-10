<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';

    protected $guarded = [];

    // public $timestamps = false;

    protected static function boot()
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

    // protected $dispatchesEvents = [
    //     'updating' => SettingUpdated::class,
    // ];
}
