<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('yourbase', 'yourbase')->name('yourbase');
    Route::view('analytics', 'analytics')->name('analytics');
});

require __DIR__.'/settings.php';
