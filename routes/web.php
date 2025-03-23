<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('login');
})->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function (){
    Route::prefix('admin')->group(function(){
        Route::get('dashboard',function(){
            return Inertia::render('admin/dashboard');
        })->name('admin.dashboard');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
