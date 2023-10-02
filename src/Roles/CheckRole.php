<?php

namespace Secondnetwork\Kompass\Roles;

use Closure;
use Illuminate\Support\Str;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        abort_unless($request->user(), 401, 'Unauthorized');

        if (Str::contains($roles, '|')) {
            $roles = explode('|', $roles);
        }

        abort_unless($request->user()->hasRole($roles), 403, 'This action is unauthorized.');

        return $next($request);
    }
}
