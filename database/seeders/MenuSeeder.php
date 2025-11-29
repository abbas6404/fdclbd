<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing menus
        Menu::truncate();

    // Level 1 - Reports
    $accountsEntryReport = Menu::create([
        'name' => 'Accounts Entry Report',
        'parent_id' => null,
        'route' => '',
        'permissions' => 'reports.accounts.entry',
        'status' => 'active',
    ]);

    // Level 2 - Sub Menus under Accounts Entry Report
    $debitVoucher = Menu::create([
        'name' => 'Debit Voucher',
        'parent_id' => $accountsEntryReport->id,
        'route' => 'admin.reports.accounts.debit-voucher',
        'print_url' => '/admin/reports/accounts/debit-voucher-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);  

    $creditVoucher = Menu::create([
        'name' => 'Credit Voucher',
        'parent_id' => $accountsEntryReport->id,
        'route' => 'admin.reports.accounts.credit-voucher',
        'print_url' => '/admin/reports/accounts/credit-voucher-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);

    $journalEntry = Menu::create([
        'name' => 'Journal Entry',
        'parent_id' => $accountsEntryReport->id,
        'route' => 'admin.reports.accounts.journal-entry',
        'print_url' => '/admin/reports/accounts/journal-entry-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);

    $contraEntry = Menu::create([
        'name' => 'Contra Entry',
        'parent_id' => $accountsEntryReport->id,
        'route' => 'admin.reports.accounts.contra-entry',
        'print_url' => '/admin/reports/accounts/contra-entry-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);

    $accountsReportLevelWise = Menu::create([
        'name' => 'Accounts Report Level Wise',
        'parent_id' => $accountsEntryReport->id,
        'route' => '',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);

    $accountsReportLevel1 = Menu::create([
        'name' => 'Accounts Report Level 1',
        'parent_id' => $accountsReportLevelWise->id,
        'route' => 'admin.reports.accounts.level-1',
        'print_url' => '/admin/reports/accounts/level-1-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);
    $accountsReportLevel2 = Menu::create([
        'name' => 'Accounts Report Level 2',
        'parent_id' => $accountsReportLevelWise->id,
        'route' => 'admin.reports.accounts.level-2',
        'print_url' => '/admin/reports/accounts/level-2-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);
    $accountsReportLevel3 = Menu::create([
        'name' => 'Accounts Report Level 3',
        'parent_id' => $accountsReportLevelWise->id,
        'route' => 'admin.reports.accounts.level-3',
        'print_url' => '/admin/reports/accounts/level-3-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);
    $accountsReportLevel4 = Menu::create([
        'name' => 'Accounts Report Level 4',
        'parent_id' => $accountsReportLevelWise->id,
        'route' => 'admin.reports.accounts.level-4',
        'print_url' => '/admin/reports/accounts/level-4-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);

  
    // Level 2 - Sub Menus under Accounts Entry Report
    $debitVoucher = Menu::create([
        'name' => 'Debit Voucher Details',
        'parent_id' => $debitVoucher->id,
        'route' => 'admin.reports.accounts.debit-voucher-details',
        'print_url' => '/admin/reports/accounts/debit-voucher-details-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);

    $creditVoucher = Menu::create([
        'name' => 'Credit Voucher Details',
        'parent_id' => $creditVoucher->id,
        'route' => 'admin.reports.accounts.credit-voucher-details',
        'print_url' => '/admin/reports/accounts/credit-voucher-details-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);

    $journalEntry = Menu::create([  
        'name' => 'Journal Entry Details',
        'parent_id' => $journalEntry->id,
        'route' => 'admin.reports.accounts.journal-entry-details',
        'print_url' => '/admin/reports/accounts/journal-entry-details-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);

    $contraEntry = Menu::create([
        'name' => 'Contra Entry Details',
        'parent_id' => $contraEntry->id,
        'route' => 'admin.reports.accounts.contra-entry-details',
        'print_url' => '/admin/reports/accounts/contra-entry-details-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);

 



    // Level 1 - Income Report
    $paymentsReceivedReport = Menu::create([
        'name' => 'Payments Received Report',
        'parent_id' => null,
        'route' => '',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);

    $paymentsReceivedDetails = Menu::create([
        'name' => 'Payments Received Summary',
        'parent_id' => $paymentsReceivedReport->id,
        'route' => 'admin.reports.payments_received.summary',
        'print_url' => '/admin/reports/payments_received/summary-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);

    $paymentsReceivedSummary = Menu::create([
        'name' => 'Payments Received Details',
        'parent_id' => $paymentsReceivedReport->id,
        'route' => 'admin.reports.payments_received.details',  
        'print_url' => '/admin/reports/payments_received/details-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);
    $paymentsReceivedChequeDetails = Menu::create([
        'name' => 'Payments Received Cheque Details',
        'parent_id' => $paymentsReceivedReport->id,
        'route' => 'admin.reports.payments_received.cheque-details',
        'print_url' => '/admin/reports/payments_received/cheque-details-print',
        'permissions' => 'reports.accounts',
        'status' => 'active',
    ]);







        $this->command->info('Menu seeder completed successfully!');
        $this->command->info('Total menus created: ' . Menu::count());
    }
}
