<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {return inertia('Auth/Login');});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {  return inertia('Home/Index'); });
    Route::resource('users', App\Http\Controllers\UserController::class);
});

require __DIR__.'/auth.php';