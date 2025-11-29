<?php

namespace App\Livewire\Admin\Suppliers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supplier;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 25;
    public $showArchived = false; // Filter for archived suppliers

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

    public function archiveSupplier($supplierId)
    {
        try {
            $supplier = Supplier::findOrFail($supplierId);
            $supplier->delete(); // Soft delete (archive)
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Supplier archived successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error archiving supplier: ' . $e->getMessage()
            ]);
        }
    }

    public function restoreSupplier($supplierId)
    {
        try {
            $supplier = Supplier::withTrashed()->findOrFail($supplierId);
            $supplier->restore();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Supplier restored successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error restoring supplier: ' . $e->getMessage()
            ]);
        }
    }

    public function permanentlyDeleteSupplier($supplierId)
    {
        try {
            $supplier = Supplier::withTrashed()->findOrFail($supplierId);
            $supplier->forceDelete();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Supplier permanently deleted!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error deleting supplier: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        // Query based on archive filter
        if ($this->showArchived) {
            $query = Supplier::onlyTrashed();
        } else {
            $query = Supplier::query();
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $suppliers = $query->paginate($this->perPage);

        // Get statistics
        $stats = [
            'total' => Supplier::count(),
            'archived' => Supplier::onlyTrashed()->count(),
        ];

        return view('livewire.admin.suppliers.index', [
            'suppliers' => $suppliers,
            'stats' => $stats
        ]);
    }
}
