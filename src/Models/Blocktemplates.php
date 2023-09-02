<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blocktemplates extends Model
{
    use HasFactory;

    protected $table = 'blocktemplates';

    protected $casts = [
        'content' => 'array',
    ];

    // protected $fillable = [
    //    'id', 'status', 'name', 'slug', 'thumbnails', 'meta_description', 'layout','content'
    // ];
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($menu) {
            $menu->slug = $menu->createSlug($menu->name);

            $menu->save();
        });

        static::updating(function ($menu) {
            $menu->slug = $menu->createSlug($menu->name);
        });
    }

    private function createSlug($anme)
    {
        $businessNameURL = Str::slug($anme, '-', 'de'); //Convert Input to Str Slug

        $placeObj = new Page;

        $checkSlug = static::whereSlug($businessNameURL)->exists();

        if ($checkSlug) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $businessNameURL.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                $checkSlug = $placeObj->whereSlug($newSlug)->exists();
                if (! $checkSlug) {
                    return $newSlug; //New Slug
                }
            }
        }

        //Slug do not exists. Just use the selected Slug.
        return $businessNameURL;
    }
}
