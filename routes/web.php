<?php

use App\Http\Controllers\web\UserController;
use App\Http\Controllers\api\UserController as ApiUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Auth::check() 
        ? redirect()->route('web.users.index')
        : redirect()->route('login');
})->name('home');

Route::middleware('auth')->group(function (){
    Route::prefix('admin')->group(function(){
        Route::prefix('users')->group(function(){
            Route::get('',[UserController::class,'index'])->name('web.users.index');
        });
    });

    Route::prefix('api')->group(function(){
        Route::apiResource('users',ApiUser::class);
        Route::prefix('users')->group(function(){
            Route::get('{user}/latest_logins',[ApiUser::class,'latest_login_attempts'])->name('users.latest_logins');
        });
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
