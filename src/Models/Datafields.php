<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datafields extends Model
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
