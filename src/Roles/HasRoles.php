<?php

namespace Secondnetwork\Kompass\Roles;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany('Secondnetwork\Kompass\Models\Role')
            ->withTimestamps();
    }

    public function hasRole($role): bool
    {
        if (is_array($role)) {
            return $this->roles->whereIn('name', $role)->isNotEmpty();
        }

        return $this->roles->where('name', $role)->isNotEmpty();
    }
}
