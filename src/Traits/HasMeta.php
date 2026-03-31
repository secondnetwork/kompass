<?php

namespace Secondnetwork\Kompass\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Secondnetwork\Kompass\Models\Meta;

trait HasMeta
{
    public function metas(): MorphMany
    {
        return $this->morphMany(Meta::class, 'metable');
    }

    public function getMeta(string $key, $default = null)
    {
        // 1. Suche nach dem Key direkt
        $meta = $this->metas()->where('key', $key)->first();
        if ($meta) {
            $value = $meta->value;
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            return $value;
        }

        // 2. Falls nicht gefunden, suche in 'set' (falls vorhanden)
        $setMeta = $this->metas()->where('key', 'set')->first();
        if ($setMeta) {
            $decoded = json_decode($setMeta->value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                if (array_key_exists($key, $decoded)) {
                    return $decoded[$key];
                }
            }
        }

        return $default;
    }

    public function setMeta(string $key, $value): void
    {
        // Wenn es ein Array oder Objekt ist, als JSON speichern
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        $this->metas()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public function deleteMeta(string $key): void
    {
        $this->metas()->where('key', $key)->delete();
    }

    public function saveMeta(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->setMeta($key, $value);
        }
    }

    public function getAllMetaAttribute()
    {
        return $this->metas;
    }
}
