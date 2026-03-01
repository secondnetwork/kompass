<?php

namespace Secondnetwork\Kompass\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $localesData = setting('global.available_locales');
        if ($localesData) {
            $availableLocales = is_array($localesData) ? $localesData : json_decode($localesData, true);
        } else {
            $availableLocales = config('kompass.available_locales', ['de', 'en', 'tr']);
        }

        if (in_array($request->segment(1), $availableLocales)) {
            if (Session::has('locale')) {
                App::setLocale(Session::get('locale'));
            }

            app()->setLocale($request->segment(1));

            URL::defaults(['locale' => $request->segment(1)]);
        }

        return $next($request);
    }
}
