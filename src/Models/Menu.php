<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(Menuitem::modelClass(), 'subgroup')->with('children');
    }

    protected static function boot()
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

        static::created(function ($menu): void {
            $menu->slug = $menu->createSlug($menu->name);

            $menu->save();
        });

        static::updating(function ($menu): void {
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
