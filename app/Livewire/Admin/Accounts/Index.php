<?php

namespace App\Livewire\Admin\Accounts;

use Livewire\Component;
use App\Models\HeadOfAccount;
use App\Models\TreasuryAccount;
use App\Models\DebitVoucher;
use App\Models\DebitVoucherItem;
use App\Models\CreditVoucher;
use App\Models\CreditVoucherItem;
use App\Models\JournalEntry;
use App\Models\JournalEntryDebit;
use App\Models\JournalEntryCredit;
use App\Models\ContraEntry;
use App\Models\ContraEntryItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    // Account entry form fields
    public $entry_date;
    public $voucher_number = '';
    public $voucher_type = 'debit'; // debit, credit, journal, contra
    public $remark = '';
    
    // Account head search
    public $account_search = '';
    public $account_results = [];
    public $active_search_type = 'account';
    
    // Account entry items
    public $items = []; // Array of items with chart_of_account_id, account_name, amount, description, entry_type (for journal/contra)
    
    // Opposite side account (for debit/credit entries)
    public $opposite_account_search = '';
    public $opposite_account_results = [];
    public $selected_opposite_account_id = '';
    public $selected_opposite_account = null;
    public $opposite_amount = 0;
    public $opposite_description = '';
    
    // Treasury account (for debit/credit vouchers)
    public $payment_method = 'cash'; // cash, bank
    public $treasury_account_id = null;
    public $treasury_accounts = [];
    
    // Calculated totals
    public $total_debit = 0;
    public $total_credit = 0;

    public function loadRecentAccounts()
    {
        // For contra entries, load treasury accounts instead of head of accounts
        if ($this->voucher_type === 'contra') {
            $this->account_results = TreasuryAccount::where('status', 'active')
                ->orderBy('account_name', 'asc')
                ->limit(50)
                ->get()
                ->map(function($account) {
                    return [
                        'id' => $account->id,
                        'account_name' => $account->account_name,
                        'account_type' => $account->account_type, // 'cash' or 'bank'
                        'account_level' => null, // Treasury accounts don't have levels
                        'is_treasury' => true, // Flag to identify treasury accounts
                        'current_balance' => $account->current_balance ?? 0,
                    ];
                })
                ->toArray();
        } else {
            $query = HeadOfAccount::where('status', 'active')
                ->where('account_level', '4'); // Show only level 4 accounts
            
            // Filter based on voucher type
            if ($this->voucher_type === 'debit') {
                // Debit voucher: show only expense accounts (debit accounts)
                $query->where('account_type', 'expense');
            } elseif ($this->voucher_type === 'credit') {
                // Credit voucher: show only income accounts (credit accounts)
                $query->where('account_type', 'income');
            } elseif ($this->voucher_type === 'journal') {
                // Journal: show both income and expense accounts
                $query->whereIn('account_type', ['income', 'expense']);
            }
            
            $this->account_results = $query->orderBy('account_name', 'asc')
                ->limit(50)
                ->get()
                ->map(function($account) {
                    return [
                        'id' => $account->id,
                        'account_name' => $account->account_name,
                        'account_type' => $account->account_type,
                        'account_level' => $account->account_level,
                        'is_treasury' => false,
                    ];
                })
                ->toArray();
        }
        
        $this->active_search_type = 'account';
    }

    public function showRecentAccounts()
    {
        $this->loadRecentAccounts();
    }

    public function updatedAccountSearch()
    {
        $this->active_search_type = 'account';
        if (strlen($this->account_search) < 2) {
            $this->loadRecentAccounts();
            return;
        }

        // For contra entries, search treasury accounts
        if ($this->voucher_type === 'contra') {
            $this->account_results = TreasuryAccount::where('status', 'active')
                ->where('account_name', 'like', "%{$this->account_search}%")
                ->orderBy('account_name', 'asc')
                ->limit(50)
                ->get()
                ->map(function($account) {
                    return [
                        'id' => $account->id,
                        'account_name' => $account->account_name,
                        'account_type' => $account->account_type, // 'cash' or 'bank'
                        'account_level' => null,
                        'is_treasury' => true,
                        'current_balance' => $account->current_balance ?? 0,
                    ];
                })
                ->toArray();
        } else {
            $query = HeadOfAccount::where('status', 'active')
                ->where('account_level', '4') // Show only level 4 accounts
                ->where('account_name', 'like', "%{$this->account_search}%");
            
            // Filter based on voucher type
            if ($this->voucher_type === 'debit') {
                // Debit voucher: show only expense accounts (debit accounts)
                $query->where('account_type', 'expense');
            } elseif ($this->voucher_type === 'credit') {
                // Credit voucher: show only income accounts (credit accounts)
                $query->where('account_type', 'income');
            } elseif ($this->voucher_type === 'journal') {
                // Journal: show both income and expense accounts
                $query->whereIn('account_type', ['income', 'expense']);
            }

            $this->account_results = $query->orderBy('account_name', 'asc')
                ->limit(50)
                ->get()
                ->map(function($account) {
                    return [
                        'id' => $account->id,
                        'account_name' => $account->account_name,
                        'account_type' => $account->account_type,
                        'account_level' => $account->account_level,
                        'is_treasury' => false,
                    ];
                })
                ->toArray();
        }
    }


    public function addItem($accountId, $entryType = null)
    {
        // For contra entries, use treasury accounts
        if ($this->voucher_type === 'contra') {
            $account = TreasuryAccount::find($accountId);
            if ($account) {
                // Check if this treasury account is already in the items list
                $existingItem = collect($this->items)->firstWhere('treasury_account_id', $account->id);
                if ($existingItem) {
                    // Account already exists, show global notification
                    $this->dispatch('show-alert', [
                        'type' => 'error',
                        'message' => 'This account has already been added to the list.'
                    ]);
                    return;
                }

                // For contra, default based on current items balance
                if ($entryType === null) {
                    $currentDebit = collect($this->items)->where('entry_type', 'debit')->sum(function($item) {
                        return $item['debit_amount'] ?? $item['amount'] ?? 0;
                    });
                    $currentCredit = collect($this->items)->where('entry_type', 'credit')->sum(function($item) {
                        return $item['credit_amount'] ?? $item['amount'] ?? 0;
                    });
                    $entryType = $currentDebit <= $currentCredit ? 'debit' : 'credit';
                }

                $this->items[] = [
                    'treasury_account_id' => $account->id,
                    'account_name' => $account->account_name,
                    'account_type' => $account->account_type, // 'cash' or 'bank'
                    'amount' => 0,
                    'debit_amount' => 0,
                    'credit_amount' => 0,
                    'description' => '',
                    'entry_type' => $entryType,
                    'is_treasury' => true,
                ];
                
                $this->account_search = '';
                $this->loadRecentAccounts();
                $this->calculateTotals();
            }
        } else {
            // For other voucher types, use head of accounts
            $account = HeadOfAccount::find($accountId);
            if ($account) {
                // Check if this account is already in the items list
                $existingItem = collect($this->items)->firstWhere('chart_of_account_id', $account->id);
                if ($existingItem) {
                    // Account already exists, show global notification
                    $this->dispatch('show-alert', [
                        'type' => 'error',
                        'message' => 'This account has already been added to the list.'
                    ]);
                    return;
                }

                // For journal entries, determine entry_type based on account type
                if ($this->voucher_type === 'journal') {
                    // Income accounts default to credit, expense accounts default to debit
                    if ($entryType === null) {
                        $entryType = $account->account_type === 'income' ? 'credit' : 'debit';
                    }
                } else {
                    // For debit/credit entries, entry_type is determined by voucher_type
                    $entryType = $this->voucher_type === 'debit' ? 'debit' : 'credit';
                }

                $this->items[] = [
                    'chart_of_account_id' => $account->id,
                    'account_name' => $account->account_name,
                    'account_type' => $account->account_type, // Store account type for reference
                    'amount' => 0,
                    'debit_amount' => $this->voucher_type === 'journal' ? 0 : 0,
                    'credit_amount' => $this->voucher_type === 'journal' ? 0 : 0,
                    'description' => '',
                    'entry_type' => $entryType ?? ($this->voucher_type === 'debit' ? 'debit' : 'credit'),
                    'is_treasury' => false,
                ];
                
                $this->account_search = '';
                $this->loadRecentAccounts();
                $this->calculateTotals();
            }
        }
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function updatedItems($value, $key)
    {
        // Auto-calculate totals when amount changes
        if (str_contains($key, '.amount') || str_contains($key, '.debit_amount') || str_contains($key, '.credit_amount')) {
            $this->calculateTotals();
        }
        // Auto-calculate when entry_type changes for journal/contra
        if (str_contains($key, '.entry_type')) {
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        if ($this->voucher_type === 'debit') {
            // Debit entry: items are debits, treasury account is credit
            $itemsTotal = collect($this->items)->sum('amount');
            $this->total_debit = $itemsTotal;
            $this->total_credit = $itemsTotal; // Same value for display (treasury account balance)
        } elseif ($this->voucher_type === 'credit') {
            // Credit entry: items are credits, treasury account is debit
            $itemsTotal = collect($this->items)->sum('amount');
            $this->total_debit = $itemsTotal; // Same value for display (treasury account balance)
            $this->total_credit = $itemsTotal;
        } elseif ($this->voucher_type === 'journal') {
            // Journal entries: use debit_amount and credit_amount fields
            $this->total_debit = collect($this->items)->sum(function($item) {
                return $item['debit_amount'] ?? ($item['entry_type'] === 'debit' ? ($item['amount'] ?? 0) : 0);
            });
            $this->total_credit = collect($this->items)->sum(function($item) {
                return $item['credit_amount'] ?? ($item['entry_type'] === 'credit' ? ($item['amount'] ?? 0) : 0);
            });
        } elseif ($this->voucher_type === 'contra') {
            // Contra entries: sum all debit_amount and credit_amount values
            $this->total_debit = collect($this->items)->sum(function($item) {
                return (float) ($item['debit_amount'] ?? 0);
            });
            $this->total_credit = collect($this->items)->sum(function($item) {
                return (float) ($item['credit_amount'] ?? 0);
            });
        }
    }

    public function updatedOppositeAmount()
    {
        $this->calculateTotals();
    }

    public function updatedVoucherType()
    {
        // Clear all selected items when voucher type changes
        $this->items = [];
        // Reset opposite account when changing voucher type
        $this->selected_opposite_account_id = '';
        $this->selected_opposite_account = null;
        $this->opposite_amount = 0;
        $this->opposite_description = '';
        $this->opposite_account_search = '';
        // Reset treasury account
        $this->treasury_account_id = null;
        $this->payment_method = 'cash';
        // Reset totals
        $this->total_debit = 0;
        $this->total_credit = 0;
        // Reload accounts with new filter based on voucher type
        $this->loadRecentAccounts();
        $this->calculateTotals();
    }

    public function loadOppositeAccounts()
    {
        $query = HeadOfAccount::where('status', 'active')
            ->where('account_level', '4'); // Show only level 4 accounts
        
        // For opposite account: debit voucher needs income (credit), credit voucher needs expense (debit)
        if ($this->voucher_type === 'debit') {
            // Debit voucher: opposite account should be income (credit side)
            $query->where('account_type', 'income');
        } elseif ($this->voucher_type === 'credit') {
            // Credit voucher: opposite account should be expense (debit side)
            $query->where('account_type', 'expense');
        }
        // For journal/contra, opposite account is not used
        
        $this->opposite_account_results = $query->orderBy('account_name', 'asc')
            ->limit(50)
            ->get()
            ->map(function($account) {
                return [
                    'id' => $account->id,
                    'account_name' => $account->account_name,
                    'full_path' => $account->full_path ?? $account->account_name,
                ];
            })
            ->toArray();
    }

    public function updatedOppositeAccountSearch()
    {
        if (strlen($this->opposite_account_search) < 2) {
            $this->loadOppositeAccounts();
            return;
        }

        $query = HeadOfAccount::where('status', 'active')
            ->where('account_level', '4') // Show only level 4 accounts
            ->where('account_name', 'like', "%{$this->opposite_account_search}%");
        
        // For opposite account: debit voucher needs income (credit), credit voucher needs expense (debit)
        if ($this->voucher_type === 'debit') {
            // Debit voucher: opposite account should be income (credit side)
            $query->where('account_type', 'income');
        } elseif ($this->voucher_type === 'credit') {
            // Credit voucher: opposite account should be expense (debit side)
            $query->where('account_type', 'expense');
        }
        // For journal/contra, opposite account is not used

        $this->opposite_account_results = $query->orderBy('account_name', 'asc')
            ->limit(50)
            ->get()
            ->map(function($account) {
                return [
                    'id' => $account->id,
                    'account_name' => $account->account_name,
                    'full_path' => $account->full_path ?? $account->account_name,
                ];
            })
            ->toArray();
    }

    public function selectOppositeAccount($accountId)
    {
        $account = HeadOfAccount::find($accountId);
        if ($account) {
            $this->selected_opposite_account_id = $account->id;
            $this->selected_opposite_account = [
                'id' => $account->id,
                'account_name' => $account->account_name,
            ];
            $this->opposite_account_search = $account->account_name;
            $this->opposite_account_results = [];
            $this->loadOppositeAccounts();
        }
    }

    public function clearOppositeAccount()
    {
        $this->selected_opposite_account_id = '';
        $this->selected_opposite_account = null;
        $this->opposite_account_search = '';
        $this->opposite_amount = 0;
        $this->loadOppositeAccounts();
    }

    public function saveEntry()
    {
        // Validate based on voucher type
        $rules = [
            'entry_date' => 'required|date',
            'voucher_type' => 'required|in:debit,credit,journal,contra',
            'items' => 'required|array|min:1',
        ];

        // For debit/credit entries
        if ($this->voucher_type === 'debit' || $this->voucher_type === 'credit') {
            $rules['items.*.chart_of_account_id'] = 'required|exists:head_of_accounts,id';
            $rules['items.*.amount'] = 'required|numeric|min:0.01';
            $rules['treasury_account_id'] = 'required|exists:treasury_accounts,id';
        }

        // For journal entries
        if ($this->voucher_type === 'journal') {
            $rules['items.*.chart_of_account_id'] = 'required|exists:head_of_accounts,id';
            // At least one of debit_amount or credit_amount must be > 0
            $rules['items.*.debit_amount'] = 'nullable|numeric|min:0';
            $rules['items.*.credit_amount'] = 'nullable|numeric|min:0';
        }

        // For contra entries
        if ($this->voucher_type === 'contra') {
            $rules['items.*.treasury_account_id'] = 'required|exists:treasury_accounts,id';
            $rules['items.*.debit_amount'] = 'nullable|numeric|min:0';
            $rules['items.*.credit_amount'] = 'nullable|numeric|min:0';
            
            // Validate that at least one item has a debit amount and one has a credit amount
            $hasDebit = collect($this->items)->some(function($item) {
                return isset($item['debit_amount']) && $item['debit_amount'] > 0;
            });
            $hasCredit = collect($this->items)->some(function($item) {
                return isset($item['credit_amount']) && $item['credit_amount'] > 0;
            });
            
            if (!$hasDebit || !$hasCredit) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Contra entries must have at least one debit amount and one credit amount.'
                ]);
                return;
            }
        }

        $rules['items.*.description'] = 'nullable|string|max:500';

        $messages = [
            'entry_date.required' => 'Entry date is required.',
            'voucher_type.required' => 'Voucher type is required.',
            'items.required' => 'Please add at least one item.',
            'items.min' => 'Please add at least one item.',
            'items.*.amount.required' => 'Amount is required.',
            'items.*.amount.min' => 'Amount must be greater than 0.',
            'treasury_account_id.required' => 'Please select a payment method.',
            'treasury_account_id.exists' => 'Selected payment method is invalid.',
        ];

        $this->validate($rules, $messages);

        // Validate double-entry balance (only for journal and contra entries)
        $this->calculateTotals();
        if ($this->voucher_type === 'journal' || $this->voucher_type === 'contra') {
            if (abs($this->total_debit - $this->total_credit) > 0.01) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Debit and Credit amounts must be equal. Difference: ' . number_format(abs($this->total_debit - $this->total_credit), 2)
                ]);
                return;
            }
        }
        
        // For debit/credit vouchers, validate that items have amounts
        if ($this->voucher_type === 'debit' || $this->voucher_type === 'credit') {
            $hasValidAmounts = collect($this->items)->every(function($item) {
                return isset($item['amount']) && $item['amount'] > 0;
            });
            
            if (!$hasValidAmounts) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'All items must have valid amounts greater than 0.'
                ]);
                return;
            }
        }

        try {
            DB::beginTransaction();

            // Handle based on voucher type
            if ($this->voucher_type === 'debit') {
                // Generate voucher number
                $voucherNumber = DebitVoucher::generateVoucherNumber();
                
                // Calculate total amount (store as integer)
                $totalAmount = (int) round($this->total_debit);

                // Create debit voucher
                $voucher = DebitVoucher::create([
                    'voucher_number' => $voucherNumber,
                    'voucher_date' => $this->entry_date,
                    'remarks' => $this->remark ?: null,
                    'total_amount' => $totalAmount,
                    'treasury_account_id' => $this->treasury_account_id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);

                // Create debit voucher items
                foreach ($this->items as $item) {
                    DebitVoucherItem::create([
                        'debit_voucher_id' => $voucher->id,
                        'head_of_account_id' => $item['chart_of_account_id'],
                        'amount' => (int) round($item['amount'] ?? 0),
                        'description' => $item['description'] ?? null,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }

                // Update treasury account current balance (debit = money going out)
                if ($this->treasury_account_id) {
                    $treasuryAccount = TreasuryAccount::find($this->treasury_account_id);
                    if ($treasuryAccount) {
                        $treasuryAccount->current_balance = $treasuryAccount->current_balance - $totalAmount;
                        $treasuryAccount->updated_by = Auth::id();
                        $treasuryAccount->save();
                    }
                }

                $successMessage = "Debit Voucher {$voucherNumber} saved successfully!";

            } elseif ($this->voucher_type === 'credit') {
                // Generate voucher number
                $voucherNumber = CreditVoucher::generateVoucherNumber();
                
                // Calculate total amount (store as integer)
                $totalAmount = (int) round($this->total_credit);

                // Create credit voucher
                $voucher = CreditVoucher::create([
                    'voucher_number' => $voucherNumber,
                    'voucher_date' => $this->entry_date,
                    'remarks' => $this->remark ?: null,
                    'total_amount' => $totalAmount,
                    'treasury_account_id' => $this->treasury_account_id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);

                // Create credit voucher items
                foreach ($this->items as $item) {
                    CreditVoucherItem::create([
                        'credit_voucher_id' => $voucher->id,
                        'head_of_account_id' => $item['chart_of_account_id'],
                        'amount' => (int) round($item['amount'] ?? 0),
                        'description' => $item['description'] ?? null,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }

                // Update treasury account current balance (credit = money coming in)
                if ($this->treasury_account_id) {
                    $treasuryAccount = TreasuryAccount::find($this->treasury_account_id);
                    if ($treasuryAccount) {
                        $treasuryAccount->current_balance = $treasuryAccount->current_balance + $totalAmount;
                        $treasuryAccount->updated_by = Auth::id();
                        $treasuryAccount->save();
                    }
                }

                $successMessage = "Credit Voucher {$voucherNumber} saved successfully!";

            } elseif ($this->voucher_type === 'journal') {
                // Generate entry number
                $entryNumber = JournalEntry::generateEntryNumber();
                
                // Calculate total amount (store as integer)
                $totalAmount = (int) round($this->total_debit);

                // Create journal entry
                $entry = JournalEntry::create([
                    'entry_number' => $entryNumber,
                    'entry_date' => $this->entry_date,
                    'remarks' => $this->remark ?: null,
                    'total_amount' => $totalAmount,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);

                // Create journal entry debits and credits
                foreach ($this->items as $item) {
                    $debitAmount = (int) round($item['debit_amount'] ?? 0);
                    $creditAmount = (int) round($item['credit_amount'] ?? 0);
                    
                    if ($debitAmount > 0) {
                        JournalEntryDebit::create([
                            'journal_entry_id' => $entry->id,
                            'head_of_account_id' => $item['chart_of_account_id'],
                            'amount' => $debitAmount,
                            'description' => $item['description'] ?? null,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);
                    }
                    
                    if ($creditAmount > 0) {
                        JournalEntryCredit::create([
                            'journal_entry_id' => $entry->id,
                            'head_of_account_id' => $item['chart_of_account_id'],
                            'amount' => $creditAmount,
                            'description' => $item['description'] ?? null,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);
                    }
                }

                $successMessage = "Journal Entry {$entryNumber} saved successfully!";

            } elseif ($this->voucher_type === 'contra') {
                // Generate entry number
                $entryNumber = ContraEntry::generateEntryNumber();

                // Create contra entry
                $entry = ContraEntry::create([
                    'entry_number' => $entryNumber,
                    'entry_date' => $this->entry_date,
                    'remarks' => $this->remark ?: null,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);

                // Create contra entry items
                foreach ($this->items as $item) {
                    $debitAmount = (int) round($item['debit_amount'] ?? 0);
                    $creditAmount = (int) round($item['credit_amount'] ?? 0);
                    
                    // Create debit item (FROM account) if debit_amount > 0
                    if ($debitAmount > 0) {
                        ContraEntryItem::create([
                            'contra_entry_id' => $entry->id,
                            'treasury_account_id' => $item['treasury_account_id'],
                            'entry_type' => 'debit',
                            'amount' => $debitAmount,
                            'description' => $item['description'] ?? null,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);
                        
                        // Update treasury account balance (money going out)
                        $treasuryAccount = TreasuryAccount::find($item['treasury_account_id']);
                        if ($treasuryAccount) {
                            $treasuryAccount->current_balance = $treasuryAccount->current_balance - $debitAmount;
                            $treasuryAccount->updated_by = Auth::id();
                            $treasuryAccount->save();
                        }
                    }
                    
                    // Create credit item (TO account) if credit_amount > 0
                    if ($creditAmount > 0) {
                        ContraEntryItem::create([
                            'contra_entry_id' => $entry->id,
                            'treasury_account_id' => $item['treasury_account_id'],
                            'entry_type' => 'credit',
                            'amount' => $creditAmount,
                            'description' => $item['description'] ?? null,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);
                        
                        // Update treasury account balance (money coming in)
                        $treasuryAccount = TreasuryAccount::find($item['treasury_account_id']);
                        if ($treasuryAccount) {
                            $treasuryAccount->current_balance = $treasuryAccount->current_balance + $creditAmount;
                            $treasuryAccount->updated_by = Auth::id();
                            $treasuryAccount->save();
                        }
                    }
                }

                $successMessage = "Contra Entry {$entryNumber} saved successfully!";
            }

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => $successMessage
            ]);

            // Reset form
            $this->resetForm();

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error saving entry: ' . $e->getMessage()
            ]);
        }
    }

    public function loadTreasuryAccounts()
    {
        $this->treasury_accounts = TreasuryAccount::where('status', 'active')
            ->orderBy('account_type')
            ->orderBy('account_name')
            ->get()
            ->toArray();
    }

    public function updatedPaymentMethod()
    {
        // Reset treasury account when payment method changes
        if ($this->payment_method === 'cash') {
            $this->treasury_account_id = null;
        }
    }

    public function resetForm()
    {
        $this->entry_date = now()->format('Y-m-d');
        $this->voucher_number = '';
        $this->voucher_type = 'debit';
        $this->remark = '';
        $this->items = [];
        $this->total_debit = 0;
        $this->total_credit = 0;
        $this->account_search = '';
        $this->selected_opposite_account_id = '';
        $this->selected_opposite_account = null;
        $this->opposite_account_search = '';
        $this->opposite_amount = 0;
        $this->opposite_description = '';
        $this->payment_method = 'cash';
        $this->treasury_account_id = null;
        $this->loadRecentAccounts();
        $this->loadOppositeAccounts();
        $this->loadTreasuryAccounts();
    }

    public function mount()
    {
        $this->entry_date = now()->format('Y-m-d');
        $this->voucher_type = 'debit';
        $this->active_search_type = 'account';
        $this->payment_method = 'cash';
        $this->loadRecentAccounts();
        $this->loadOppositeAccounts();
        $this->loadTreasuryAccounts();
    }

    public function render()
    {
        return view('livewire.admin.accounts.index');
    }
}

