<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContraEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get treasury accounts
        $cashAccount = DB::table('treasury_accounts')->where('account_type', 'cash')->first();
        $bankAccount = DB::table('treasury_accounts')->where('account_type', 'bank')->first();

        if (!$cashAccount || !$bankAccount) {
            $this->command->warn('Treasury accounts not found. Skipping contra entry seeding.');
            return;
        }

        // Create sample contra entry - Cash to Bank
        $entryId = DB::table('contra_entries')->insertGetId([
            'entry_number' => 'CT-000001',
            'entry_date' => Carbon::now()->subDays(1),
            'remarks' => 'Transfer cash to bank for deposit',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create contra entry items - Cash to Bank
        // Debit item (FROM account - Cash)
        DB::table('contra_entry_items')->insert([
            'contra_entry_id' => $entryId,
            'treasury_account_id' => $cashAccount->id,
            'entry_type' => 'debit',
            'amount' => 20000,
            'description' => 'Transfer from Main Cash to SBI Account',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Credit item (TO account - Bank)
        DB::table('contra_entry_items')->insert([
            'contra_entry_id' => $entryId,
            'treasury_account_id' => $bankAccount->id,
            'entry_type' => 'credit',
            'amount' => 20000,
            'description' => 'Transfer from Main Cash to SBI Account',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create another contra entry - Bank to Cash
        $entryId2 = DB::table('contra_entries')->insertGetId([
            'entry_number' => 'CT-000002',
            'entry_date' => Carbon::now(),
            'remarks' => 'Withdrawal from bank to cash',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create contra entry items - Bank to Cash
        // Debit item (FROM account - Bank)
        DB::table('contra_entry_items')->insert([
            'contra_entry_id' => $entryId2,
            'treasury_account_id' => $bankAccount->id,
            'entry_type' => 'debit',
            'amount' => 5000,
            'description' => 'Withdrawal from SBI Account to Main Cash',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Credit item (TO account - Cash)
        DB::table('contra_entry_items')->insert([
            'contra_entry_id' => $entryId2,
            'treasury_account_id' => $cashAccount->id,
            'entry_type' => 'credit',
            'amount' => 5000,
            'description' => 'Withdrawal from SBI Account to Main Cash',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Contra entries seeded successfully!');
        $this->command->info('Total contra entries created: ' . DB::table('contra_entries')->count());
    }
}
