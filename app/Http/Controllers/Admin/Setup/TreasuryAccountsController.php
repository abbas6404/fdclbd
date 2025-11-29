<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TreasuryAccount;

class TreasuryAccountsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'permission:setup.treasury-accounts']);
    }

    /**
     * Display the treasury accounts setup page.
     */
    public function index()
    {
        $treasuryAccounts = TreasuryAccount::orderBy('account_type')
            ->orderBy('account_name')
            ->get();
        
        // Statistics
        $stats = [
            'total_accounts' => TreasuryAccount::count(),
            'cash_accounts' => TreasuryAccount::where('account_type', 'cash')->count(),
            'bank_accounts' => TreasuryAccount::where('account_type', 'bank')->count(),
            'active_accounts' => TreasuryAccount::where('status', 'active')->count(),
            'total_balance' => TreasuryAccount::where('status', 'active')->sum('current_balance'),
        ];
        
        return view('admin.setup.treasury-accounts.index', compact(
            'treasuryAccounts',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new treasury account.
     */
    public function create()
    {
        return view('admin.setup.treasury-accounts.create');
    }

    /**
     * Store a newly created treasury account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:cash,bank',
            'bank_name' => 'nullable|string|max:255|required_if:account_type,bank',
            'account_number' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'opening_balance' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            // Convert opening balance to paise (multiply by 100)
            $openingBalance = $request->opening_balance ? (int)($request->opening_balance * 100) : 0;

            TreasuryAccount::create([
                'account_name' => $request->account_name,
                'account_type' => $request->account_type,
                'bank_name' => $request->account_type === 'bank' ? $request->bank_name : null,
                'account_number' => $request->account_type === 'bank' ? $request->account_number : null,
                'branch_name' => $request->account_type === 'bank' ? $request->branch_name : null,
                'opening_balance' => $openingBalance,
                'current_balance' => $openingBalance, // Set current balance same as opening balance initially
                'status' => $request->status,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('admin.setup.treasury-accounts.index')
                ->with('success', 'Treasury account created successfully!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating treasury account: ' . $e->getMessage());
        }
    }
}

