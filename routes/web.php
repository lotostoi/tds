<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClickController;
use App\Models\Click;

Route::get('/', [ClickController::class, 'handle']);

Route::get('/clicks', [ClickController::class, 'index'])->name('clicks.index');
