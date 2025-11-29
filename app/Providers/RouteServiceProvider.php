<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest\Auth\LoginController;
use App\Http\Controllers\Guest\Auth\RegisterController;
use App\Http\Controllers\Guest\Auth\ForgotPasswordController;
use App\Http\Controllers\Guest\Auth\ResetPasswordController;
use App\Http\Controllers\Guest\Auth\ConfirmPasswordController;
use App\Http\Controllers\Guest\Auth\VerificationController;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/admin';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->mapAuthRoutes();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Admin routes with permission middleware
            Route::middleware(['web', 'auth', 'permission:system.dashboard'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            // User routes
            Route::middleware(['web', 'auth'])
                ->group(base_path('routes/user.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Define the Auth routes for the application.
     *
     * @return void
     */
    protected function mapAuthRoutes()
    {
        Route::middleware('web')
            ->group(function () {
                // Login Routes
                Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
                Route::post('login', [LoginController::class, 'login']);
                Route::post('logout', [LoginController::class, 'logout'])->name('logout');

                // Dashboard route for redirects
                Route::get('dashboard', function () {
                    if (Auth::check()) {
                        // Check if user has admin dashboard access
                        if (Auth::user()->hasPermissionTo('system.dashboard') || Auth::user()->hasRole(['Super Admin', 'Admin'])) {
                            return redirect()->route('admin.dashboard');
                        } else {
                            // Redirect to user dashboard if they have one
                            if (Route::has('user.dashboard')) {
                                return redirect()->route('user.dashboard');
                            }
                            // Fallback to admin dashboard (they might have access)
                            return redirect()->route('admin.dashboard');
                        }
                    }
                    return redirect()->route('login');
                })->name('dashboard');

                // Registration Routes - Commented out to disable registration
                // Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
                // Route::post('register', [RegisterController::class, 'register']);

                // Password Reset Routes
                Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
                Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
                Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
                Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

                // Password Confirmation Routes
                Route::get('password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
                Route::post('password/confirm', [ConfirmPasswordController::class, 'confirm']);

                // Email Verification Routes
                Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
                Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
                Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
            });
    }
} 