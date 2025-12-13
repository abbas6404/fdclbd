<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\PermissionGroup;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions with their groups and types for Real Estate/Construction Company
        $permissions = [
            // System Access Permission - Base admin access
            'system.dashboard' => ['group' => 'System Dashboard', 'type' => 'menu'],
            'system.dashboard.view' => ['group' => 'System Dashboard', 'type' => 'view'],
            'admin.dashboard' => ['group' => 'Admin Dashboard', 'type' => 'menu'],
            'admin.dashboard.view' => ['group' => 'Admin Dashboard', 'type' => 'view'],

            // Real Estate Management - Projects
            'projects' => ['group' => 'Projects', 'type' => 'menu'],
            'projects.view' => ['group' => 'Projects', 'type' => 'view'],
            'projects.create' => ['group' => 'Projects', 'type' => 'create'],
            'projects.edit' => ['group' => 'Projects', 'type' => 'edit'],
            'projects.delete' => ['group' => 'Projects', 'type' => 'delete'],

            // Real Estate Management - Flats
            'flats' => ['group' => 'Flats', 'type' => 'menu'],
            'flats.view' => ['group' => 'Flats', 'type' => 'view'],
            'flats.create' => ['group' => 'Flats', 'type' => 'create'],
            'flats.edit' => ['group' => 'Flats', 'type' => 'edit'],
            'flats.delete' => ['group' => 'Flats', 'type' => 'delete'],

            // Real Estate Management - Flat Sales
            'flat-sales' => ['group' => 'Flat Sales', 'type' => 'menu'],
            'flat-sales.view' => ['group' => 'Flat Sales', 'type' => 'view'],
            'flat-sales.create' => ['group' => 'Flat Sales', 'type' => 'create'],
            'flat-sales.edit' => ['group' => 'Flat Sales', 'type' => 'edit'],

            // Real Estate Management - Payment Schedules
            'payment-schedules' => ['group' => 'Payment Schedules', 'type' => 'menu'],
            'payment-schedules.view' => ['group' => 'Payment Schedules', 'type' => 'view'],
            'payment-schedules.create' => ['group' => 'Payment Schedules', 'type' => 'create'],
            'payment-schedules.edit' => ['group' => 'Payment Schedules', 'type' => 'edit'],

            // Real Estate Management - Payment Receive
            'payment-receive' => ['group' => 'Payment Receive', 'type' => 'menu'],
            'payment-receive.view' => ['group' => 'Payment Receive', 'type' => 'view'],
            'payment-receive.create' => ['group' => 'Payment Receive', 'type' => 'create'],

            // Real Estate Management - Cheque Management
            'cheque-management' => ['group' => 'Cheque Management', 'type' => 'menu'],
            'cheque-management.view' => ['group' => 'Cheque Management', 'type' => 'view'],
            'cheque-management.create' => ['group' => 'Cheque Management', 'type' => 'create'],
            'cheque-management.edit' => ['group' => 'Cheque Management', 'type' => 'edit'],

            // Real Estate Management - Customers
            'customers' => ['group' => 'Customers', 'type' => 'menu'],
            'customers.view' => ['group' => 'Customers', 'type' => 'view'],
            'customers.create' => ['group' => 'Customers', 'type' => 'create'],
            'customers.edit' => ['group' => 'Customers', 'type' => 'edit'],
            'customers.delete' => ['group' => 'Customers', 'type' => 'delete'],

            // Real Estate Management - Sales Agents
            'sales-agents' => ['group' => 'Sales Agents', 'type' => 'menu'],
            'sales-agents.view' => ['group' => 'Sales Agents', 'type' => 'view'],
            'sales-agents.create' => ['group' => 'Sales Agents', 'type' => 'create'],
            'sales-agents.edit' => ['group' => 'Sales Agents', 'type' => 'edit'],
            'sales-agents.delete' => ['group' => 'Sales Agents', 'type' => 'delete'],

            // Real Estate Management - Suppliers
            'suppliers' => ['group' => 'Suppliers', 'type' => 'menu'],
            'suppliers.view' => ['group' => 'Suppliers', 'type' => 'view'],
            'suppliers.create' => ['group' => 'Suppliers', 'type' => 'create'],
            'suppliers.edit' => ['group' => 'Suppliers', 'type' => 'edit'],
            'suppliers.delete' => ['group' => 'Suppliers', 'type' => 'delete'],

            // Real Estate Management - Contractors
            'contractors' => ['group' => 'Contractors', 'type' => 'menu'],
            'contractors.view' => ['group' => 'Contractors', 'type' => 'view'],
            'contractors.create' => ['group' => 'Contractors', 'type' => 'create'],
            'contractors.edit' => ['group' => 'Contractors', 'type' => 'edit'],

            // Real Estate Management - Requisitions
            'requisitions' => ['group' => 'Requisitions', 'type' => 'menu'],
            'requisitions.view' => ['group' => 'Requisitions', 'type' => 'view'],
            'requisitions.create' => ['group' => 'Requisitions', 'type' => 'create'],
            'requisitions.edit' => ['group' => 'Requisitions', 'type' => 'edit'],
            'requisitions.delete' => ['group' => 'Requisitions', 'type' => 'delete'],

            // Accounts Management
            'accounts' => ['group' => 'Accounts', 'type' => 'menu'],
            // Accounts Management - Voucher Posting
            'accounts.voucher-posting' => ['group' => 'Accounts Voucher Posting', 'type' => 'menu'],
            'accounts.voucher-posting.view' => ['group' => 'Accounts Voucher Posting', 'type' => 'view'],
            'accounts.voucher-posting.create' => ['group' => 'Accounts Voucher Posting', 'type' => 'create'],

            // Users Management - All Users Type
            'users.dashboard' => ['group' => 'Users Setup Dashboard', 'type' => 'menu'],

            // Users Management - Admin Type
            'users.admin' => ['group' => 'Users Admin (With Login Access)', 'type' => 'menu'],
            'users.admin.view' => ['group' => 'Users Admin (With Login Access)', 'type' => 'view'],
            'users.admin.create' => ['group' => 'Users Admin (With Login Access)', 'type' => 'create'],
            'users.admin.edit' => ['group' => 'Users Admin (With Login Access)', 'type' => 'edit'],
            'users.admin.delete' => ['group' => 'Users Admin (With Login Access)', 'type' => 'delete'],
            'users.admin.restore' => ['group' => 'Users Admin (With Login Access)', 'type' => 'restore'],
            'users.admin.delete.permanent' => ['group' => 'Users Admin (With Login Access)', 'type' => 'delete_permanent'],

            // Reports
            'reports' => ['group' => 'Reports', 'type' => 'menu'],
            'reports.view' => ['group' => 'Reports', 'type' => 'view'],
            'reports.income' => ['group' => 'Reports Income', 'type' => 'view'],
            'reports.expense' => ['group' => 'Reports Expense', 'type' => 'view'],
            'reports.sales' => ['group' => 'Reports Sales', 'type' => 'view'],
            'reports.financial' => ['group' => 'Reports Financial', 'type' => 'view'],

            // System Management
            // Setup Management Permissions - Dashboard
            'setup.dashboard' => ['group' => 'Setup Configuration Dashboard', 'type' => 'view'],

            // Setup Management Permissions - Role
            'setup.role' => ['group' => 'Setup Role For Login Access', 'type' => 'menu'],
            'setup.role.view' => ['group' => 'Setup Role For Login Access', 'type' => 'view'],
            'setup.role.create' => ['group' => 'Setup Role For Login Access', 'type' => 'create'],
            'setup.role.edit' => ['group' => 'Setup Role For Login Access', 'type' => 'edit'],
            'setup.role.delete' => ['group' => 'Setup Role For Login Access', 'type' => 'delete'],
            'setup.role.restore' => ['group' => 'Setup Role For Login Access', 'type' => 'restore'],
            'setup.role.delete.permanent' => ['group' => 'Setup Role For Login Access', 'type' => 'delete_permanent'],

            // Setup Management Permissions - Display Settings edit and view only
            'setup.display' => ['group' => 'Setup Display Settings', 'type' => 'menu'],
            'setup.display.view' => ['group' => 'Setup Display Settings', 'type' => 'view'],
            'setup.display.edit' => ['group' => 'Setup Display Settings', 'type' => 'edit'],

            // Accounts Management - Head of Accounts (Chart of Accounts)
            'setup.chart-of-accounts' => ['group' => 'Setup Head of Accounts', 'type' => 'menu'],
            'setup.chart-of-accounts.view' => ['group' => 'Setup Head of Accounts', 'type' => 'view'],
            'setup.chart-of-accounts.create' => ['group' => 'Setup Head of Accounts', 'type' => 'create'],
            'setup.chart-of-accounts.edit' => ['group' => 'Setup Head of Accounts', 'type' => 'edit'],
            'setup.chart-of-accounts.delete' => ['group' => 'Setup Head of Accounts', 'type' => 'delete'],
            'setup.chart-of-accounts.restore' => ['group' => 'Setup Head of Accounts', 'type' => 'restore'],
            'setup.chart-of-accounts.delete.permanent' => ['group' => 'Setup Head of Accounts', 'type' => 'delete_permanent'],

            // Setup Management Permissions - Treasury Accounts
            'setup.treasury-accounts' => ['group' => 'Setup Treasury Accounts', 'type' => 'menu'],
            'setup.treasury-accounts.view' => ['group' => 'Setup Treasury Accounts', 'type' => 'view'],
            'setup.treasury-accounts.create' => ['group' => 'Setup Treasury Accounts', 'type' => 'create'],
            'setup.treasury-accounts.edit' => ['group' => 'Setup Treasury Accounts', 'type' => 'edit'],
            'setup.treasury-accounts.delete' => ['group' => 'Setup Treasury Accounts', 'type' => 'delete'],
            'setup.treasury-accounts.restore' => ['group' => 'Setup Treasury Accounts', 'type' => 'restore'],
            'setup.treasury-accounts.delete.permanent' => ['group' => 'Setup Treasury Accounts', 'type' => 'delete_permanent'],

            // Setup Management Permissions - System Settings
            'setup.system-settings' => ['group' => 'Setup System Settings', 'type' => 'menu'],
            'setup.system-settings.view' => ['group' => 'Setup System Settings', 'type' => 'view'],
            'setup.system-settings.create' => ['group' => 'Setup System Settings', 'type' => 'create'],
            'setup.system-settings.edit' => ['group' => 'Setup System Settings', 'type' => 'edit'],
            'setup.system-settings.delete' => ['group' => 'Setup System Settings', 'type' => 'delete'],
            'setup.system-settings.restore' => ['group' => 'Setup System Settings', 'type' => 'restore'],
            'setup.system-settings.delete.permanent' => ['group' => 'Setup System Settings', 'type' => 'delete_permanent'],

            // Setup Management Permissions - Approval Levels
            'setup.approval-levels' => ['group' => 'Setup Approval Levels', 'type' => 'menu'],
            'setup.approval-levels.view' => ['group' => 'Setup Approval Levels', 'type' => 'view'],
            'setup.approval-levels.create' => ['group' => 'Setup Approval Levels', 'type' => 'create'],
            'setup.approval-levels.edit' => ['group' => 'Setup Approval Levels', 'type' => 'edit'],
            'setup.approval-levels.delete' => ['group' => 'Setup Approval Levels', 'type' => 'delete'],
        ];

        // Create groups dynamically and assign permissions
        $groups = [];
        foreach ($permissions as $permissionName => $permissionData) {
            $groupName = $permissionData['group'];
            $permissionType = $permissionData['type'];
            
            // Create group if it doesn't exist
            if (!isset($groups[$groupName])) {
                $group = PermissionGroup::firstOrCreate(
                    ['name' => $groupName],
                    [
                        'name' => $groupName,
                        'sort_order' => count($groups) + 1,
                        'status' => 'active'
                    ]
                );
                $groups[$groupName] = $group;
            }

            // Create permission and assign to group with type
            $permission = Permission::firstOrCreate(
                ['name' => $permissionName],
                [
                    'name' => $permissionName,
                    'type' => $permissionType,
                    'permission_group_id' => $groups[$groupName]->id
                ]
            );
            
            // Update existing permissions with type and group
            if (!$permission->wasRecentlyCreated) {
                $permission->update([
                    'type' => $permissionType,
                    'permission_group_id' => $groups[$groupName]->id
                ]);
            }
        }

        $this->command->info('Permissions seeded successfully!');
        $this->command->info('Total permissions created: ' . count($permissions));
        $this->command->info('Total groups created: ' . count($groups));
    }
}
