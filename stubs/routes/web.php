<?php

use Illuminate\Support\Facades\Route;

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

require __DIR__.'/auth.php';

Route::group(['middleware' => ['web']], function (): void {
    Route::livewire('/', 'pages::page');
    Route::livewire('/blog', 'pages::blog.index');
    Route::livewire('/blog/{slug}', 'pages::blog.single');
    Route::livewire('/{slug}', 'pages::page');
});
