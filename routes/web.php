<?php

namespace Secondnetwork\Kompass;

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Secondnetwork\Kompass\Livewire\Roles;
use Secondnetwork\Kompass\Livewire\MenuData;
use Secondnetwork\Kompass\Livewire\Settings;
use Secondnetwork\Kompass\Livewire\MenuTable;
use Secondnetwork\Kompass\Livewire\PagesData;
use Secondnetwork\Kompass\Livewire\PostsData;
use Secondnetwork\Kompass\Livewire\BlocksData;
use Secondnetwork\Kompass\Livewire\PagesTable;
use Secondnetwork\Kompass\Livewire\PostsTable;
use Secondnetwork\Kompass\Livewire\AccountForm;
use Secondnetwork\Kompass\Livewire\BlocksTable;
use Secondnetwork\Kompass\Livewire\Medialibrary;
use Secondnetwork\Kompass\Livewire\Settings\Profile;
use Secondnetwork\Kompass\Http\Controllers\KompassController;

//Asset Routes
Route::get('assets', [KompassController::class, 'assets'])->name('kompass_asset');

// Route::get('/password/create/{id}', [AccountForm::class, 'create'])
//     ->middleware(['signed'])
//     ->name('password.create');

// Route::post('/password', [AccountForm::class, 'store'])
//     ->middleware(['signed'])
//     ->name('password.store');

View::composer('*', function ($view) {
    $vi = str_replace('.', '_', $view->getName());
    $vn = str_replace('::', '-', $vi);
    View::share('viewName', $vn);
    // dump($view->getName());
});

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::view('/', 'kompass::admin.dashboard')->name('dashboard-root');
    Route::view('dashboard', 'kompass::admin.dashboard')->name('dashboard');
    Route::view('profile', 'kompass::admin.profile')->name('profile');

    Route::get('posts', PostsTable::class)->name('posts');
    Route::get('posts/{action}/{id}', PostsData::class)->name('posts.show');

    Route::get('pages', PagesTable::class)->name('pages');
    Route::get('pages/{action}/{id}', PagesData::class)->name('pages.show');

    Route::get('medialibrary', Medialibrary::class)->name('medialibrary');

    Route::get('menus', MenuTable::class)->name('menus');
    Route::get('menus/{action}/{id}', MenuData::class)->name('menus.show');

    Route::group(['middleware' => ['role:manager|admin']], function () {
        Route::get('blocks', BlocksTable::class)->name('blocks');
        Route::get('blocks/{action}/{id}', BlocksData::class)->name('blocks.show');
        Route::get('settings', Settings::class)->name('settings');
    });

    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('account', AccountForm::class)->name('account');
        Route::get('roles', Roles::class)->name('roles');
    });

    Route::view('about', 'kompass::admin.about')->name('about');
    Route::view('cd', 'kompass::admin.cd')->name('cd');

    Route::get('settings/profile', Profile::class)->name('profile');
});


require __DIR__.'/auth.php';