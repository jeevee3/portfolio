<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VaultController;
use App\Http\Middleware\VaultUnlocked;

// ---------- Profile ----------
Route::get('/', [ProfileController::class, 'index'])->name('profile');
Route::get('/profile', [ProfileController::class, 'index']);

Route::post('/artworks', [ProfileController::class, 'store'])->name('artworks.store');
Route::delete('/artworks/{artwork}', [ProfileController::class, 'destroy'])->name('artworks.destroy');

// ---------- Vault: hidden trigger on the profile page ----------
Route::post('/ping', [VaultController::class, 'quick'])
    ->middleware('throttle:5,1')
    ->name('vault.quick');

// ---------- Vault: secret address ----------
if (config('vault.path')) {
    Route::prefix(config('vault.path'))->name('vault.')->group(function () {
        Route::get('/', [VaultController::class, 'loginForm'])->name('login');
        Route::post('/', [VaultController::class, 'attempt'])
            ->middleware('throttle:5,1')->name('attempt');

        Route::middleware(VaultUnlocked::class)->group(function () {
            Route::get('/arabella', [VaultController::class, 'home'])->name('home');
            Route::post('/upload', [VaultController::class, 'upload'])->name('upload');
            Route::get('/img/{item}', [VaultController::class, 'image'])->name('image');
            Route::delete('/item/{item}', [VaultController::class, 'delete'])->name('delete');
            Route::post('/lock', [VaultController::class, 'lock'])->name('lock');
        });
    });
}