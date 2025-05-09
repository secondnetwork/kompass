<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $table = 'redirections';

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
}
