<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {return inertia('Welcome');});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {  return inertia('Home/Index'); });
    Route::get('/pdf', function () {  return inertia('Modules/PDF/Viewer'); });
    
    Route::resource('dtr', App\Http\Controllers\Modules\DtrController::class);
    Route::resource('application', App\Http\Controllers\Modules\RequestController::class);
    Route::resource('documents', App\Http\Controllers\Modules\DocumentController::class);
    Route::resource('employees', App\Http\Controllers\Modules\EmployeeController::class);

    // Route::resource('employees', App\Http\Controllers\UserController::class);
    // Route::post('/employee/import', [App\Http\Controllers\ImportController::class, 'index']);
    // Route::post('/employee/store', [App\Http\Controllers\ImportController::class, 'store']);
});

require __DIR__.'/auth.php';