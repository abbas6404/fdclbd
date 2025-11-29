<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Guest\AboutController;
use App\Http\Controllers\Guest\ContactController;
use App\Http\Controllers\User\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect all traffic to login page
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Add home route that redirects to admin dashboard
Route::get('/home', function () {
    return redirect()->route('admin.dashboard');
})->name('home');

// Redirect any other routes to admin dashboard
Route::fallback(function () {
    return redirect()->route('admin.dashboard');
});

// Remove all guest routes
// Route::get('/about', [AboutController::class, 'index'])->name('about');
// Route::get('/contact', [ContactController::class, 'index'])->name('contact');
// Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');


