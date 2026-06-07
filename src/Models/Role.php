<?php

namespace Secondnetwork\Kompass\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    /**
     * Extends Spatie's Role so the model gains the permissions() relationship
     * and helpers (syncPermissions, givePermissionTo, hasPermissionTo, …) while
     * still reading/writing the same `roles` table used by Spatie internally.
     *
     * Spatie's base model uses $guarded = [], so all columns — including the
     * project's custom display_name — remain mass-assignable.
     */
}
