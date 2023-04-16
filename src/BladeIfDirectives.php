<?php

namespace Secondnetwork\Kompass;

use Secondnetwork\Kompass\Models\Role;

class BladeIfDirectives
{
    public static function is_admin()
    {
        $role = 'admin';
        $rolesFromDatabase = Role::where('slug', $role)->first();
   
        if ($rolesFromDatabase && (auth()->user()->roles->first()->slug ?? '')) {
            if ($role == auth()->user()->roles->first()->slug && auth()->user()->roles->first()->id == $rolesFromDatabase->id) {
                return true;
            }
        }

        return false;
    }
}
