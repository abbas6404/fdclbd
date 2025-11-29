<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get accounts and treasury accounts
        $incomeAccounts = DB::table('head_of_accounts')->where('account_type', 'income')->get();
        $expenseAccounts = DB::table('head_of_accounts')->where('account_type', 'expense')->get();
        $treasuryAccounts = DB::table('treasury_accounts')->get();
        $userId = 1; // Super Admin

        if ($incomeAccounts->isEmpty() || $expenseAccounts->isEmpty() || $treasuryAccounts->isEmpty()) {
            $this->command->warn('Required accounts or treasury accounts not found. Please run ChartOfAccountsSeeder and TreasuryAccountSeeder first.');
            return;
        }

        $cashAccount = $treasuryAccounts->where('account_type', 'cash')->first();
        $bankAccount = $treasuryAccounts->where('account_type', 'bank')->first() ?? $cashAccount;

        // Generate data for the last 3 months
        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now();

        $this->command->info('Creating test data for reports...');

        // Create Debit Vouchers (Expenses)
        $this->createDebitVouchers($expenseAccounts, $treasuryAccounts, $userId, $startDate, $endDate);

        // Create Credit Vouchers (Income)
        $this->createCreditVouchers($incomeAccounts, $treasuryAccounts, $userId, $startDate, $endDate);

        // Create Journal Entries
        $this->createJournalEntries($incomeAccounts, $expenseAccounts, $userId, $startDate, $endDate);

        // Create Contra Entries
        if ($bankAccount && $cashAccount) {
            $this->createContraEntries($cashAccount, $bankAccount, $userId, $startDate, $endDate);
        }

        $this->command->info('Test data created successfully!');
        $this->command->info('Debit Vouchers: ' . DB::table('debit_vouchers')->count());
        $this->command->info('Credit Vouchers: ' . DB::table('credit_vouchers')->count());
        $this->command->info('Journal Entries: ' . DB::table('journal_entries')->count());
        $this->command->info('Contra Entries: ' . DB::table('contra_entries')->count());
    }

    private function createDebitVouchers($expenseAccounts, $treasuryAccounts, $userId, $startDate, $endDate)
    {
        // Get the highest voucher number to continue from
        $lastVoucher = DB::table('debit_vouchers')
            ->where('voucher_number', 'like', 'DV-%')
            ->orderByRaw('CAST(SUBSTRING(voucher_number, 4) AS UNSIGNED) DESC')
            ->first();
        
        $voucherNumber = $lastVoucher 
            ? (int) str_replace('DV-', '', $lastVoucher->voucher_number) 
            : 0;

        $expenseTypes = [
            'Office Rent', 'Utility Bills', 'Salaries', 'Office Supplies', 
            'Marketing Expenses', 'Travel Expenses', 'Maintenance', 'Insurance',
            'Professional Fees', 'Telephone Bills', 'Internet Bills', 'Printing & Stationery'
        ];

        for ($i = 0; $i < 30; $i++) {
            $voucherNumber++;
            $voucherDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );

            $expenseAccount = $expenseAccounts->random();
            $treasuryAccount = $treasuryAccounts->random();
            $amount = rand(5000, 500000); // 50 to 5000 in main currency (5000 to 500000 in paise)

            $voucherId = DB::table('debit_vouchers')->insertGetId([
                'voucher_number' => 'DV-' . str_pad($voucherNumber, 6, '0', STR_PAD_LEFT),
                'voucher_date' => $voucherDate,
                'remarks' => $expenseTypes[array_rand($expenseTypes)] . ' - ' . $voucherDate->format('M Y'),
                'total_amount' => $amount,
                'treasury_account_id' => $treasuryAccount->id,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $voucherDate,
                'updated_at' => $voucherDate,
            ]);

            DB::table('debit_voucher_items')->insert([
                'debit_voucher_id' => $voucherId,
                'head_of_account_id' => $expenseAccount->id,
                'amount' => $amount,
                'description' => $expenseTypes[array_rand($expenseTypes)],
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $voucherDate,
                'updated_at' => $voucherDate,
            ]);
        }
    }

    private function createCreditVouchers($incomeAccounts, $treasuryAccounts, $userId, $startDate, $endDate)
    {
        // Get the highest voucher number to continue from
        $lastVoucher = DB::table('credit_vouchers')
            ->where('voucher_number', 'like', 'CV-%')
            ->orderByRaw('CAST(SUBSTRING(voucher_number, 4) AS UNSIGNED) DESC')
            ->first();
        
        $voucherNumber = $lastVoucher 
            ? (int) str_replace('CV-', '', $lastVoucher->voucher_number) 
            : 0;

        $incomeTypes = [
            'Service Revenue', 'Consultation Fees', 'Product Sales', 'Rental Income',
            'Interest Income', 'Commission Income', 'Project Revenue', 'Maintenance Fees',
            'Subscription Revenue', 'Training Fees', 'License Fees', 'Other Income'
        ];

        for ($i = 0; $i < 25; $i++) {
            $voucherNumber++;
            $voucherDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );

            $incomeAccount = $incomeAccounts->random();
            $treasuryAccount = $treasuryAccounts->random();
            $amount = rand(10000, 1000000); // 100 to 10000 in main currency

            $voucherId = DB::table('credit_vouchers')->insertGetId([
                'voucher_number' => 'CV-' . str_pad($voucherNumber, 6, '0', STR_PAD_LEFT),
                'voucher_date' => $voucherDate,
                'remarks' => $incomeTypes[array_rand($incomeTypes)] . ' - ' . $voucherDate->format('M Y'),
                'total_amount' => $amount,
                'treasury_account_id' => $treasuryAccount->id,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $voucherDate,
                'updated_at' => $voucherDate,
            ]);

            DB::table('credit_voucher_items')->insert([
                'credit_voucher_id' => $voucherId,
                'head_of_account_id' => $incomeAccount->id,
                'amount' => $amount,
                'description' => $incomeTypes[array_rand($incomeTypes)],
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $voucherDate,
                'updated_at' => $voucherDate,
            ]);
        }
    }

    private function createJournalEntries($incomeAccounts, $expenseAccounts, $userId, $startDate, $endDate)
    {
        // Get the highest entry number to continue from
        $lastEntry = DB::table('journal_entries')
            ->where('entry_number', 'like', 'JV-%')
            ->orderByRaw('CAST(SUBSTRING(entry_number, 4) AS UNSIGNED) DESC')
            ->first();
        
        $entryNumber = $lastEntry 
            ? (int) str_replace('JV-', '', $lastEntry->entry_number) 
            : 0;

        $entryTypes = [
            'Accrued Expenses Adjustment', 'Depreciation Entry', 'Prepaid Expenses',
            'Accrued Income Adjustment', 'Bad Debt Provision', 'Inventory Adjustment',
            'Year End Adjustment', 'Revaluation Entry', 'Transfer Entry'
        ];

        for ($i = 0; $i < 15; $i++) {
            $entryNumber++;
            $entryDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );

            $debitAccount = $expenseAccounts->random();
            $creditAccount = $incomeAccounts->random();
            $amount = rand(5000, 200000);

            $entryId = DB::table('journal_entries')->insertGetId([
                'entry_number' => 'JV-' . str_pad($entryNumber, 6, '0', STR_PAD_LEFT),
                'entry_date' => $entryDate,
                'remarks' => $entryTypes[array_rand($entryTypes)] . ' - ' . $entryDate->format('M Y'),
                'total_amount' => $amount,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $entryDate,
                'updated_at' => $entryDate,
            ]);

            // Debit entry
            DB::table('journal_entry_debits')->insert([
                'journal_entry_id' => $entryId,
                'head_of_account_id' => $debitAccount->id,
                'amount' => $amount,
                'description' => 'Debit: ' . $entryTypes[array_rand($entryTypes)],
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $entryDate,
                'updated_at' => $entryDate,
            ]);

            // Credit entry (must equal debit)
            DB::table('journal_entry_credits')->insert([
                'journal_entry_id' => $entryId,
                'head_of_account_id' => $creditAccount->id,
                'amount' => $amount,
                'description' => 'Credit: ' . $entryTypes[array_rand($entryTypes)],
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $entryDate,
                'updated_at' => $entryDate,
            ]);
        }
    }

    private function createContraEntries($cashAccount, $bankAccount, $userId, $startDate, $endDate)
    {
        // Get the highest entry number to continue from
        $lastEntry = DB::table('contra_entries')
            ->where('entry_number', 'like', 'CT-%')
            ->orderByRaw('CAST(SUBSTRING(entry_number, 4) AS UNSIGNED) DESC')
            ->first();
        
        $entryNumber = $lastEntry 
            ? (int) str_replace('CT-', '', $lastEntry->entry_number) 
            : 0;

        $remarks = [
            'Cash deposit to bank', 'Bank withdrawal to cash', 'Fund transfer',
            'Cash to bank transfer', 'Bank to cash transfer'
        ];

        for ($i = 0; $i < 10; $i++) {
            $entryNumber++;
            $entryDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );

            // Randomly decide direction: cash to bank or bank to cash
            $isCashToBank = rand(0, 1);
            $fromAccount = $isCashToBank ? $cashAccount : $bankAccount;
            $toAccount = $isCashToBank ? $bankAccount : $cashAccount;
            $amount = rand(10000, 500000);

            $entryId = DB::table('contra_entries')->insertGetId([
                'entry_number' => 'CT-' . str_pad($entryNumber, 6, '0', STR_PAD_LEFT),
                'entry_date' => $entryDate,
                'remarks' => $remarks[array_rand($remarks)] . ' - ' . $entryDate->format('M Y'),
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $entryDate,
                'updated_at' => $entryDate,
            ]);

            // Debit entry (FROM account)
            DB::table('contra_entry_items')->insert([
                'contra_entry_id' => $entryId,
                'treasury_account_id' => $fromAccount->id,
                'entry_type' => 'debit',
                'amount' => $amount,
                'description' => 'From: ' . $fromAccount->account_name,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $entryDate,
                'updated_at' => $entryDate,
            ]);

            // Credit entry (TO account)
            DB::table('contra_entry_items')->insert([
                'contra_entry_id' => $entryId,
                'treasury_account_id' => $toAccount->id,
                'entry_type' => 'credit',
                'amount' => $amount,
                'description' => 'To: ' . $toAccount->account_name,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $entryDate,
                'updated_at' => $entryDate,
            ]);
        }
    }
}

