<?php

namespace App\Http\Controllers\Admin\Reports\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HeadOfAccount;
use App\Models\DebitVoucherItem;
use App\Models\CreditVoucherItem;
use App\Models\JournalEntryDebit;
use App\Models\JournalEntryCredit;
use Carbon\Carbon;

class AccountsLevelController extends Controller
{
    public function level1(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $accounts = HeadOfAccount::with(['parent'])
            ->where('account_level', '1')
            ->where('status', 'active')
            ->orderBy('account_name', 'asc')
            ->get();
        
        $processedAccounts = $this->processAccounts($accounts, $dates, true);
        
        return view('admin.reports.accounts.level-1-print', compact('processedAccounts', 'dates'));
    }

    public function level2(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $accounts = HeadOfAccount::with(['parent'])
            ->where('account_level', '2')
            ->where('status', 'active')
            ->orderBy('account_name', 'asc')
            ->get();
        
        $processedAccounts = $this->processAccounts($accounts, $dates, true);
        
        return view('admin.reports.accounts.level-2-print', compact('processedAccounts', 'dates'));
    }

    public function level3(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $accounts = HeadOfAccount::with(['parent'])
            ->where('account_level', '3')
            ->where('status', 'active')
            ->orderBy('account_name', 'asc')
            ->get();
        
        $processedAccounts = $this->processAccounts($accounts, $dates, false);
        
        return view('admin.reports.accounts.level-3-print', compact('processedAccounts', 'dates'));
    }

    public function level4(Request $request)
    {
        $dates = $this->getDateRange($request);
        
        $accounts = HeadOfAccount::with(['parent'])
            ->where('account_level', '4')
            ->where('status', 'active')
            ->orderBy('account_name', 'asc')
            ->get();
        
        $processedAccounts = $this->processAccounts($accounts, $dates, false, true);
        
        return view('admin.reports.accounts.level-4-print', compact('processedAccounts', 'dates'));
    }

    /**
     * Process accounts and calculate totals
     */
    private function processAccounts($accounts, $dates, $recursive = false, $direct = false)
    {
        $processed = [];
        
        foreach ($accounts as $account) {
            if ($direct) {
                // Level 4: Use account ID directly
                $level4Ids = [$account->id];
            } elseif ($recursive) {
                // Level 1 & 2: Get all level 4 descendants recursively
                $level4Ids = $this->getLevel4DescendantIds($account);
            } else {
                // Level 3: Get direct level 4 children only
                $level4Ids = HeadOfAccount::where('parent_id', $account->id)
                    ->where('account_level', '4')
                    ->pluck('id')
                    ->toArray();
            }
            
            // Calculate debits
            $debitVoucherTotal = DebitVoucherItem::whereIn('head_of_account_id', $level4Ids)
                ->whereHas('debitVoucher', function($q) use ($dates) {
                    $q->whereBetween('voucher_date', [$dates['start'], $dates['end']]);
                })
                ->sum('amount');
            
            $journalDebitTotal = JournalEntryDebit::whereIn('head_of_account_id', $level4Ids)
                ->whereHas('journalEntry', function($q) use ($dates) {
                    $q->whereBetween('entry_date', [$dates['start'], $dates['end']]);
                })
                ->sum('amount');
            
            $debitTotal = $debitVoucherTotal + $journalDebitTotal;
            
            // Calculate credits
            $creditVoucherTotal = CreditVoucherItem::whereIn('head_of_account_id', $level4Ids)
                ->whereHas('creditVoucher', function($q) use ($dates) {
                    $q->whereBetween('voucher_date', [$dates['start'], $dates['end']]);
                })
                ->sum('amount');
            
            $journalCreditTotal = JournalEntryCredit::whereIn('head_of_account_id', $level4Ids)
                ->whereHas('journalEntry', function($q) use ($dates) {
                    $q->whereBetween('entry_date', [$dates['start'], $dates['end']]);
                })
                ->sum('amount');
            
            $creditTotal = $creditVoucherTotal + $journalCreditTotal;
            
            // Only include accounts with transactions
            if ($debitTotal > 0 || $creditTotal > 0) {
                // Balance = Credit - Debit (positive when credit > debit)
                $balance = $creditTotal - $debitTotal;
                
                $processed[] = [
                    'account' => $account,
                    'debitTotal' => $debitTotal,
                    'creditTotal' => $creditTotal,
                    'balance' => $balance
                ];
            }
        }
        
        return $processed;
    }

    /**
     * Get all level 4 descendant account IDs for a given account
     */
    private function getLevel4DescendantIds($account)
    {
        $ids = [];
        
        // Get direct children
        $children = HeadOfAccount::where('parent_id', $account->id)->get();
        
        foreach ($children as $child) {
            // If child is level 4, add it
            if ($child->account_level == '4') {
                $ids[] = $child->id;
            } else {
                // Recursively get level 4 descendants
                $ids = array_merge($ids, $this->getLevel4DescendantIds($child));
            }
        }
        
        return $ids;
    }

    private function getDateRange(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->toDateString());
        $endDate = $request->get('end_date', Carbon::today()->toDateString());
        
        return [
            'start' => Carbon::parse($startDate)->startOfDay(),
            'end' => Carbon::parse($endDate)->endOfDay(),
        ];
    }
}

