<?php

namespace Secondnetwork\Kompass\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
       
        Auth::guard('web')->logout();
        
        Session::invalidate();
        Session::regenerateToken();

        return redirect('/login');
        
    }
}
