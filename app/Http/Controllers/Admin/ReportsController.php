<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\DebitVoucher;
use App\Models\CreditVoucher;
use App\Models\JournalEntry;
use App\Models\ContraEntry;
use App\Models\HeadOfAccount;
use App\Models\FlatSale;
use App\Models\TreasuryAccount;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request)
    {
        $menuId = $request->get('menu_id');
        $selectedMenu = null;
        
        // Get root menus (Level 1)
        $rootMenus = Menu::with('children')
            ->whereNull('parent_id')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Get sub menus (Level 2) - children of selected root menu
        $subMenus = collect();
        if ($menuId) {
            $selectedMenu = Menu::with(['parent', 'children'])->find($menuId);
            if ($selectedMenu) {
                // If selected menu is root, get its children
                if (!$selectedMenu->parent_id) {
                    $subMenus = Menu::with('children')
                        ->where('parent_id', $selectedMenu->id)
                        ->where('status', 'active')
                        ->orderBy('name')
                        ->get();
                } 
                // If selected menu has parent, get siblings
                elseif ($selectedMenu->parent) {
                    $subMenus = Menu::with('children')
                        ->where('parent_id', $selectedMenu->parent->id)
                        ->where('status', 'active')
                        ->orderBy('name')
                        ->get();
                }
            }
        }
        
        // Get sub sub menus (Level 3) - children of selected sub menu
        $subSubMenus = collect();
        if ($selectedMenu && $selectedMenu->parent_id) {
            // If selected menu is level 2, get its children
            if ($selectedMenu->parent && !$selectedMenu->parent->parent_id) {
                $subSubMenus = Menu::with('children')
                    ->where('parent_id', $selectedMenu->id)
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->get();
            }
            // If selected menu is level 3, get siblings
            elseif ($selectedMenu->parent && $selectedMenu->parent->parent_id) {
                $subSubMenus = Menu::with('children')
                    ->where('parent_id', $selectedMenu->parent->id)
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->get();
            }
        }
        
        // Get deep menus (Level 4+) - children of selected sub sub menu
        $deepMenus = collect();
        if ($selectedMenu) {
            $level = $selectedMenu->getLevel();
            if ($level >= 3) {
                $deepMenus = Menu::where('parent_id', $selectedMenu->id)
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->get();
            }
        }
        
        return view('admin.reports.index', compact(
            'rootMenus',
            'subMenus',
            'subSubMenus',
            'deepMenus',
            'selectedMenu'
        ));
    }

    /**
     * Get date range from request
     */
    private function getDateRange(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->toDateString());
        $endDate = $request->get('end_date', Carbon::today()->toDateString());
        
        return [
            'start' => Carbon::parse($startDate)->startOfDay(),
            'end' => Carbon::parse($endDate)->endOfDay(),
        ];
    }

    // ==================== INCOME REPORTS ====================

    public function incomeDetails(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        // Get income accounts transactions
        $incomeAccounts = HeadOfAccount::where('account_type', 'income')
            ->where('status', 'active')
            ->get();
        
        $transactions = collect();
        
        // Get from debit vouchers (expenses against income accounts)
        $debitItems = DB::table('debit_voucher_items')
            ->join('debit_vouchers', 'debit_voucher_items.debit_voucher_id', '=', 'debit_vouchers.id')
            ->join('head_of_accounts', 'debit_voucher_items.head_of_account_id', '=', 'head_of_accounts.id')
            ->where('head_of_accounts.account_type', 'income')
            ->whereBetween('debit_vouchers.voucher_date', [$dates['start'], $dates['end']])
            ->select('debit_vouchers.*', 'debit_voucher_items.*', 'head_of_accounts.account_name')
            ->get();
        
        // Get from credit vouchers (income received)
        $creditItems = DB::table('credit_voucher_items')
            ->join('credit_vouchers', 'credit_voucher_items.credit_voucher_id', '=', 'credit_vouchers.id')
            ->join('head_of_accounts', 'credit_voucher_items.head_of_account_id', '=', 'head_of_accounts.id')
            ->where('head_of_accounts.account_type', 'income')
            ->whereBetween('credit_vouchers.voucher_date', [$dates['start'], $dates['end']])
            ->select('credit_vouchers.*', 'credit_voucher_items.*', 'head_of_accounts.account_name')
            ->get();
        
        // Get from journal entries
        $journalCredits = DB::table('journal_entry_credits')
            ->join('journal_entries', 'journal_entry_credits.journal_entry_id', '=', 'journal_entries.id')
            ->join('head_of_accounts', 'journal_entry_credits.head_of_account_id', '=', 'head_of_accounts.id')
            ->where('head_of_accounts.account_type', 'income')
            ->whereBetween('journal_entries.entry_date', [$dates['start'], $dates['end']])
            ->select('journal_entries.*', 'journal_entry_credits.*', 'head_of_accounts.account_name')
            ->get();
        
        return view('admin.reports.income.details', compact('debitItems', 'creditItems', 'journalCredits', 'dates'));
    }

    public function incomeSummary(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        // Summary by account
        $summary = DB::table('credit_voucher_items')
            ->join('credit_vouchers', 'credit_voucher_items.credit_voucher_id', '=', 'credit_vouchers.id')
            ->join('head_of_accounts', 'credit_voucher_items.head_of_account_id', '=', 'head_of_accounts.id')
            ->where('head_of_accounts.account_type', 'income')
            ->whereBetween('credit_vouchers.voucher_date', [$dates['start'], $dates['end']])
            ->select('head_of_accounts.account_name', DB::raw('SUM(credit_voucher_items.amount) as total'))
            ->groupBy('head_of_accounts.id', 'head_of_accounts.account_name')
            ->get();
        
        $totalIncome = $summary->sum('total');
        
        return view('admin.reports.income.summary', compact('summary', 'totalIncome', 'dates'));
    }

    // ==================== EXPENSE REPORTS ====================

    public function expenseDetails(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        // Get expense accounts transactions
        $debitItems = DB::table('debit_voucher_items')
            ->join('debit_vouchers', 'debit_voucher_items.debit_voucher_id', '=', 'debit_vouchers.id')
            ->join('head_of_accounts', 'debit_voucher_items.head_of_account_id', '=', 'head_of_accounts.id')
            ->where('head_of_accounts.account_type', 'expense')
            ->whereBetween('debit_vouchers.voucher_date', [$dates['start'], $dates['end']])
            ->select('debit_vouchers.*', 'debit_voucher_items.*', 'head_of_accounts.account_name')
            ->get();
        
        $journalDebits = DB::table('journal_entry_debits')
            ->join('journal_entries', 'journal_entry_debits.journal_entry_id', '=', 'journal_entries.id')
            ->join('head_of_accounts', 'journal_entry_debits.head_of_account_id', '=', 'head_of_accounts.id')
            ->where('head_of_accounts.account_type', 'expense')
            ->whereBetween('journal_entries.entry_date', [$dates['start'], $dates['end']])
            ->select('journal_entries.*', 'journal_entry_debits.*', 'head_of_accounts.account_name')
            ->get();
        
        return view('admin.reports.expense.details', compact('debitItems', 'journalDebits', 'dates'));
    }

    public function expenseSummary(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $summary = DB::table('debit_voucher_items')
            ->join('debit_vouchers', 'debit_voucher_items.debit_voucher_id', '=', 'debit_vouchers.id')
            ->join('head_of_accounts', 'debit_voucher_items.head_of_account_id', '=', 'head_of_accounts.id')
            ->where('head_of_accounts.account_type', 'expense')
            ->whereBetween('debit_vouchers.voucher_date', [$dates['start'], $dates['end']])
            ->select('head_of_accounts.account_name', DB::raw('SUM(debit_voucher_items.amount) as total'))
            ->groupBy('head_of_accounts.id', 'head_of_accounts.account_name')
            ->get();
        
        $totalExpense = $summary->sum('total');
        
        return view('admin.reports.expense.summary', compact('summary', 'totalExpense', 'dates'));
    }

    // ==================== SALES REPORTS ====================

    public function salesDetails(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $sales = FlatSale::with(['customer', 'flat.project', 'salesAgent'])
            ->whereBetween('sale_date', [$dates['start'], $dates['end']])
            ->orderBy('sale_date', 'desc')
            ->get();
        
        return view('admin.reports.sales.details', compact('sales', 'dates'));
    }

    public function salesSummary(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $summary = FlatSale::whereBetween('sale_date', [$dates['start'], $dates['end']])
            ->select(
                DB::raw('COUNT(*) as total_sales'),
                DB::raw('SUM(CAST(net_price AS DECIMAL(15,2))) as total_amount'),
                DB::raw('AVG(CAST(net_price AS DECIMAL(15,2))) as avg_amount')
            )
            ->first();
        
        $salesByProject = FlatSale::join('project_flats', 'flat_sales.flat_id', '=', 'project_flats.id')
            ->join('projects', 'project_flats.project_id', '=', 'projects.id')
            ->whereBetween('flat_sales.sale_date', [$dates['start'], $dates['end']])
            ->select('projects.name as project_name', DB::raw('COUNT(*) as sales_count'), DB::raw('SUM(CAST(flat_sales.net_price AS DECIMAL(15,2))) as total'))
            ->groupBy('projects.id', 'projects.name')
            ->get();
        
        return view('admin.reports.sales.summary', compact('summary', 'salesByProject', 'dates'));
    }

    // ==================== FINANCIAL REPORTS ====================

    public function profitLoss(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        // Total Income
        $totalIncome = DB::table('credit_voucher_items')
            ->join('credit_vouchers', 'credit_voucher_items.credit_voucher_id', '=', 'credit_vouchers.id')
            ->join('head_of_accounts', 'credit_voucher_items.head_of_account_id', '=', 'head_of_accounts.id')
            ->where('head_of_accounts.account_type', 'income')
            ->whereBetween('credit_vouchers.voucher_date', [$dates['start'], $dates['end']])
            ->sum('credit_voucher_items.amount');
        
        // Total Expense
        $totalExpense = DB::table('debit_voucher_items')
            ->join('debit_vouchers', 'debit_voucher_items.debit_voucher_id', '=', 'debit_vouchers.id')
            ->join('head_of_accounts', 'debit_voucher_items.head_of_account_id', '=', 'head_of_accounts.id')
            ->where('head_of_accounts.account_type', 'expense')
            ->whereBetween('debit_vouchers.voucher_date', [$dates['start'], $dates['end']])
            ->sum('debit_voucher_items.amount');
        
        $netProfit = $totalIncome - $totalExpense;
        
        return view('admin.reports.financial.profit-loss', compact('totalIncome', 'totalExpense', 'netProfit', 'dates'));
    }

    public function balanceSheet(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        // Assets (Treasury Accounts)
        $assets = TreasuryAccount::where('status', 'active')
            ->sum('current_balance');
        
        // Liabilities and Equity
        $totalIncome = DB::table('credit_voucher_items')
            ->join('credit_vouchers', 'credit_voucher_items.credit_voucher_id', '=', 'credit_vouchers.id')
            ->join('head_of_accounts', 'credit_voucher_items.head_of_account_id', '=', 'head_of_accounts.id')
            ->where('head_of_accounts.account_type', 'income')
            ->whereBetween('credit_vouchers.voucher_date', [$dates['start'], $dates['end']])
            ->sum('credit_voucher_items.amount');
        
        $totalExpense = DB::table('debit_voucher_items')
            ->join('debit_vouchers', 'debit_voucher_items.debit_voucher_id', '=', 'debit_vouchers.id')
            ->join('head_of_accounts', 'debit_voucher_items.head_of_account_id', '=', 'head_of_accounts.id')
            ->where('head_of_accounts.account_type', 'expense')
            ->whereBetween('debit_vouchers.voucher_date', [$dates['start'], $dates['end']])
            ->sum('debit_voucher_items.amount');
        
        $equity = $totalIncome - $totalExpense;
        
        return view('admin.reports.financial.balance-sheet', compact('assets', 'equity', 'dates'));
    }

    public function cashFlow(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        // Cash Inflows (Credit Vouchers)
        $cashIn = CreditVoucher::whereBetween('voucher_date', [$dates['start'], $dates['end']])
            ->sum('total_amount');
        
        // Cash Outflows (Debit Vouchers)
        $cashOut = DebitVoucher::whereBetween('voucher_date', [$dates['start'], $dates['end']])
            ->sum('total_amount');
        
        $netCashFlow = $cashIn - $cashOut;
        
        return view('admin.reports.financial.cash-flow', compact('cashIn', 'cashOut', 'netCashFlow', 'dates'));
    }

    // ==================== ACCOUNTS REPORTS ====================

    public function debitVoucher(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $vouchers = DebitVoucher::with(['items.headOfAccount', 'treasuryAccount', 'createdBy'])
            ->whereBetween('voucher_date', [$dates['start'], $dates['end']])
            ->orderBy('voucher_date', 'desc')
            ->get();
        
        return view('admin.reports.accounts.debit-voucher', compact('vouchers', 'dates'));
    }

    public function creditVoucher(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $vouchers = CreditVoucher::with(['items.headOfAccount', 'treasuryAccount', 'createdBy'])
            ->whereBetween('voucher_date', [$dates['start'], $dates['end']])
            ->orderBy('voucher_date', 'desc')
            ->get();
        
        return view('admin.reports.accounts.credit-voucher', compact('vouchers', 'dates'));
    }

    public function journalEntry(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $entries = JournalEntry::with(['debits.headOfAccount', 'credits.headOfAccount', 'createdBy'])
            ->whereBetween('entry_date', [$dates['start'], $dates['end']])
            ->orderBy('entry_date', 'desc')
            ->get();
        
        return view('admin.reports.accounts.journal-entry', compact('entries', 'dates'));
    }

    public function contraEntry(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $entries = ContraEntry::with(['items.treasuryAccount', 'createdBy'])
            ->whereBetween('entry_date', [$dates['start'], $dates['end']])
            ->orderBy('entry_date', 'desc')
            ->get();
        
        return view('admin.reports.accounts.contra-entry', compact('entries', 'dates'));
    }
}
