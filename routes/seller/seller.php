<?php

use App\Http\Controllers\Seller\ClickController;
use App\Http\Controllers\Seller\ExternalTrafficController;
use App\Http\Controllers\Seller\SellerController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/seller', [SellerController::class, 'index'])->name('seller.index');
    Route::get('/seller/create', [SellerController::class, 'create'])->name('seller.create');
    Route::post('/seller', [SellerController::class, 'store'])->name('seller.store');
    Route::get('/seller/{seller}', [SellerController::class, 'show'])->name('seller.show');
    Route::get('/seller/{seller}/edit', [SellerController::class, 'edit'])->name('seller.edit');
    Route::put('/seller/{seller}', [SellerController::class, 'update'])->name('seller.update');
    Route::delete('/seller/{seller}', [SellerController::class, 'destroy'])->name('seller.destroy');


    Route::get('/clicks/{seller?}', [ClickController::class, 'index'])->name('clicks.index');
    Route::get('/clicks/{seller?}/export', [ClickController::class, 'exportCsv'])->name('clicks.export');

    Route::get('/seller/{seller}/external-traffic', [ExternalTrafficController::class, 'index'])->name('seller.external-traffic.index');
    Route::get('/seller/{seller}/external-traffic/create', [ExternalTrafficController::class, 'create'])->name('seller.external-traffic.create');
    Route::post('/seller/{seller}/external-traffic', [ExternalTrafficController::class, 'store'])->name('seller.external-traffic.store');
    Route::get('/seller/{seller}/external-traffic-export', [ExternalTrafficController::class, 'exportCsv'])->name('seller.external-traffic.export');
});
