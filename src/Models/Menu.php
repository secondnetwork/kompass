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

        $query = static::whereSlug($businessNameURL);
        if ($this->land) {
            $query->where('land', $this->land);
        }
        
        $checkSlug = $query->exists();

        if ($checkSlug) {
            $numericalPrefix = 1;
            while (1) {
                $newSlug = $businessNameURL.'-'.$numericalPrefix++;
                $newSlug = Str::slug($newSlug, '-', 'de');
                
                $query = static::whereSlug($newSlug);
                if ($this->land) {
                    $query->where('land', $this->land);
                }
                
                if (! $query->exists()) {
                    return $newSlug; //New Slug
                }
            }
        }

        //Slug do not exists. Just use the selected Slug.
        return $businessNameURL;
    }
}
