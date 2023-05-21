<?php

namespace Secondnetwork\Kompass\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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

    public function handle(Request $request, Closure $next, string|array $role)
    {
        if (! $this->authorization('roles', $role)) {
            return abort('401');
        }

        return $next($request);
    }
}
