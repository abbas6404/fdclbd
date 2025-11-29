<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get all roles for Real Estate/Construction Company
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $salesManagerRole = Role::where('name', 'Sales Manager')->first();
        $salesAgentRole = Role::where('name', 'Sales Agent')->first();
        $accountantRole = Role::where('name', 'Accountant')->first();
        $projectManagerRole = Role::where('name', 'Project Manager')->first();
        $contractorRole = Role::where('name', 'Contractor')->first();
        $userRole = Role::where('name', 'User')->first();

        // Get the system.dashboard permission
        $dashboardPermission = Permission::where('name', 'system.dashboard')->first();

        if (!$dashboardPermission) {
            $this->command->error('system.dashboard permission not found!');
            return;
        }

        // Super Admin role - Has ALL permissions
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo(Permission::all());
            $this->command->info('Super Admin role assigned ALL permissions');
        }

        // Admin role - Has comprehensive management permissions
        if ($adminRole) {
            $adminRole->givePermissionTo([
                'system.dashboard',
                'admin.dashboard',
                'admin.dashboard.view',
                'setup.dashboard',
                'setup.role',
                'setup.role.view',
                'setup.role.create',
                'setup.role.edit',
                'setup.role.delete',
                'setup.role.restore',
                'setup.role.delete.permanent',
                'setup.display',
                'setup.display.view',
                'setup.display.edit',
                'setup.chart-of-accounts',
                'setup.chart-of-accounts.view',
                'setup.chart-of-accounts.create',
                'setup.chart-of-accounts.edit',
                'setup.chart-of-accounts.delete',
                'setup.treasury-accounts',
                'setup.treasury-accounts.view',
                'setup.treasury-accounts.create',
                'setup.system-settings',
                'setup.system-settings.view',
                'accounts',
                'accounts.voucher-posting',
                'accounts.voucher-posting.view',
                'accounts.voucher-posting.create',
                'users.dashboard',
            ]);
            $this->command->info('Admin role assigned comprehensive management permissions for real estate/construction company');
        }

        // Manager role - Has management permissions
        if ($managerRole) {
            $managerRole->givePermissionTo([
                'system.dashboard',
                'admin.dashboard',
                'admin.dashboard.view',
            ]);
            $this->command->info('Manager role assigned dashboard permissions');
        }

        // Sales Manager role - Has sales management permissions
        if ($salesManagerRole) {
            $salesManagerRole->givePermissionTo([
                'system.dashboard',
            ]);
            $this->command->info('Sales Manager role assigned dashboard permission');
        }

        // Sales Agent role - Has sales permissions
        if ($salesAgentRole) {
            $salesAgentRole->givePermissionTo([
                'system.dashboard',
            ]);
            $this->command->info('Sales Agent role assigned dashboard permission');
        }

        // Accountant role - Has accounting permissions
        if ($accountantRole) {
            $accountantRole->givePermissionTo([
                'system.dashboard',
                'accounts',
                'accounts.voucher-posting',
                'accounts.voucher-posting.view',
                'accounts.voucher-posting.create',
                'setup.chart-of-accounts',
                'setup.chart-of-accounts.view',
                'setup.treasury-accounts',
                'setup.treasury-accounts.view',
            ]);
            $this->command->info('Accountant role assigned accounting permissions');
        }

        // Project Manager role - Has project management permissions
        if ($projectManagerRole) {
            $projectManagerRole->givePermissionTo([
                'system.dashboard',
            ]);
            $this->command->info('Project Manager role assigned dashboard permission');
        }

        // Contractor role - Has limited permissions
        if ($contractorRole) {
            $contractorRole->givePermissionTo([
                'system.dashboard',
            ]);
            $this->command->info('Contractor role assigned dashboard permission');
        }

        // User role - Basic access
        if ($userRole) {
            $userRole->givePermissionTo([
                'system.dashboard',
            ]);
            $this->command->info('User role assigned dashboard permission');
        }

        $this->command->info('All roles assigned permissions successfully!');
    }
}
