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

        if (config('kompass.settings.registration_can_user')) {
            Fortify::registerView(function () {
                return view('kompass::admin.auth.register');
            });
        } else {
            Fortify::registerView(function () {
                return response('', 404);
            });
        }

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
