<?php

use Illuminate\Support\Facades\Route;
use Secondnetwork\Kompass\Livewire\Frontend\Pageview;

/*
|--------------------------------------------------------------------------
| Web Routes naffd
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['web']], function () {
  Route::get('/', Pageview::class)->name('is_front_page');
  Route::get('/{slug}', Pageview::class)->name('pages');
});