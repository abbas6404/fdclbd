<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JournalEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample accounts
        $expenseAccount = DB::table('head_of_accounts')->where('account_type', 'expense')->first();
        $incomeAccount = DB::table('head_of_accounts')->where('account_type', 'income')->first();

        if (!$expenseAccount || !$incomeAccount) {
            $this->command->warn('Head of accounts not found. Skipping journal entry seeding.');
            return;
        }

        // Create sample journal entry
        $entryId = DB::table('journal_entries')->insertGetId([
            'entry_number' => 'JV-000001',
            'entry_date' => Carbon::now()->subDays(1),
            'remarks' => 'Adjustment entry for accrued expenses',
            'total_amount' => 10000,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create debit entry
        DB::table('journal_entry_debits')->insert([
            'journal_entry_id' => $entryId,
            'head_of_account_id' => $expenseAccount->id,
            'amount' => 10000,
            'description' => 'Accrued expenses',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create credit entry (must equal debit)
        DB::table('journal_entry_credits')->insert([
            'journal_entry_id' => $entryId,
            'head_of_account_id' => $incomeAccount->id,
            'amount' => 10000,
            'description' => 'Accrued income',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Journal entries seeded successfully!');
        $this->command->info('Total journal entries created: ' . DB::table('journal_entries')->count());
    }
}
