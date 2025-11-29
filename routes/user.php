<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\OrderController;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Here is where you can register user routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" and "auth" middleware group.
|
*/

// Dashboard routes - COMMENTED OUT
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Profile routes - COMMENTED OUT
// Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
// Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
// Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
// Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');

// Order routes - COMMENTED OUT
// Route::get('/orders', [OrderController::class, 'index'])->name('orders');
// Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show'); 