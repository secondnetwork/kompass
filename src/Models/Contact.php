<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Contact extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getCreatedAtAttribute($date)
    {
        $timezone = config('app.timezone');

        return Carbon::parse($date)->tz($timezone)->format('d.m.Y h:i');
    }

    public function getUpdatedAtAttribute($date)
    {
        $timezone = config('app.timezone');

        return Carbon::parse($date)->tz($timezone)->format('d.m.Y H:i');
    }
}
