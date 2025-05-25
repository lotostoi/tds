<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClickController;

Route::get('/', [ClickController::class, 'handle']);

// Route::get('/welcome', function () {
//     return view('welcome');
// });
