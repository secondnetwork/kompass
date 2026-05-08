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

    public function handle(Request $request, Closure $next, string|array $roles): mixed
    {
        if (Str::contains($roles, '|')) {
            $roles = explode('|', $roles);
        }

        if (! $request->user() || ! $request->user()->hasRole($roles)) {
            abort(403);
        }

        return $next($request);
    }
}
