<?php

namespace App\Providers;

use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Gate;
use Secondnetwork\Kompass\Models\Role;
use Illuminate\Support\ServiceProvider;

class KompassServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::loginView(function () {
            return view('kompass::admin.auth.login');
        });

        Fortify::registerView(function () {
            return view('kompass::admin.auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('kompass::admin.auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('kompass::admin.auth.reset-password', ['request' => $request]);
        });

        Fortify::verifyEmailView(function () {
            return view('kompass::admin.auth.verify-email');
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

        $roles = Role::with('users')->get();

        $permissionsArray = [];
        foreach ($roles as $role) {
            $permissionsArray[$role->slug][] = $role->id;
            // foreach ($role->slug as $permissions) {
            //     $permissionsArray[$permissions->title][] = $role->id;
            // }
        }

        // Every permission may have multiple roles assigned
        foreach ($permissionsArray as $title => $roles) {
            Gate::define($title, function ($user) use ($roles) {
                // We check if we have the needed roles among current user's roles
                return count(array_intersect($user->roles->pluck('id')->toArray(), $roles)) > 0;
            });
        }
    }
}
