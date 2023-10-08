<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

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
    }
}
