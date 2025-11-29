<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\HeadOfAccount;

class ChartOfAccountsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of chart of accounts.
     */
    public function index()
    {
        $accounts = HeadOfAccount::with(['parent', 'children', 'createdBy'])
            ->orderBy('account_code')
            ->get()
            ->groupBy('parent_id');

        // Ensure we have at least an empty collection for the view
        if ($accounts->isEmpty()) {
            $accounts = collect([]);
        }

        $stats = [
            'total_accounts' => HeadOfAccount::count(),
            'income_accounts' => HeadOfAccount::where('account_type', 'income')->count(),
            'expense_accounts' => HeadOfAccount::where('account_type', 'expense')->count(),
            'contra_accounts' => HeadOfAccount::where('account_type', 'contra')->count(),
            'active_accounts' => HeadOfAccount::where('status', 'active')->count(),
        ];

        return view('admin.accounts.chart-of-accounts.index', compact('accounts', 'stats'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create()
    {
        $parentAccounts = HeadOfAccount::where('status', 'active')
            ->orderBy('account_code')
            ->get();

        return view('admin.accounts.chart-of-accounts.create', compact('parentAccounts'));
    }

    /**
     * Store a newly created account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_code' => 'required|string|max:20|unique:head_of_accounts,account_code',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:income,expense,contra',
            'parent_id' => 'nullable|exists:head_of_accounts,id',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            HeadOfAccount::create([
                'account_code' => $request->account_code,
                'account_name' => $request->account_name,
                'account_type' => $request->account_type,
                'parent_id' => $request->parent_id,
                'status' => $request->status,
                'created_by' => Auth::id(),
            ]);

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', 'Account created successfully!');

            return redirect()->route('admin.accounts.chart-of-accounts.index');

        } catch (\Exception $e) {
            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Error creating account: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Display the specified account.
     */
    public function show($id)
    {
        $account = HeadOfAccount::with(['parent', 'children', 'createdBy', 'updatedBy'])
            ->findOrFail($id);

        return view('admin.accounts.chart-of-accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit($id)
    {
        $account = HeadOfAccount::findOrFail($id);
        $parentAccounts = HeadOfAccount::where('status', 'active')
            ->where('id', '!=', $id)
            ->orderBy('account_code')
            ->get();

        return view('admin.accounts.chart-of-accounts.edit', compact('account', 'parentAccounts'));
    }

    /**
     * Update the specified account.
     */
    public function update(Request $request, $id)
    {
        $account = HeadOfAccount::findOrFail($id);

        $request->validate([
            'account_code' => 'required|string|max:20|unique:head_of_accounts,account_code,' . $id,
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:income,expense,contra',
            'parent_id' => 'nullable|exists:head_of_accounts,id',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $account->update([
                'account_code' => $request->account_code,
                'account_name' => $request->account_name,
                'account_type' => $request->account_type,
                'parent_id' => $request->parent_id,
                'status' => $request->status,
                'updated_by' => Auth::id(),
            ]);

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', 'Account updated successfully!');

            return redirect()->route('admin.accounts.chart-of-accounts.index');

        } catch (\Exception $e) {
            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Error updating account: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified account.
     */
    public function destroy($id)
    {
        $account = HeadOfAccount::findOrFail($id);

        // Check if account has children
        if ($account->children()->count() > 0) {
            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Cannot delete account with sub-accounts. Please delete sub-accounts first.');
            return redirect()->route('admin.accounts.chart-of-accounts.index');
        }

        // Check if account is used in transactions
        $hasTransactions = $account->transactionDebits()->count() > 0 || 
                          $account->transactionCredits()->count() > 0 ||
                          $account->requisitionItems()->count() > 0;
        
        if ($hasTransactions) {
            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Cannot delete account that is used in transactions or requisitions.');
            return redirect()->route('admin.accounts.chart-of-accounts.index');
        }

        try {
            $account->delete();

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', 'Account deleted successfully!');

        } catch (\Exception $e) {
            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Error deleting account: ' . $e->getMessage());
        }

        return redirect()->route('admin.accounts.chart-of-accounts.index');
    }

    /**
     * Toggle account status.
     */
    public function toggleStatus($id)
    {
        $account = HeadOfAccount::findOrFail($id);

        try {
            $newStatus = $account->status === 'active' ? 'inactive' : 'active';
            $account->update([
                'status' => $newStatus,
                'updated_by' => Auth::id(),
            ]);

            session()->flash('alert_type', 'success');
            session()->flash('alert_message', 'Account status updated successfully!');

        } catch (\Exception $e) {
            session()->flash('alert_type', 'error');
            session()->flash('alert_message', 'Error updating account status: ' . $e->getMessage());
        }

        return redirect()->route('admin.accounts.chart-of-accounts.index');
    }
}
