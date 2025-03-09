<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Secondnetwork\Kompass\Livewire\Auth\ConfirmPassword;
use Secondnetwork\Kompass\Livewire\Auth\ForgotPassword;
use Secondnetwork\Kompass\Livewire\Auth\Login;
use Secondnetwork\Kompass\Livewire\Auth\Register;
use Secondnetwork\Kompass\Livewire\Auth\ResetPassword;
use Secondnetwork\Kompass\Livewire\Auth\VerifyEmail;
use Secondnetwork\Kompass\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'guest'])->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware( ['web', 'auth'])->group(function () {
    Route::get('verify-email', VerifyEmail::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::get('confirm-password', ConfirmPassword::class)
        ->name('password.confirm');
});

Route::post('logout', Logout::class)
    ->name('logout');
