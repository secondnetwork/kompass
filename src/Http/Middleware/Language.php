<?php

namespace Secondnetwork\Kompass\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class language
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (in_array($request->segment(1), config('kompass.available_locales'))) {
            if (Session::has('locale')) {
                App::setLocale(Session::get('locale'));
            }

            app()->setLocale($request->segment(1));

            URL::defaults(['locale' => $request->segment(1)]);
        }

        return $next($request);
    }
}
