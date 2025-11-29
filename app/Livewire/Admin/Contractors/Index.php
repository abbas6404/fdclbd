<?php

namespace App\Livewire\Admin\Contractors;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Contractor;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 25;
    public $showArchived = false; // Filter for archived contractors

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

    public function archiveContractor($contractorId)
    {
        try {
            $contractor = Contractor::findOrFail($contractorId);
            $contractor->delete(); // Soft delete (archive)
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Contractor archived successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error archiving contractor: ' . $e->getMessage()
            ]);
        }
    }

    public function restoreContractor($contractorId)
    {
        try {
            $contractor = Contractor::withTrashed()->findOrFail($contractorId);
            $contractor->restore();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Contractor restored successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error restoring contractor: ' . $e->getMessage()
            ]);
        }
    }

    public function permanentlyDeleteContractor($contractorId)
    {
        try {
            $contractor = Contractor::withTrashed()->findOrFail($contractorId);
            $contractor->forceDelete();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Contractor permanently deleted!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error deleting contractor: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        // Query based on archive filter
        if ($this->showArchived) {
            $query = Contractor::onlyTrashed();
        } else {
            $query = Contractor::query();
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $contractors = $query->paginate($this->perPage);

        // Get statistics
        $stats = [
            'total' => Contractor::count(),
            'archived' => Contractor::onlyTrashed()->count(),
        ];

        return view('livewire.admin.contractors.index', [
            'contractors' => $contractors,
            'stats' => $stats
        ]);
    }
}
