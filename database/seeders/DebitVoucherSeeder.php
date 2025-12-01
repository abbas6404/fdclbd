<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DebitVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first treasury account (cash) and first expense account
        $treasuryAccount = DB::table('treasury_accounts')->where('account_type', 'cash')->first();
        $expenseAccount = DB::table('head_of_accounts')->where('account_type', 'expense')->first();

        if (!$treasuryAccount || !$expenseAccount) {
            $this->command->warn('Treasury accounts or head of accounts not found. Skipping debit voucher seeding.');
            return;
        }

        // Create sample debit vouchers
        $vouchers = [
            [
                'voucher_number' => 'DV-000001',
                'voucher_date' => Carbon::now()->subDays(5),
                'remarks' => 'Office supplies payment',
                'total_amount' => 5000,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'voucher_number' => 'DV-000002',
                'voucher_date' => Carbon::now()->subDays(3),
                'remarks' => 'Utility bill payment',
                'total_amount' => 8000,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($vouchers as $voucher) {
            $voucherId = DB::table('debit_vouchers')->insertGetId($voucher);

            // Create voucher items
            DB::table('debit_voucher_items')->insert([
                'debit_voucher_id' => $voucherId,
                'head_of_account_id' => $expenseAccount->id,
                'treasury_account_id' => $treasuryAccount->id,
                'amount' => $voucher['total_amount'],
                'description' => 'Payment for ' . $voucher['remarks'],
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Debit vouchers seeded successfully!');
        $this->command->info('Total debit vouchers created: ' . DB::table('debit_vouchers')->count());
    }
}
