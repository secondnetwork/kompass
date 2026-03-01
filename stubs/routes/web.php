<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

$localesData = setting('global.available_locales');
if ($localesData) {
    $locales = is_array($localesData) ? $localesData : json_decode($localesData, true);
} else {
    $locales = ['de', 'en', 'tr'];
}

$defaultLocale = $locales[0] ?? 'de';
$otherLocales = array_slice($locales, 1);
$localePattern = implode('|', $otherLocales);

Route::group(['middleware' => ['web']], function () use ($localePattern): void {
    Route::livewire('/', 'pages::page');
    
    Route::livewire('/blog', 'pages::blog.index');
    Route::livewire('/blog/{slug}', 'pages::blog.single');
    
    if (setting('global.multilingual') && $localePattern) {
        Route::livewire('/{locale}/blog', 'pages::blog.index')->where('locale', $localePattern);
        Route::livewire('/{locale}/blog/{slug}', 'pages::blog.single')->where('locale', $localePattern);
        Route::livewire('/{locale}/{slug}', 'pages::page')->where('locale', $localePattern);
        Route::livewire('/{locale}', 'pages::page')->where('locale', $localePattern);
    }
    
    Route::livewire('/{slug}', 'pages::page');
});
