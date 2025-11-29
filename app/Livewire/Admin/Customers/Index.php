<?php

namespace App\Livewire\Admin\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 25;
    public $showArchived = false; // Filter for archived customers

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'desc'],
        'showArchived' => ['except' => false],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingShowArchived()
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

    public function archiveCustomer($customerId)
    {
        try {
            $customer = Customer::findOrFail($customerId);
            $customer->delete(); // Soft delete (archive)
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Customer archived successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error archiving customer: ' . $e->getMessage()
            ]);
        }
    }

    public function restoreCustomer($customerId)
    {
        try {
            $customer = Customer::withTrashed()->findOrFail($customerId);
            $customer->restore();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Customer restored successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error restoring customer: ' . $e->getMessage()
            ]);
        }
    }

    public function permanentlyDeleteCustomer($customerId)
    {
        try {
            $customer = Customer::withTrashed()->findOrFail($customerId);
            $customer->forceDelete();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Customer permanently deleted!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error deleting customer: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        // Query based on archive filter
        if ($this->showArchived) {
            $query = Customer::onlyTrashed();
        } else {
            $query = Customer::query();
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('nid_or_passport_number', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $customers = $query->paginate($this->perPage);

        // Get statistics
        $stats = [
            'total' => Customer::count(),
            'archived' => Customer::onlyTrashed()->count(),
        ];

        return view('livewire.admin.customers.index', [
            'customers' => $customers,
            'stats' => $stats
        ]);
    }
}
