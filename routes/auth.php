<?php

use App\Http\Controllers\web\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login_form'])
        ->name('login');

    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function(){
    Route::post('logout',[AuthController::class,'logout'])->name('logout');
});