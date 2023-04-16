<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menuitem extends Model
{
    use HasFactory;

    protected $table = 'menu_items';

    protected $guarded = [];

    public $timestamps = false;

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

    public function children()
    {
        return $this->hasMany(Menuitem::class, 'subgroup')->with('children');
    }
}
