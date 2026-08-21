<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeGlobal($query)
    {
        return $query->where('group', 'global');
    }

    /**
     * Resolve a setting's stored image value to a displayable path.
     *
     * New rows store the media library `File` id (integer), picked via the same
     * Medialibrary flow used for block/datafield images. Legacy rows may still
     * hold a full storage path string written by the old raw-upload widget.
     */
    public static function resolveImageUrl($data): ?string
    {
        if (empty($data)) {
            return null;
        }

        if (is_numeric($data)) {
            $file = File::find($data);

            if (! $file) {
                return null;
            }

            $path = $file->path ? $file->path.'/' : '';

            return '/storage/'.$path.$file->slug.'.'.$file->extension;
        }

        return $data;
    }

    // public $timestamps = fal
    // protected static function boot()
    protected static function boot()
    // public static function __callStatic($method, $parameters)
    {
        parent::boot();
        static::creating(function (): void {
            Cache::forget('settings');
        });
        static::updating(function (): void {
            Cache::forget('settings');
        });
        static::deleting(function (): void {
            Cache::forget('settings');
        });

    }
}
