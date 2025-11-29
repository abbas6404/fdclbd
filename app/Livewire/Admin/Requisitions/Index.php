<?php

namespace App\Livewire\Admin\Requisitions;

use Livewire\Component;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\HeadOfAccount;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    // Requisition form fields
    public $requisition_date;
    public $required_date;
    public $remark = '';
    public $status = 'pending';
    public $selected_project_id = '';
    
    // Employee search (for form)
    public $employee_search = '';
    public $employee_results = [];
    public $selected_employee_id = '';
    public $selected_employee = null;
    
        // Head of Account search (expense head) - for form
    public $account_search = '';
    public $account_results = [];
    public $active_search_type = 'account'; // 'project', 'employee', 'account'
    
    // Project search
    public $project_search = '';
    public $project_results = [];
    
    // Requisition items
    public $items = []; // Array of items with chart_of_account_id, description, qty, rate, amount
    
    // Calculated totals
    public $total_amount = 0;

    public function mount()
    {
        $this->requisition_date = now()->format('Y-m-d');
        $this->required_date = now()->format('Y-m-d'); // Default to current date
        $this->active_search_type = 'account';
        $this->loadRecentAccounts();
        $this->loadRecentEmployees();
        $this->loadRecentProjects();
    }

    public function loadRecentAccounts()
    {
        // Load expense accounts (level 4 - detail accounts)
        $this->account_results = HeadOfAccount::where('account_type', 'expense')
            ->where('account_level', '4')
            ->where('status', 'active')
            ->orderBy('account_name', 'asc')
            ->limit(20)
            ->get()
            ->map(function($account) {
                return [
                    'id' => $account->id,
                    'account_name' => $account->account_name,
                    'full_path' => $account->full_path ?? $account->account_name,
                ];
            })
            ->toArray();
        
        $this->active_search_type = 'account';
    }

    public function loadRecentEmployees()
    {
        $this->employee_results = Employee::orderBy('name', 'asc')
            ->limit(20)
            ->get()
            ->map(function($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email ?? '',
                    'phone' => $employee->phone ?? '',
                    'position' => $employee->position ?? '',
                ];
            })
            ->toArray();
        $this->active_search_type = 'employee';
    }

    public function showRecentEmployees()
    {
        $this->loadRecentEmployees();
    }

    public function updatedEmployeeSearch()
    {
        $this->active_search_type = 'employee';
        if (strlen($this->employee_search) < 2) {
            $this->loadRecentEmployees();
            return;
        }

        $this->employee_results = Employee::where(function($q) {
                $q->where('name', 'like', "%{$this->employee_search}%")
                  ->orWhere('email', 'like', "%{$this->employee_search}%")
                  ->orWhere('phone', 'like', "%{$this->employee_search}%");
            })
            ->orderBy('name', 'asc')
            ->limit(20)
            ->get()
            ->map(function($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email ?? '',
                    'phone' => $employee->phone ?? '',
                    'position' => $employee->position ?? '',
                ];
            })
            ->toArray();
    }

    public function selectEmployee($employeeId)
    {
        $employee = Employee::find($employeeId);
        if ($employee) {
            $this->selected_employee_id = $employee->id;
            $this->selected_employee = [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email ?? '',
            ];
            $this->employee_search = $employee->name;
            $this->employee_results = [];
            // Keep showing recent employees after selection
            $this->loadRecentEmployees();
        }
    }

    public function clearEmployee()
    {
        $this->selected_employee_id = '';
        $this->selected_employee = null;
        $this->employee_search = '';
        $this->loadRecentEmployees();
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

        $this->account_results = HeadOfAccount::where('account_type', 'expense')
            ->where('status', 'active')
            ->where('account_name', 'like', "%{$this->account_search}%")
            ->orderBy('account_name', 'asc')
            ->limit(20)
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

    public function addItem($accountId)
    {
        $account = HeadOfAccount::find($accountId);
        if ($account) {
            // Check if account is already in items
            $exists = collect($this->items)->contains(function($item) use ($accountId) {
                return $item['chart_of_account_id'] == $accountId;
            });
            
            if (!$exists) {
                $this->items[] = [
                    'chart_of_account_id' => $account->id,
                    'account_name' => $account->account_name,
                    'description' => '',
                    'qty' => 1,
                    'rate' => 0,
                    'amount' => 0,
                ];
            }
            
            $this->account_search = '';
            // Keep showing recent accounts after selection
            $this->loadRecentAccounts();
        }
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

    public function updatedItems($value, $key)
    {
        // Auto-calculate amount when qty or rate changes
        if (str_contains($key, '.qty') || str_contains($key, '.rate')) {
            $parts = explode('.', $key);
            $index = (int) $parts[0];
            if (isset($this->items[$index])) {
                $qty = (float) ($this->items[$index]['qty'] ?? 0);
                $rate = (float) ($this->items[$index]['rate'] ?? 0);
                $this->items[$index]['amount'] = round($qty * $rate, 2);
                $this->calculateTotal();
            }
        }
    }

    public function calculateTotal()
    {
        $this->total_amount = collect($this->items)->sum('amount');
    }

    public function saveRequisition()
    {
        // Validate
        $this->validate([
            'requisition_date' => 'required|date',
            'required_date' => 'required|date|after_or_equal:requisition_date',
            'selected_employee_id' => 'required|exists:employees,id',
            'selected_project_id' => 'nullable|exists:projects,id',
            'items' => 'required|array|min:1',
            'items.*.chart_of_account_id' => 'required|exists:head_of_accounts,id',
            'items.*.description' => 'nullable|string|max:500',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.amount' => 'required|numeric|min:0',
        ], [
            'requisition_date.required' => 'Requisition date is required.',
            'required_date.required' => 'Required date is required.',
            'required_date.after_or_equal' => 'Required date must be after or equal to requisition date.',
            'selected_employee_id.required' => 'Please select an employee.',
            'selected_employee_id.exists' => 'Selected employee is invalid.',
            'items.required' => 'Please add at least one item.',
            'items.min' => 'Please add at least one item.',
            'items.*.qty.required' => 'Quantity is required.',
            'items.*.qty.min' => 'Quantity must be greater than 0.',
            'items.*.rate.required' => 'Rate is required.',
            'items.*.rate.min' => 'Rate must be 0 or greater.',
        ]);

        try {
            DB::beginTransaction();

            // Generate requisition number
            $requisitionNumber = Requisition::generateRequisitionNumber();

            // Create requisition
            $requisition = Requisition::create([
                'requisition_number' => $requisitionNumber,
                'requisition_date' => $this->requisition_date,
                'required_date' => $this->required_date,
                'total_amount' => $this->total_amount,
                'status' => $this->status,
                'remark' => $this->remark,
                'employee_id' => $this->selected_employee_id,
                'project_id' => $this->selected_project_id ?: null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Create requisition items
            foreach ($this->items as $item) {
                RequisitionItem::create([
                    'requisition_id' => $requisition->id,
                    'chart_of_account_id' => $item['chart_of_account_id'],
                    'description' => $item['description'] ?? '',
                    'qty' => $item['qty'],
                    'rate' => $item['rate'],
                    'amount' => $item['amount'],
                ]);
            }

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => "Requisition {$requisitionNumber} created successfully!"
            ]);

            // Reset form
            $this->resetForm();

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error saving requisition: ' . $e->getMessage()
            ]);
        }
    }

    public function resetForm()
    {
        $this->requisition_date = now()->format('Y-m-d');
        $this->required_date = now()->format('Y-m-d'); // Reset to current date
        $this->remark = '';
        $this->status = 'pending';
        $this->selected_project_id = '';
        $this->selected_employee_id = '';
        $this->selected_employee = null;
        $this->employee_search = '';
        $this->items = [];
        $this->total_amount = 0;
        $this->account_search = '';
        $this->loadRecentAccounts();
        $this->loadRecentEmployees();
    }
    
    public function loadRecentProjects()
    {
        $this->project_results = Project::orderBy('project_name', 'asc')
            ->limit(20)
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'project_name' => $project->project_name,
                    'address' => $project->address ?? '',
                    'facing' => $project->facing ?? '',
                ];
            })
            ->toArray();
        $this->active_search_type = 'project';
    }
    
    public function showRecentProjects()
    {
        $this->loadRecentProjects();
    }
    
    public function updatedProjectSearch()
    {
        $this->active_search_type = 'project';
        if (strlen($this->project_search) < 2) {
            $this->loadRecentProjects();
            return;
        }
        
        $this->project_results = Project::where('project_name', 'like', "%{$this->project_search}%")
            ->orWhere('address', 'like', "%{$this->project_search}%")
            ->orderBy('project_name', 'asc')
            ->limit(20)
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'project_name' => $project->project_name,
                    'address' => $project->address ?? '',
                    'facing' => $project->facing ?? '',
                ];
            })
            ->toArray();
    }
    
    public function selectProject($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $this->selected_project_id = $project->id;
            $this->project_search = $project->project_name;
            $this->project_results = [];
            // Keep showing recent projects after selection
            $this->loadRecentProjects();
        }
    }
    
    public function clearProject()
    {
        $this->selected_project_id = '';
        $this->project_search = '';
        $this->loadRecentProjects();
    }

    public function render()
    {
        return view('livewire.admin.requisitions.index');
    }
}
