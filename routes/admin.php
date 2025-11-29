<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

// Real Estate Controllers
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\FlatManagementController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SalesAgentController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\FlatSaleController;
use App\Http\Controllers\Admin\PaymentScheduleController;
use App\Http\Controllers\Admin\PaymentReceiveController;
use App\Http\Controllers\Admin\ChequeManagementController;
use App\Http\Controllers\Admin\RequisitionController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ContractorController;
use App\Http\Controllers\Admin\PrintControllers\PaymentSchedules\PaymentScheduleController as PrintPaymentScheduleController;
use App\Http\Controllers\Admin\PrintControllers\PaymentInvoices\PaymentInvoiceController as PrintPaymentInvoiceController;

// Reports Controllers
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\Reports\Income\IncomeDetailsController;
use App\Http\Controllers\Admin\Reports\Income\IncomeSummaryController;
use App\Http\Controllers\Admin\Reports\Expense\ExpenseDetailsController;
use App\Http\Controllers\Admin\Reports\Expense\ExpenseSummaryController;
use App\Http\Controllers\Admin\Reports\Sales\SalesDetailsController;
use App\Http\Controllers\Admin\Reports\Sales\SalesSummaryController;
use App\Http\Controllers\Admin\Reports\Financial\ProfitLossController;
use App\Http\Controllers\Admin\Reports\Financial\BalanceSheetController;
use App\Http\Controllers\Admin\Reports\Financial\CashFlowController;
use App\Http\Controllers\Admin\Reports\Accounts\DebitVoucherController;
use App\Http\Controllers\Admin\Reports\Accounts\CreditVoucherController;
use App\Http\Controllers\Admin\Reports\Accounts\JournalEntryController;
use App\Http\Controllers\Admin\Reports\Accounts\ContraEntryController;
use App\Http\Controllers\Admin\Reports\Accounts\AccountsLevelController;
use App\Http\Controllers\Admin\Reports\PaymentsReceived\PaymentsReceivedController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" and "auth" middleware group.
|
*/

// Admin Dashboard - accessible to anyone with admin dashboard access
Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

// Admin-Only Dashboard - accessible only to Super Admin and Admin roles
Route::get('/admin-dashboard', [AdminController::class, 'adminDashboard'])->name('admin-dashboard');

// Real Estate Management Routes
Route::prefix('projects')->name('projects.')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/create', [ProjectController::class, 'create'])->name('create');
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
    Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
    Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
});

Route::prefix('project-flat')->name('project-flat.')->group(function () {
    Route::get('/', [FlatManagementController::class, 'index'])->name('index');
    Route::get('/create', [FlatManagementController::class, 'create'])->name('create');
    Route::post('/', [FlatManagementController::class, 'store'])->name('store');
    Route::get('/{flat}', [FlatManagementController::class, 'show'])->name('show');
    Route::get('/{flat}/edit', [FlatManagementController::class, 'edit'])->name('edit');
    Route::put('/{flat}', [FlatManagementController::class, 'update'])->name('update');
    Route::delete('/{flat}', [FlatManagementController::class, 'destroy'])->name('destroy');
});

// Flat Sales Routes
Route::prefix('flat-sales')->name('flat-sales.')->group(function () {
    Route::get('/', [FlatSaleController::class, 'index'])->name('index');
});

// Payment Schedule Routes
Route::prefix('payment-schedules')->name('payment-schedules.')->group(function () {
    Route::get('/', [PaymentScheduleController::class, 'index'])->name('index');
});

// Print Templates routes
Route::group(['prefix' => 'print-templates'], function() {
    Route::get('/payment-schedule', [PrintPaymentScheduleController::class, 'showPaymentSchedule'])->name('print-templates.payment-schedule');
    Route::get('/payment-invoice', [PrintPaymentInvoiceController::class, 'showPaymentInvoice'])->name('print-templates.payment-invoice');
});

// Payment Receive Routes
Route::prefix('payment-receive')->name('payment-receive.')->group(function () {
    Route::get('/', [PaymentReceiveController::class, 'index'])->name('index');
});

// Cheque Management Routes
Route::prefix('cheque-management')->name('cheque-management.')->group(function () {
    Route::get('/', [ChequeManagementController::class, 'index'])->name('index');
});

// Requisition Routes
Route::prefix('requisitions')->name('requisitions.')->group(function () {
    Route::get('/', [RequisitionController::class, 'index'])->name('index');
    Route::get('/confirm', [RequisitionController::class, 'confirm'])->name('confirm');
    Route::post('/{id}/approve', [RequisitionController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [RequisitionController::class, 'reject'])->name('reject');
});

// Account Entry Routes
Route::prefix('accounts')->name('accounts.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
});

Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index');
    Route::get('/create', [CustomerController::class, 'create'])->name('create');
    Route::post('/', [CustomerController::class, 'store'])->name('store');
    Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
    Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
    Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
    Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
});

Route::prefix('sales-agents')->name('sales-agents.')->group(function () {
    Route::get('/', [SalesAgentController::class, 'index'])->name('index');
    Route::get('/create', [SalesAgentController::class, 'create'])->name('create');
    Route::post('/', [SalesAgentController::class, 'store'])->name('store');
    Route::get('/{salesAgent}', [SalesAgentController::class, 'show'])->name('show');
    Route::get('/{salesAgent}/edit', [SalesAgentController::class, 'edit'])->name('edit');
    Route::put('/{salesAgent}', [SalesAgentController::class, 'update'])->name('update');
    Route::delete('/{salesAgent}', [SalesAgentController::class, 'destroy'])->name('destroy');
});

Route::prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('index');
    Route::get('/create', [SupplierController::class, 'create'])->name('create');
    Route::post('/', [SupplierController::class, 'store'])->name('store');
    Route::get('/{supplier}', [SupplierController::class, 'show'])->name('show');
    Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('edit');
    Route::put('/{supplier}', [SupplierController::class, 'update'])->name('update');
    Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('destroy');
});

Route::prefix('contractors')->name('contractors.')->group(function () {
    Route::get('/', [ContractorController::class, 'index'])->name('index');
    Route::get('/create', [ContractorController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [ContractorController::class, 'edit'])->name('edit');
});

// Reports Routes
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportsController::class, 'index'])->name('index');
    
    // Income Reports
    Route::prefix('income')->name('income.')->group(function () {
        Route::get('/details', [IncomeDetailsController::class, 'index'])->name('details');
        Route::get('/details-print', [IncomeDetailsController::class, 'index'])->name('details-print');
        Route::get('/summary', [IncomeSummaryController::class, 'index'])->name('summary');
        Route::get('/summary-print', [IncomeSummaryController::class, 'index'])->name('summary-print');
    });
    
    // Expense Reports
    Route::prefix('expense')->name('expense.')->group(function () {
        Route::get('/details', [ExpenseDetailsController::class, 'index'])->name('details');
        Route::get('/details-print', [ExpenseDetailsController::class, 'index'])->name('details-print');
        Route::get('/summary', [ExpenseSummaryController::class, 'index'])->name('summary');
        Route::get('/summary-print', [ExpenseSummaryController::class, 'index'])->name('summary-print');
    });
    
    // Sales Reports
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/details', [SalesDetailsController::class, 'index'])->name('details');
        Route::get('/details-print', [SalesDetailsController::class, 'index'])->name('details-print');
        Route::get('/summary', [SalesSummaryController::class, 'index'])->name('summary');
        Route::get('/summary-print', [SalesSummaryController::class, 'index'])->name('summary-print');
    });
    
    // Financial Reports
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/profit-loss', [ProfitLossController::class, 'index'])->name('profit-loss');
        Route::get('/profit-loss-print', [ProfitLossController::class, 'index'])->name('profit-loss-print');
        Route::get('/balance-sheet', [BalanceSheetController::class, 'index'])->name('balance-sheet');
        Route::get('/balance-sheet-print', [BalanceSheetController::class, 'index'])->name('balance-sheet-print');
        Route::get('/cash-flow', [CashFlowController::class, 'index'])->name('cash-flow');
        Route::get('/cash-flow-print', [CashFlowController::class, 'index'])->name('cash-flow-print');
    });
    
    // Accounts Reports
    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/debit-voucher', [DebitVoucherController::class, 'index'])->name('debit-voucher');
        Route::get('/debit-voucher-print', [DebitVoucherController::class, 'index'])->name('debit-voucher-print');
        Route::get('/debit-voucher-details-print', [DebitVoucherController::class, 'details'])->name('debit-voucher-details-print');
        Route::get('/credit-voucher', [CreditVoucherController::class, 'index'])->name('credit-voucher');
        Route::get('/credit-voucher-print', [CreditVoucherController::class, 'index'])->name('credit-voucher-print');
        Route::get('/credit-voucher-details-print', [CreditVoucherController::class, 'details'])->name('credit-voucher-details-print');
        Route::get('/journal-entry', [JournalEntryController::class, 'index'])->name('journal-entry');
        Route::get('/journal-entry-print', [JournalEntryController::class, 'index'])->name('journal-entry-print');
        Route::get('/journal-entry-details-print', [JournalEntryController::class, 'details'])->name('journal-entry-details-print');
        Route::get('/contra-entry', [ContraEntryController::class, 'index'])->name('contra-entry');
        Route::get('/contra-entry-print', [ContraEntryController::class, 'index'])->name('contra-entry-print');
        Route::get('/contra-entry-details-print', [ContraEntryController::class, 'details'])->name('contra-entry-details-print');
        Route::get('/level-1-print', [AccountsLevelController::class, 'level1'])->name('level-1-print');
        Route::get('/level-2-print', [AccountsLevelController::class, 'level2'])->name('level-2-print');
        Route::get('/level-3-print', [AccountsLevelController::class, 'level3'])->name('level-3-print');
        Route::get('/level-4-print', [AccountsLevelController::class, 'level4'])->name('level-4-print');
    });
    
    // Payments Received Reports
    Route::prefix('payments_received')->name('payments_received.')->group(function () {
        Route::get('/summary-print', [PaymentsReceivedController::class, 'summary'])->name('summary-print');
        Route::get('/details-print', [PaymentsReceivedController::class, 'details'])->name('details-print');
        Route::get('/cheque-details-print', [PaymentsReceivedController::class, 'chequeDetails'])->name('cheque-details-print');
    });
});

// Admin Profile routes
Route::get('/profile', [AdminController::class, 'showProfile'])->name('profile.index');
Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
Route::get('/profile/password', [AdminController::class, 'showChangePasswordForm'])->name('profile.password');
Route::put('/profile/password', [AdminController::class, 'updatePassword'])->name('profile.password.update');

// Admin Users Routes
use App\Http\Controllers\Admin\AdminUsersController;

Route::prefix('admin-users')->name('admin-users.')->group(function () {
    Route::get('/', [AdminUsersController::class, 'index'])->name('index');
    Route::get('/create', [AdminUsersController::class, 'create'])->name('create');
    Route::post('/', [AdminUsersController::class, 'store'])->name('store');
    Route::get('/{adminRecord}', [AdminUsersController::class, 'show'])->name('show');
    Route::get('/{adminRecord}/edit', [AdminUsersController::class, 'edit'])->name('edit');
    Route::put('/{adminRecord}', [AdminUsersController::class, 'update'])->name('update');
    Route::delete('/{adminRecord}', [AdminUsersController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restore', [AdminUsersController::class, 'restore'])->name('restore');
    Route::delete('/{id}/force-delete', [AdminUsersController::class, 'forceDelete'])->name('force-delete');
});

// Setup Routes
use App\Http\Controllers\Admin\Setup\SetupController;
use App\Http\Controllers\Admin\Setup\DisplaySettingsController;
use App\Http\Controllers\Admin\Setup\HeadOfAccountsController;
use App\Http\Controllers\Admin\Setup\TreasuryAccountsController;
use App\Http\Controllers\Admin\Setup\SystemSettingsController;
use App\Http\Controllers\Admin\Setup\RoleController;

Route::prefix('setup')->name('setup.')->group(function () {
    // Main setup page
    Route::get('/', [SetupController::class, 'index'])->name('index');
    
    // Role Setup
    Route::prefix('role')->name('role.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [RoleController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [RoleController::class, 'forceDelete'])->name('force-delete');
    });
    
    // Display Settings
    Route::prefix('display')->name('display.')->group(function () {
        Route::get('/', [DisplaySettingsController::class, 'index'])->name('index');
        Route::get('/edit', [DisplaySettingsController::class, 'edit'])->name('edit');
        Route::put('/update', [DisplaySettingsController::class, 'update'])->name('update');
    });
    
    // Head of Accounts
    Route::prefix('head-of-accounts')->name('head-of-accounts.')->group(function () {
        Route::get('/', [HeadOfAccountsController::class, 'index'])->name('index');
        Route::get('/create', [HeadOfAccountsController::class, 'create'])->name('create');
        Route::post('/', [HeadOfAccountsController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [HeadOfAccountsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [HeadOfAccountsController::class, 'update'])->name('update');
        Route::delete('/{id}', [HeadOfAccountsController::class, 'destroy'])->name('destroy');
    });
    
    // Treasury Accounts
    Route::prefix('treasury-accounts')->name('treasury-accounts.')->group(function () {
        Route::get('/', [TreasuryAccountsController::class, 'index'])->name('index');
        Route::get('/create', [TreasuryAccountsController::class, 'create'])->name('create');
        Route::post('/', [TreasuryAccountsController::class, 'store'])->name('store');
    });
    
    // System Settings
    Route::prefix('system-settings')->name('system-settings.')->group(function () {
        Route::get('/', [SystemSettingsController::class, 'index'])->name('index');
    });
}); 