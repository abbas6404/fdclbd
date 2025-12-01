<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreditVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first treasury account (cash) and first income account
        $treasuryAccount = DB::table('treasury_accounts')->where('account_type', 'cash')->first();
        $incomeAccount = DB::table('head_of_accounts')->where('account_type', 'income')->first();

        if (!$treasuryAccount || !$incomeAccount) {
            $this->command->warn('Treasury accounts or head of accounts not found. Skipping credit voucher seeding.');
            return;
        }

        // Create sample credit vouchers
        $vouchers = [
            [
                'voucher_number' => 'CV-000001',
                'voucher_date' => Carbon::now()->subDays(4),
                'remarks' => 'Service income received',
                'total_amount' => 15000,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'voucher_number' => 'CV-000002',
                'voucher_date' => Carbon::now()->subDays(2),
                'remarks' => 'Consultation fee received',
                'total_amount' => 12000,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($vouchers as $voucher) {
            $voucherId = DB::table('credit_vouchers')->insertGetId($voucher);

            // Create voucher items
            DB::table('credit_voucher_items')->insert([
                'credit_voucher_id' => $voucherId,
                'head_of_account_id' => $incomeAccount->id,
                'treasury_account_id' => $treasuryAccount->id,
                'amount' => $voucher['total_amount'],
                'description' => 'Income from ' . $voucher['remarks'],
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Credit vouchers seeded successfully!');
        $this->command->info('Total credit vouchers created: ' . DB::table('credit_vouchers')->count());
    }
}
