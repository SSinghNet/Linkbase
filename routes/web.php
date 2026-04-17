<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\LinkRedirectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\YourBaseController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('yourbase', YourBaseController::class)
        ->name('yourbase');
    Route::get('analytics', AnalyticsController::class)
        ->name('analytics');
});

Route::get('/u/{username}', ProfileController::class)
    ->name('profile');

Route::get('/u/{username}/link/{linkId}', [LinkRedirectController::class, 'redirect'])
    ->name('link.redirect');

require __DIR__.'/settings.php';
