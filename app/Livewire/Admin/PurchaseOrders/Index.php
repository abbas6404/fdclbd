<?php

namespace App\Livewire\Admin\PurchaseOrders;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\HeadOfAccount;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Supplier;
use App\Models\Requisition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $editing_po_id = null; // Track if we're editing
    
    // Purchase Order form fields
    public $purchase_order_date;
    public $required_date;
    public $remark = '';
    public $selected_project_id = '';
    public $selected_employee_id = '';
    public $selected_supplier_id = '';
    public $selected_requisition_id = '';
    
    // Employee search (for form)
    public $employee_search = '';
    public $employee_results = [];
    public $selected_employee = null;
    
    // Supplier search (for form)
    public $supplier_search = '';
    public $supplier_results = [];
    public $selected_supplier = null;
    
    // Requisition search (for form)
    public $requisition_search = '';
    public $requisition_results = [];
    public $selected_requisition = null;
    
    // Head of Account search (expense head) - for form
    public $account_search = '';
    public $account_results = [];
    public $active_search_type = 'account'; // 'project', 'employee', 'supplier', 'requisition', 'account'
    
    // Project search
    public $project_search = '';
    public $project_results = [];
    
    // Purchase Order items
    public $items = []; // Array of items with head_of_account_id, description, unit, qty, amount
    
    // Unit options for dropdown
    public $unitOptions = [
        'pcs' => 'Pieces',
        'kg' => 'Kilogram',
        'g' => 'Gram',
        'ltr' => 'Liter',
        'ml' => 'Milliliter',
        'm' => 'Meter',
        'cm' => 'Centimeter',
        'sqft' => 'Square Feet',
        'sqm' => 'Square Meter',
        'box' => 'Box',
        'pack' => 'Pack',
        'set' => 'Set',
        'pair' => 'Pair',
        'dozen' => 'Dozen',
        'bundle' => 'Bundle',
        'roll' => 'Roll',
        'unit' => 'Unit',
    ];
    
    // Calculated totals
    public $total_amount = 0;
    
    // Track last used unit
    public $lastUsedUnit = 'pcs';

    public function mount($edit = null)
    {
        if ($edit) {
            $this->loadPurchaseOrderForEdit($edit);
        } else {
            $this->purchase_order_date = now()->format('Y-m-d');
            $this->required_date = now()->format('Y-m-d');
        }
        $this->active_search_type = 'account';
        $this->loadRecentAccounts();
        $this->loadRecentEmployees();
        $this->loadRecentSuppliers();
        $this->loadRecentProjects();
    }

    public function loadPurchaseOrderForEdit($poId)
    {
        $po = PurchaseOrder::with(['items.headOfAccount', 'supplier', 'employee', 'project', 'requisition'])->findOrFail($poId);
        
        $this->editing_po_id = $po->id;
        $this->purchase_order_date = $po->purchase_order_date->format('Y-m-d');
        $this->required_date = $po->required_date ? $po->required_date->format('Y-m-d') : now()->format('Y-m-d');
        $this->remark = $po->remark ?? '';
        $this->selected_supplier_id = $po->supplier_id ?? '';
        $this->selected_employee_id = $po->employee_id ?? '';
        $this->selected_project_id = $po->project_id ?? '';
        $this->selected_requisition_id = $po->requisition_id ?? '';
        
        // Set search values
        if ($po->supplier) {
            $this->supplier_search = $po->supplier->name;
            $this->selected_supplier = $po->supplier;
        }
        if ($po->employee) {
            $this->employee_search = $po->employee->name;
            $this->selected_employee = $po->employee;
        }
        if ($po->project) {
            $this->project_search = $po->project->project_name;
        }
        if ($po->requisition) {
            $this->requisition_search = $po->requisition->requisition_number;
            $this->selected_requisition = $po->requisition;
        }
        
        // Load items
        $this->items = $po->items->map(function($item) {
            return [
                'id' => $item->id,
                'head_of_account_id' => $item->head_of_account_id,
                'account_name' => $item->headOfAccount->account_name ?? 'N/A',
                'description' => $item->description ?? '',
                'unit' => $item->unit ?? 'pcs',
                'qty' => $item->qty,
                'amount' => $item->amount,
            ];
        })->toArray();
        
        $this->calculateTotal();
    }

    public function loadRecentAccounts()
    {
        $this->account_results = HeadOfAccount::where('account_type', 'expense')
            ->where('status', 'active')
            ->where('is_requisitions', true)
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

    public function loadRecentSuppliers()
    {
        $this->supplier_results = Supplier::orderBy('name', 'asc')
            ->limit(20)
            ->get()
            ->map(function($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'email' => $supplier->email ?? '',
                    'phone' => $supplier->phone ?? '',
                    'address' => $supplier->address ?? '',
                ];
            })
            ->toArray();
        $this->active_search_type = 'supplier';
    }

    public function showRecentEmployees()
    {
        $this->loadRecentEmployees();
    }

    public function showRecentSuppliers()
    {
        $this->loadRecentSuppliers();
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

    public function updatedSupplierSearch()
    {
        $this->active_search_type = 'supplier';
        if (strlen($this->supplier_search) < 2) {
            $this->loadRecentSuppliers();
            return;
        }

        $this->supplier_results = Supplier::where(function($q) {
                $q->where('name', 'like', "%{$this->supplier_search}%")
                  ->orWhere('email', 'like', "%{$this->supplier_search}%")
                  ->orWhere('phone', 'like', "%{$this->supplier_search}%");
            })
            ->orderBy('name', 'asc')
            ->limit(20)
            ->get()
            ->map(function($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'email' => $supplier->email ?? '',
                    'phone' => $supplier->phone ?? '',
                    'address' => $supplier->address ?? '',
                ];
            })
            ->toArray();
    }

    public function updatedRequisitionSearch()
    {
        $this->active_search_type = 'requisition';
        if (strlen($this->requisition_search) < 2) {
            $this->requisition_results = [];
            return;
        }

        $this->requisition_results = Requisition::where('requisition_number', 'like', "%{$this->requisition_search}%")
            ->with(['employee', 'project'])
            ->orderBy('requisition_date', 'desc')
            ->limit(20)
            ->get()
            ->map(function($requisition) {
                return [
                    'id' => $requisition->id,
                    'requisition_number' => $requisition->requisition_number,
                    'requisition_date' => $requisition->requisition_date->format('d/m/Y'),
                    'employee_name' => $requisition->employee->name ?? 'N/A',
                    'project_name' => $requisition->project->project_name ?? 'N/A',
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

    public function selectSupplier($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if ($supplier) {
            $this->selected_supplier_id = $supplier->id;
            $this->selected_supplier = [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'email' => $supplier->email ?? '',
            ];
            $this->supplier_search = $supplier->name;
            $this->supplier_results = [];
            $this->loadRecentSuppliers();
        }
    }

    public function clearSupplier()
    {
        $this->selected_supplier_id = '';
        $this->selected_supplier = null;
        $this->supplier_search = '';
        $this->loadRecentSuppliers();
    }

    public function selectRequisition($requisitionId)
    {
        $requisition = Requisition::with(['items.headOfAccount', 'employee', 'project'])->find($requisitionId);
        if ($requisition) {
            $this->selected_requisition_id = $requisition->id;
            $this->selected_requisition = [
                'id' => $requisition->id,
                'requisition_number' => $requisition->requisition_number,
                'employee_name' => $requisition->employee->name ?? 'N/A',
                'project_name' => $requisition->project->project_name ?? 'N/A',
            ];
            $this->requisition_search = $requisition->requisition_number;
            $this->requisition_results = [];
            
            // Auto-fill project and employee if not set
            if (!$this->selected_project_id && $requisition->project_id) {
                $this->selected_project_id = $requisition->project_id;
                $this->project_search = $requisition->project->project_name ?? '';
            }
            if (!$this->selected_employee_id && $requisition->employee_id) {
                $this->selectEmployee($requisition->employee_id);
            }
            
            // Load items from requisition
            $this->items = [];
            foreach ($requisition->items as $item) {
                $this->items[] = [
                    'head_of_account_id' => $item->head_of_account_id,
                    'account_name' => $item->headOfAccount->account_name ?? 'N/A',
                    'description' => $item->description ?? '',
                    'unit' => $item->unit ?? 'pcs',
                    'qty' => $item->qty ?? 1,
                    'amount' => 0,
                ];
            }
            $this->calculateTotal();
        }
    }

    public function clearRequisition()
    {
        $this->selected_requisition_id = '';
        $this->selected_requisition = null;
        $this->requisition_search = '';
        $this->requisition_results = [];
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
            ->where('is_requisitions', true)
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
            $exists = collect($this->items)->contains(function($item) use ($accountId) {
                return $item['head_of_account_id'] == $accountId;
            });
            
            if (!$exists) {
                $defaultUnit = $account->last_used_unit ?? $this->lastUsedUnit ?? 'pcs';
                
                $this->items[] = [
                    'head_of_account_id' => $account->id,
                    'account_name' => $account->account_name,
                    'description' => '',
                    'unit' => $defaultUnit,
                    'qty' => 1,
                    'amount' => 0,
                ];
            }
            
            $this->account_search = '';
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
        if (str_contains($key, '.unit')) {
            $parts = explode('.', $key);
            $index = (int) $parts[0];
            if (isset($this->items[$index]) && isset($this->items[$index]['unit'])) {
                $newUnit = $this->items[$index]['unit'];
                $this->lastUsedUnit = $newUnit;
                
                $accountId = $this->items[$index]['head_of_account_id'] ?? null;
                if ($accountId) {
                    HeadOfAccount::where('id', $accountId)->update(['last_used_unit' => $newUnit]);
                }
            }
        }
        
        // Calculate total when qty or amount changes
        if (str_contains($key, '.qty') || str_contains($key, '.amount')) {
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        $this->total_amount = collect($this->items)->sum(function($item) {
            return (int) ($item['amount'] ?? 0);
        });
    }

    public function savePurchaseOrder()
    {
        $this->validate([
            'purchase_order_date' => 'required|date',
            'required_date' => 'nullable|date|after_or_equal:purchase_order_date',
            'selected_supplier_id' => 'required|exists:suppliers,id',
            'selected_employee_id' => 'nullable|exists:employees,id',
            'selected_project_id' => 'nullable|exists:projects,id',
            'selected_requisition_id' => 'nullable|exists:requisitions,id',
            'items' => 'required|array|min:1',
            'items.*.head_of_account_id' => 'required|exists:head_of_accounts,id',
            'items.*.description' => 'nullable|string|max:500',
            'items.*.unit' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.amount' => 'required|integer|min:0',
        ], [
            'purchase_order_date.required' => 'Purchase order date is required.',
            'required_date.after_or_equal' => 'Required date must be after or equal to purchase order date.',
            'selected_supplier_id.required' => 'Please select a supplier.',
            'selected_supplier_id.exists' => 'Selected supplier is invalid.',
            'items.required' => 'Please add at least one item.',
            'items.min' => 'Please add at least one item.',
            'items.*.unit.required' => 'Unit is required.',
            'items.*.qty.required' => 'Quantity is required.',
            'items.*.qty.min' => 'Quantity must be at least 1.',
            'items.*.amount.required' => 'Amount is required.',
            'items.*.amount.min' => 'Amount cannot be negative.',
        ]);

        try {
            DB::beginTransaction();

            if ($this->editing_po_id) {
                // Update existing PO
                $purchaseOrder = PurchaseOrder::findOrFail($this->editing_po_id);
                $purchaseOrder->update([
                    'purchase_order_date' => $this->purchase_order_date,
                    'required_date' => $this->required_date ?: null,
                    'total_amount' => $this->total_amount,
                    'remark' => $this->remark,
                    'requisition_id' => $this->selected_requisition_id ?: null,
                    'project_id' => $this->selected_project_id ?: null,
                    'employee_id' => $this->selected_employee_id ?: null,
                    'supplier_id' => $this->selected_supplier_id,
                    'updated_by' => Auth::id(),
                ]);

                // Delete existing items
                PurchaseOrderItem::where('purchase_order_id', $purchaseOrder->id)->delete();

                // Create new items
                foreach ($this->items as $item) {
                    PurchaseOrderItem::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'head_of_account_id' => $item['head_of_account_id'],
                        'description' => $item['description'] ?? '',
                        'unit' => $item['unit'] ?? 'pcs',
                        'qty' => (int) $item['qty'],
                        'amount' => (int) $item['amount'],
                        'receiving_confirmation' => 'pending',
                    ]);
                }

                $message = "Purchase Order {$purchaseOrder->purchase_order_number} updated successfully!";
            } else {
                // Create new PO
                $poNumber = PurchaseOrder::generatePurchaseOrderNumber();

                $purchaseOrder = PurchaseOrder::create([
                    'purchase_order_number' => $poNumber,
                    'purchase_order_date' => $this->purchase_order_date,
                    'required_date' => $this->required_date ?: null,
                    'total_amount' => $this->total_amount,
                    'remark' => $this->remark,
                    'requisition_id' => $this->selected_requisition_id ?: null,
                    'project_id' => $this->selected_project_id ?: null,
                    'employee_id' => $this->selected_employee_id ?: null,
                    'supplier_id' => $this->selected_supplier_id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);

                foreach ($this->items as $item) {
                    PurchaseOrderItem::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'head_of_account_id' => $item['head_of_account_id'],
                        'description' => $item['description'] ?? '',
                        'unit' => $item['unit'] ?? 'pcs',
                        'qty' => (int) $item['qty'],
                        'amount' => (int) $item['amount'],
                        'receiving_confirmation' => 'pending',
                    ]);
                }

                $message = "Purchase Order {$poNumber} created successfully!";
            }

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => $message
            ]);

            if ($this->editing_po_id) {
                // Redirect to list after update
                return redirect()->route('admin.purchase-orders.list');
            } else {
                $this->resetForm();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error saving purchase order: ' . $e->getMessage()
            ]);
        }
    }

    public function resetForm()
    {
        $this->editing_po_id = null;
        $this->purchase_order_date = now()->format('Y-m-d');
        $this->required_date = now()->format('Y-m-d');
        $this->remark = '';
        $this->selected_project_id = '';
        $this->selected_employee_id = '';
        $this->selected_supplier_id = '';
        $this->selected_requisition_id = '';
        $this->selected_employee = null;
        $this->selected_supplier = null;
        $this->selected_requisition = null;
        $this->employee_search = '';
        $this->supplier_search = '';
        $this->requisition_search = '';
        $this->items = [];
        $this->total_amount = 0;
        $this->account_search = '';
        $this->loadRecentAccounts();
        $this->loadRecentEmployees();
        $this->loadRecentSuppliers();
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
        return view('livewire.admin.purchase-orders.index');
    }
}
