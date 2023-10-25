<?php

namespace Secondnetwork\Kompass\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleMiddleware
{
    protected function authorization(
        string $type,
        string|array $rolesPermissions,
    ): bool {
        $method = $type == 'roles' ? 'hasRole' : 'hasPermission';
        $rolesPermissions = standardize($rolesPermissions, true);

        foreach ($rolesPermissions as $role) {
            if (auth()->user()->roles->first()->slug == $role) {
                return auth()->user()->$method($role);
            }
        }

        return false;
    }

    public function handle(Request $request, Closure $next, string|array $roles)
    {

        if (Str::contains($roles, '|')) {
            $roles = explode('|', $roles);
        }

        return $next($request);
    }
}
