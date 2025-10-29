<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;





// Auth Routes
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('post-register', [AuthController::class, 'postRegister'])->name('register.post');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


// Route Groups
Route::middleware('auth')->group(function(){
    // Route Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Route Resources
    Route::resource('product', ProductController::class);
    Route::resource('category', CategoryController::class);
});
