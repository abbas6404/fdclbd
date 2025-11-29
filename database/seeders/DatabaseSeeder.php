<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First, seed the role and permission system
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            RoleHasPermissionSeeder::class,
            SystemSettingsSeeder::class,
            UserSeeder::class,
            ChartOfAccountsSeeder::class,
            TreasuryAccountSeeder::class,
            DebitVoucherSeeder::class,
            CreditVoucherSeeder::class,
            JournalEntrySeeder::class,
            ContraEntrySeeder::class,
            MenuSeeder::class,
            ProjectSeeder::class,
            ProjectFlatSeeder::class,
            CustomerSeeder::class,
            SalesAgentSeeder::class,
            SupplierSeeder::class,
            ContractorSeeder::class,
            EmployeeSeeder::class,
            RequisitionSeeder::class,
            ReportTestDataSeeder::class,
        ]);
        
    }
}