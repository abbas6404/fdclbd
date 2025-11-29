<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register additional Blade directives for role and permission checking
        Blade::if('hasrole', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });
        
        Blade::if('hasanyrole', function ($roles) {
            return auth()->check() && auth()->user()->hasAnyRole($roles);
        });
        
        Blade::if('hasallroles', function ($roles) {
            return auth()->check() && auth()->user()->hasAllRoles($roles);
        });
        
        Blade::if('haspermission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermissionTo($permission);
        });
        
        Blade::if('hasanypermission', function ($permissions) {
            return auth()->check() && auth()->user()->hasAnyPermission($permissions);
        });
    }
}
