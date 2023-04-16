<?php

namespace Secondnetwork\Kompass\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Secondnetwork\Kompass\Models\Role;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param  Closure  $next
     * @param $role
     * @param  null  $permission
     * @return mixed
     */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, array|string $role)
    {


        $hasAnyRole =  auth()->user()->hasAnyRole($role);
        $hasRole = auth()->user()->hasRole($role);

        if ($hasRole && $hasAnyRole) {
            return $next($request);
        }


        return abort(401);
    }
}
