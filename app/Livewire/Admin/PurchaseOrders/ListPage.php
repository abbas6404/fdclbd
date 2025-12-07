<?php

namespace App\Livewire\Admin\PurchaseOrders;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

class ListPage extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'purchase_order_date';
    public $sortDirection = 'desc';
    public $perPage = 25; // Fixed per page
    public $selectedPurchaseOrder = null; // Track which PO to show in modal

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'purchase_order_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function viewPurchaseOrder($poId)
    {
        $this->selectedPurchaseOrder = PurchaseOrder::with(['supplier', 'employee', 'project', 'requisition', 'items.headOfAccount', 'createdBy'])
            ->findOrFail($poId);
        
        $this->dispatch('openPOModal');
    }

    public function closeModal()
    {
        $this->selectedPurchaseOrder = null;
    }

    public function editPurchaseOrder($poId)
    {
        return redirect()->route('admin.purchase-orders.index', ['edit' => $poId]);
    }

    public function render()
    {
        $query = PurchaseOrder::with(['supplier', 'employee', 'project', 'requisition', 'items.headOfAccount', 'createdBy'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('purchase_order_number', 'like', "%{$this->search}%")
                        ->orWhereHas('supplier', function ($q) {
                            $q->where('name', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('employee', function ($q) {
                            $q->where('name', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('project', function ($q) {
                            $q->where('project_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $purchaseOrders = $query->paginate($this->perPage);

        return view('livewire.admin.purchase-orders.list-page', [
            'purchaseOrders' => $purchaseOrders,
        ]);
    }
}

