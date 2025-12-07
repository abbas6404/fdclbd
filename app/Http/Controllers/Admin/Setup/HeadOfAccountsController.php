<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HeadOfAccount;

class HeadOfAccountsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'permission:setup.chart-of-accounts']);
    }

    /**
     * Display the head of accounts setup page.
     */
    public function index()
    {
        // Get all accounts with relationships
        $allAccounts = HeadOfAccount::with('parent', 'children')
            ->orderBy('account_name')
            ->get();
        
        // Build hierarchical tree structure
        $accountsTree = $this->buildAccountTree($allAccounts);
        
        // Get parent accounts for create form
        $parentAccounts = HeadOfAccount::whereNull('parent_id')
            ->orderBy('account_name')
            ->get();
        
        // Statistics
        $stats = [
            'total_accounts' => HeadOfAccount::count(),
            'income_accounts' => HeadOfAccount::where('account_type', 'income')->count(),
            'expense_accounts' => HeadOfAccount::where('account_type', 'expense')->count(),
            'active_accounts' => HeadOfAccount::where('status', 'active')->count(),
        ];
        
        return view('admin.setup.head-of-accounts.index', compact(
            'accountsTree',
            'parentAccounts',
            'stats'
        ));
    }

    /**
     * Build hierarchical tree structure from flat account list.
     */
    private function buildAccountTree($accounts)
    {
        // Group accounts by parent_id
        $grouped = $accounts->groupBy('parent_id');
        
        // Get root accounts (no parent)
        $roots = $grouped->get(null, collect());
        
        // Build tree recursively
        $tree = collect();
        foreach ($roots->sortBy('account_name') as $root) {
            $tree->push($this->buildTreeItem($root, $grouped));
        }
        
        return $tree;
    }

    /**
     * Build a tree item with its children recursively.
     */
    private function buildTreeItem($account, $grouped, $level = 0)
    {
        // Check if account level matches its position in hierarchy
        $expectedLevel = $level + 1; // Level 1 = root, Level 2 = first child, etc.
        $actualLevel = (int)$account->account_level;
        $levelMismatch = $actualLevel != $expectedLevel;
        
        // Get children of this account
        $children = $grouped->get($account->id, collect());
        $hasChildren = $children->count() > 0;
        
        $item = [
            'account' => $account,
            'level' => $level,
            'expectedLevel' => $expectedLevel,
            'levelMismatch' => $levelMismatch,
            'children' => collect(),
            'hasChildren' => $hasChildren
        ];
        
        // Build children recursively
        foreach ($children->sortBy('account_name') as $child) {
            $item['children']->push($this->buildTreeItem($child, $grouped, $level + 1));
        }
        
        return $item;
    }

    /**
     * Show the form for creating a new head of account.
     */
    public function create()
    {
        // Get all accounts for parent selection (excluding deleted)
        $parentAccounts = HeadOfAccount::where('status', 'active')
            ->orderByRaw('CAST(account_level AS UNSIGNED) ASC')
            ->orderBy('account_name', 'ASC')
            ->get();
        
        return view('admin.setup.head-of-accounts.create', compact('parentAccounts'));
    }

    /**
     * Store a newly created head of account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:income,expense',
            'parent_id' => 'nullable|exists:head_of_accounts,id',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            // Calculate account level based on parent
            $accountLevel = '1';
            if ($request->parent_id) {
                $parent = HeadOfAccount::find($request->parent_id);
                if ($parent) {
                    $accountLevel = (string)((int)$parent->account_level + 1);
                }
            }

            HeadOfAccount::create([
                'account_name' => $request->account_name,
                'account_type' => $request->account_type,
                'parent_id' => $request->parent_id,
                'account_level' => $accountLevel,
                'status' => $request->status,
                'is_requisitions' => $request->has('is_requisitions') ? true : false,
                'is_boq' => $request->has('is_boq') ? true : false,
                'is_account' => $request->has('is_account') ? true : false,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('admin.setup.head-of-accounts.index')
                ->with('success', 'Head of account created successfully!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating head of account: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified head of account.
     */
    public function edit($id)
    {
        $account = HeadOfAccount::findOrFail($id);
        
        // Get all descendant IDs (to exclude from parent selection)
        $excludedIds = $this->getDescendantIds($account);
        $excludedIds[] = $id; // Also exclude the account itself
        
        // Get all accounts for parent selection (excluding current account, its descendants, and deleted)
        $parentAccounts = HeadOfAccount::where('status', 'active')
            ->whereNotIn('id', $excludedIds)
            ->orderByRaw('CAST(account_level AS UNSIGNED) ASC')
            ->orderBy('account_name', 'ASC')
            ->get();
        
        return view('admin.setup.head-of-accounts.edit', compact('account', 'parentAccounts'));
    }

    /**
     * Update the specified head of account.
     */
    public function update(Request $request, $id)
    {
        $account = HeadOfAccount::findOrFail($id);

        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:income,expense',
            'parent_id' => 'nullable|exists:head_of_accounts,id',
            'account_level' => 'nullable|in:1,2,3,4',
            'status' => 'required|in:active,inactive',
        ]);

        // Prevent setting parent to itself or its descendants
        if ($request->parent_id == $id) {
            return back()->withInput()
                ->with('error', 'An account cannot be its own parent.');
        }

        // Check if parent is a descendant (would create circular reference)
        if ($request->parent_id) {
            $parent = HeadOfAccount::find($request->parent_id);
            if ($parent && $this->isDescendantOf($parent, $account)) {
                return back()->withInput()
                    ->with('error', 'Cannot set parent to a descendant account.');
            }
        }

        try {
            // Use provided account level, or calculate based on parent
            $accountLevel = '1';
            if ($request->account_level) {
                // Use the manually provided level
                $accountLevel = (string)$request->account_level;
            } elseif ($request->parent_id) {
                // Calculate from parent if no level provided
                $parent = HeadOfAccount::find($request->parent_id);
                if ($parent) {
                    $accountLevel = (string)((int)$parent->account_level + 1);
                }
            } else {
                // No parent, default to level 1
                $accountLevel = '1';
            }

            $account->update([
                'account_name' => $request->account_name,
                'account_type' => $request->account_type,
                'parent_id' => $request->parent_id,
                'account_level' => $accountLevel,
                'status' => $request->status,
                'is_requisitions' => $request->has('is_requisitions') ? true : false,
                'is_boq' => $request->has('is_boq') ? true : false,
                'is_account' => $request->has('is_account') ? true : false,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('admin.setup.head-of-accounts.index')
                ->with('success', 'Head of account updated successfully!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating head of account: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified head of account.
     */
    public function destroy($id)
    {
        $account = HeadOfAccount::findOrFail($id);

        // Check if account has children
        if ($account->children()->count() > 0) {
            return redirect()->route('admin.setup.head-of-accounts.index')
                ->with('error', 'Cannot delete account with sub-accounts. Please delete sub-accounts first.');
        }

        try {
            $account->delete();

            return redirect()->route('admin.setup.head-of-accounts.index')
                ->with('success', 'Head of account deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('admin.setup.head-of-accounts.index')
                ->with('error', 'Error deleting head of account: ' . $e->getMessage());
        }
    }

    /**
     * Check if an account is a descendant of another account.
     * Returns true if $potentialDescendant is a descendant of $potentialAncestor.
     */
    private function isDescendantOf($potentialDescendant, $potentialAncestor)
    {
        // Check if the potential descendant is actually the same account
        if ($potentialDescendant->id == $potentialAncestor->id) {
            return true;
        }
        
        // Traverse up the parent chain from the potential descendant
        $current = $potentialDescendant->parent;
        while ($current) {
            if ($current->id == $potentialAncestor->id) {
                return true;
            }
            $current = $current->parent;
        }
        return false;
    }

    /**
     * Get all descendant account IDs recursively.
     */
    private function getDescendantIds($account)
    {
        $ids = [];
        $children = HeadOfAccount::where('parent_id', $account->id)->get();
        
        foreach ($children as $child) {
            $ids[] = $child->id;
            // Recursively get descendants
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }
        
        return $ids;
    }
}

