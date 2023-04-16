<?php

namespace Secondnetwork\Kompass\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use SoftDeletes;
    use HasFactory;


    protected $casts = [
        'content' => 'array',
    ];

    protected $fillable = [
        'status', 'title', 'slug', 'thumbnails', 'meta_description', 'layout', 'content', 'updated_at',
    ];



    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];



    public $timestamps = false;

    public function block()
    {
        return $this->belongsToMany('Secondnetwork\Kompass\Models\Block');
        // return $this->hasOne('Rote');
    }


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

    protected static function boot()
    {
        parent::boot();

    }

}
