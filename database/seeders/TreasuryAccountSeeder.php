<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TreasuryAccount;

class TreasuryAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing treasury accounts
        TreasuryAccount::query()->delete();

        // Create Cash Accounts
        TreasuryAccount::create([
            'account_name' => 'Main Cash',
            'account_type' => 'cash',
            'bank_name' => null,
            'account_number' => null,
            'branch_name' => null,
            'opening_balance' => 0,
            'current_balance' => 0,
            'status' => 'active',
            'created_by' => 1,
        ]);

        TreasuryAccount::create([
            'account_name' => 'Petty Cash',
            'account_type' => 'cash',
            'bank_name' => null,
            'account_number' => null,
            'branch_name' => null,
            'opening_balance' => 0,
            'current_balance' => 0,
            'status' => 'active',
            'created_by' => 1,
        ]);

        // Create Bank Accounts
        TreasuryAccount::create([
            'account_name' => 'SBI Account',
            'account_type' => 'bank',
            'bank_name' => 'State Bank of India',
            'account_number' => '123456789012',
            'branch_name' => 'Main Branch',
            'opening_balance' => 0,
            'current_balance' => 0,
            'status' => 'active',
            'created_by' => 1,
        ]);

        TreasuryAccount::create([
            'account_name' => 'HDFC Account',
            'account_type' => 'bank',
            'bank_name' => 'HDFC Bank',
            'account_number' => '987654321098',
            'branch_name' => 'City Branch',
            'opening_balance' => 0,
            'current_balance' => 0,
            'status' => 'active',
            'created_by' => 1,
        ]);

        TreasuryAccount::create([
            'account_name' => 'ICICI Account',
            'account_type' => 'bank',
            'bank_name' => 'ICICI Bank',
            'account_number' => '555555555555',
            'branch_name' => 'Corporate Branch',
            'opening_balance' => 0,
            'current_balance' => 0,
            'status' => 'active',
            'created_by' => 1,
        ]);

        $cashCount = TreasuryAccount::where('account_type', 'cash')->count();
        $bankCount = TreasuryAccount::where('account_type', 'bank')->count();

        $this->command->info('Treasury accounts seeded successfully!');
        $this->command->info("Total treasury accounts created: " . TreasuryAccount::count());
        $this->command->info("Cash accounts: {$cashCount}");
        $this->command->info("Bank accounts: {$bankCount}");
    }
}
