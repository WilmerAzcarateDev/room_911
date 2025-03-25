<?php

use App\Http\Controllers\web\UserController;
use App\Http\Controllers\api\UserController as ApiUser;
use App\Http\Controllers\api\ProductionDepartamentController as ApiProductionDepartament;
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
            Route::post('',[UserController::class,'store'])->name('web.users.store');
            Route::post('mass_create',[UserController::class,'mass_store'])->name('web.users.mass_store');
            Route::put('{user}',[UserController::class,'update'])->name('web.users.update');
            Route::delete('{user}',[UserController::class,'destroy'])->name('web.users.destroy');
        });
    });

    Route::prefix('api')->group(function(){
        Route::apiResource('users',ApiUser::class);
        Route::prefix('users')->group(function(){
            Route::get('{user}/latest_logins',[ApiUser::class,'latest_login_attempts'])->name('users.latest_logins');
            Route::get('{user}/download/latest_logins',[ApiUser::class,'download_login_attempts'])->name('users.download.latest_logins');
        });
        Route::apiResource('production-departaments',ApiProductionDepartament::class)->only('index');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
