<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClickController;

Route::get('/', [ClickController::class, 'handle']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/clicks', [ClickController::class, 'index'])->name('clicks.index');
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__.'/auth.php';
