<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Blade directive for role-based authorization
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$role})): ?>";
        });
        
        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });
        
        // Blade directive for permission-based authorization
        Blade::directive('permission', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->hasPermissionTo({$permission})): ?>";
        });
        
        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });
        
        // Blade directive for any role from a list
        Blade::directive('hasanyrole', function ($roles) {
            return "<?php if(auth()->check() && auth()->user()->hasAnyRole({$roles})): ?>";
        });
        
        Blade::directive('endhasanyrole', function () {
            return "<?php endif; ?>";
        });
        
        // Blade directive for all roles
        Blade::directive('hasallroles', function ($roles) {
            return "<?php if(auth()->check() && auth()->user()->hasAllRoles({$roles})): ?>";
        });
        
        Blade::directive('endhasallroles', function () {
            return "<?php endif; ?>";
        });
    }
}
