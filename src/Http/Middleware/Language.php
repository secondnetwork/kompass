<?php

namespace Secondnetwork\Kompass\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;

class language
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle(Request $request, Closure $next)
    {

        if (in_array($request->segment(1),config('kompass.available_locales'))) {
            if (Session::has('locale')) {
                App::setLocale(Session::get('locale'));
            }
    
            app()->setLocale($request->segment(1));
   
            URL::defaults(['locale' => $request->segment(1)]);
          } 



        return $next($request);
    }
}
