<?php

namespace Secondnetwork\Kompass;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Secondnetwork\Kompass\Http\Controllers\KompassController;
use Secondnetwork\Kompass\Livewire\AccountForm;
use Secondnetwork\Kompass\Livewire\BlocksData;
use Secondnetwork\Kompass\Livewire\BlocksTable;
use Secondnetwork\Kompass\Livewire\Brokenlink;
use Secondnetwork\Kompass\Livewire\Medialibrary;
use Secondnetwork\Kompass\Livewire\MenuData;
use Secondnetwork\Kompass\Livewire\MenuTable;
use Secondnetwork\Kompass\Livewire\PagesData;
use Secondnetwork\Kompass\Livewire\PagesTable;
use Secondnetwork\Kompass\Livewire\Redirection;
use Secondnetwork\Kompass\Livewire\Roles;
use Secondnetwork\Kompass\Livewire\Settings;

//Asset Routes
Route::get('assets', [KompassController::class, 'assets'])->name('kompass_asset');

View::composer('*', function ($view) {
    $vi = str_replace('.', '_', $view->getName());
    $vn = str_replace('::', '-', $vi);
    View::share('viewName', $vn);
    // dump($view->getName());
});

Route::group(['middleware' => ['web', 'auth', 'verified'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::view('/', 'kompass::admin.dashboard')->name('dashboard-root');
    Route::view('dashboard', 'kompass::admin.dashboard')->name('dashboard');

    Route::get('pages', PagesTable::class)->name('pages');
    Route::get('pages/{action}/{id}', PagesData::class)->name('pages.show');

    Route::get('medialibrary', Medialibrary::class)->name('medialibrary');

    Route::get('menus', MenuTable::class)->name('menus');
    Route::get('menus/{action}/{id}', MenuData::class)->name('menus.show');

    Route::get('blocks', BlocksTable::class)->name('blocks');
    Route::get('blocks/{action}/{id}', BlocksData::class)->name('blocks.show');

    Route::get('settings', Settings::class)->name('settings');

    Route::get('redirect', Redirection::class)->name('redirect');
    Route::get('brokenlink', Brokenlink::class)->name('brokenlink');

    Route::view('profile', 'kompass::admin.profile')->name('profile');

    Route::group(['middleware' => ['role:root|admin']], function () {
        Route::get('account', AccountForm::class)->name('account');
        Route::get('roles', Roles::class)->name('roles');
    });

    Route::view('about', 'kompass::admin.about')->name('about')->middleware('role:user|admin');
});
