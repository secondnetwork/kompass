<?php

namespace Secondnetwork\Kompass\Models;


use Illuminate\Database\Eloquent\Model;

use SiroDiaz\Redirection\Models\Redirection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SiroDiaz\Redirection\Models\Redirection as RedirectionBaseModel;

class Redirect extends Redirection
{
    use HasFactory;

    // protected $table = 'Datafields';
    // protected $casts = [
    //     'data' => 'array',
    // ];
    // protected $fillable = [
    //     'id','status', 'name', 'blocktemplate_id', 'slug', 'type', 'grid', 'layout','content'
    // ];
    protected $guarded = [];

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
}
