<?php

namespace Secondnetwork\Kompass;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Secondnetwork\Kompass\Http\Controllers\KompassController;
use Secondnetwork\Kompass\Livewire\AccountForm;
use Secondnetwork\Kompass\Livewire\BlocksData;
use Secondnetwork\Kompass\Livewire\BlocksTable;
use Secondnetwork\Kompass\Livewire\CategoryTable;
use Secondnetwork\Kompass\Livewire\Dashboard;
use Secondnetwork\Kompass\Livewire\Medialibrary;
use Secondnetwork\Kompass\Livewire\MenuData;
use Secondnetwork\Kompass\Livewire\MenuTable;
use Secondnetwork\Kompass\Livewire\PagesData;
use Secondnetwork\Kompass\Livewire\PagesTable;
use Secondnetwork\Kompass\Livewire\PostsData;
use Secondnetwork\Kompass\Livewire\PostsTable;
use Secondnetwork\Kompass\Livewire\Roles;
use Secondnetwork\Kompass\Livewire\Settings;
use Secondnetwork\Kompass\Livewire\Settings\Profile;
use Secondnetwork\Kompass\Livewire\UserDashboard;

// Asset Routes
Route::get('assets/{path?}', [KompassController::class, 'assets'])->name('kompass_asset');

// User Dashboard Route (for 'user' role - no admin access)
Route::group(['middleware' => ['web', 'auth', 'role:user'], 'prefix' => 'profile', 'as' => 'profile.'], function (): void {
    Route::get('/', UserDashboard::class)->name('dashboard');
});

Route::group(['middleware' => ['web', 'auth', 'role:admin|manager|editor'], 'prefix' => 'admin', 'as' => 'admin.'], function (): void {
    Route::get('/', Dashboard::class)->name('dashboard-root');
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('profile', Profile::class)->name('profile');

    Route::get('posts', PostsTable::class)->name('posts');
    Route::get('posts/{action}/{id}', PostsData::class)->name('posts.show');

    Route::get('categories', CategoryTable::class)->name('categories');

    Route::get('pages', PagesTable::class)->name('pages');
    Route::get('pages/{action}/{id}', PagesData::class)->name('pages.show');

    Route::get('medialibrary', Medialibrary::class)->name('medialibrary');

    Route::group(['middleware' => ['role:admin|manager']], function (): void {
        Route::get('menus', MenuTable::class)->name('menus');
        Route::get('menus/{action}/{id}', MenuData::class)->name('menus.show');
        Route::get('blocks', BlocksTable::class)->name('blocks');
        Route::get('blocks/{action}/{id}', BlocksData::class)->name('blocks.show');
        Route::get('settings', Settings::class)->name('settings');
    });

    Route::group(['middleware' => ['role:admin']], function (): void {
        Route::get('account', AccountForm::class)->name('account');
        Route::get('roles', Roles::class)->name('roles');
    });

    Route::view('about', 'kompass::admin.about')->name('about');
    Route::view('cd', 'kompass::admin.cd')->name('cd');
});

// include_once __DIR__.'/auth.php';
