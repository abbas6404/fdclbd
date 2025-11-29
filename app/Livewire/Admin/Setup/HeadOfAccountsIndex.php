<?php

namespace App\Livewire\Admin\Setup;

use Livewire\Component;
use App\Models\HeadOfAccount;
use Illuminate\Support\Facades\Auth;

class HeadOfAccountsIndex extends Component
{
    public $showModal = false;
    public $parentAccountId = null;
    public $parentAccountName = '';
    public $newAccountName = '';
    public $newAccountType = 'expense';
    public $newAccountStatus = 'active';
    public $expandedAccounts = []; // Track which accounts are expanded
    public $allExpanded = false; // Track if all accounts are expanded
    
    // Search and filter properties
    public $search = '';
    public $filterType = 'all'; // all, income, expense
    public $filterStatus = 'all'; // all, active, inactive
    public $filterLevel = 'all'; // all, 1, 2, 3, 4

    public function openAddModal($accountId, $accountName, $accountType)
    {
        $this->parentAccountId = $accountId;
        $this->parentAccountName = $accountName;
        $this->newAccountType = $accountType; // Inherit type from parent
        $this->newAccountName = '';
        $this->newAccountStatus = 'active';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->parentAccountId = null;
        $this->parentAccountName = '';
        $this->newAccountName = '';
    }

    public function toggleExpand($accountId)
    {
        if (in_array($accountId, $this->expandedAccounts)) {
            $this->expandedAccounts = array_values(array_diff($this->expandedAccounts, [$accountId]));
            // Update allExpanded state if we manually collapse an account
            $this->allExpanded = false;
        } else {
            $this->expandedAccounts[] = $accountId;
        }
    }

    public function isExpanded($accountId)
    {
        return in_array($accountId, $this->expandedAccounts);
    }

    public function toggleExpandCollapseAll()
    {
        if ($this->allExpanded) {
            // Collapse all
            $this->expandedAccounts = [];
            $this->allExpanded = false;
        } else {
            // Expand all
            $allAccounts = HeadOfAccount::whereHas('children')->pluck('id')->toArray();
            $this->expandedAccounts = $allAccounts;
            $this->allExpanded = true;
        }
    }

    public function expandAll()
    {
        // Get all accounts that have children
        $allAccounts = HeadOfAccount::whereHas('children')->pluck('id')->toArray();
        $this->expandedAccounts = $allAccounts;
        $this->allExpanded = true;
    }

    public function collapseAll()
    {
        $this->expandedAccounts = [];
        $this->allExpanded = false;
    }

    public function saveAccount()
    {
        $this->validate([
            'newAccountName' => 'required|string|max:255',
        ]);

        try {
            // Get parent account to determine level
            $parent = HeadOfAccount::find($this->parentAccountId);
            
            if (!$parent) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Parent account not found!'
                ]);
                return;
            }

            // Calculate account level based on parent
            $accountLevel = (string)((int)$parent->account_level + 1);

            HeadOfAccount::create([
                'account_name' => $this->newAccountName,
                'account_type' => $this->newAccountType,
                'parent_id' => $this->parentAccountId,
                'account_level' => $accountLevel,
                'status' => $this->newAccountStatus,
                'created_by' => Auth::id(),
            ]);

            $this->closeModal();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Account created successfully!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error creating account: ' . $e->getMessage()
            ]);
        }
    }

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

    private function buildTreeItem($account, $grouped, $level = 0)
    {
        // Check if account level matches its position in hierarchy
        $expectedLevel = $level + 1;
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

    public function updatedSearch()
    {
        // When searching, auto-expand all parent accounts to show matching results
        if (!empty($this->search)) {
            // Find all accounts that match the search
            $matchingAccounts = HeadOfAccount::where('account_name', 'like', '%' . $this->search . '%')
                ->get();
            
            // Collect all parent IDs that need to be expanded
            $parentIds = [];
            foreach ($matchingAccounts as $account) {
                // Get all ancestors (parents) of this account
                $current = $account;
                $visited = []; // Prevent infinite loops
                while ($current && $current->parent_id && !in_array($current->parent_id, $visited)) {
                    $parentIds[] = $current->parent_id;
                    $visited[] = $current->parent_id;
                    $current = HeadOfAccount::find($current->parent_id);
                    if (!$current) break;
                }
            }
            
            // Also expand accounts that have matching children
            $accountsWithMatchingChildren = HeadOfAccount::whereHas('children', function($query) {
                $query->where('account_name', 'like', '%' . $this->search . '%');
            })->pluck('id')->toArray();
            
            // Merge and get unique IDs
            $this->expandedAccounts = array_values(array_unique(array_merge($parentIds, $accountsWithMatchingChildren)));
            $this->allExpanded = false; // Search results don't mean all are expanded
        } else {
            // Reset expanded accounts when search is cleared
            $this->expandedAccounts = [];
            $this->allExpanded = false;
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = 'all';
        $this->filterStatus = 'all';
        $this->filterLevel = 'all';
        $this->expandedAccounts = [];
    }

    public function render()
    {
        // Build query with search
        $query = HeadOfAccount::with('parent:id,account_name');
        
        // Apply search filter - need to include parents of matching accounts
        if (!empty($this->search)) {
            // Find all accounts that match the search
            $matchingAccountIds = HeadOfAccount::where('account_name', 'like', '%' . $this->search . '%')
                ->pluck('id')
                ->toArray();
            
            if (!empty($matchingAccountIds)) {
                // Find all parent IDs (ancestors) of matching accounts
                $parentIds = [];
                $matchingAccounts = HeadOfAccount::whereIn('id', $matchingAccountIds)->get();
                
                foreach ($matchingAccounts as $account) {
                    $current = $account;
                    $visited = [];
                    while ($current && $current->parent_id && !in_array($current->parent_id, $visited)) {
                        $parentIds[] = $current->parent_id;
                        $visited[] = $current->parent_id;
                        $current = HeadOfAccount::find($current->parent_id);
                        if (!$current) break;
                    }
                }
                
                // Include both matching accounts and their parents
                $allRelevantIds = array_unique(array_merge($matchingAccountIds, $parentIds));
                $query->whereIn('id', $allRelevantIds);
            } else {
                // No matches, return empty result
                $query->whereRaw('1 = 0'); // Force empty result
            }
        }
        
        // Get all accounts with relationships (optimized)
        $allAccounts = $query->orderByRaw('CAST(account_level AS UNSIGNED) ASC')
            ->orderBy('account_name', 'ASC')
            ->get();
        
        // Build hierarchical tree structure
        $accountsTree = $this->buildAccountTree($allAccounts);
        
        // Statistics (cached for better performance)
        $stats = [
            'total_accounts' => HeadOfAccount::count(),
            'income_accounts' => HeadOfAccount::where('account_type', 'income')->count(),
            'expense_accounts' => HeadOfAccount::where('account_type', 'expense')->count(),
            'active_accounts' => HeadOfAccount::where('status', 'active')->count(),
        ];
        
        return view('livewire.admin.setup.head-of-accounts-index', compact('accountsTree', 'stats'));
    }
}
