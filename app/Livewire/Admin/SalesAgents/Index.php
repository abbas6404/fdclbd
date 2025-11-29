<?php

namespace App\Livewire\Admin\SalesAgents;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SalesAgent;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 25;
    public $showArchived = false; // Filter for archived sales agents

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

    public function archiveSalesAgent($agentId)
    {
        try {
            $agent = SalesAgent::findOrFail($agentId);
            $agent->delete(); // Soft delete (archive)
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Sales agent archived successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error archiving sales agent: ' . $e->getMessage()
            ]);
        }
    }

    public function restoreSalesAgent($agentId)
    {
        try {
            $agent = SalesAgent::withTrashed()->findOrFail($agentId);
            $agent->restore();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Sales agent restored successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error restoring sales agent: ' . $e->getMessage()
            ]);
        }
    }

    public function permanentlyDeleteSalesAgent($agentId)
    {
        try {
            $agent = SalesAgent::withTrashed()->findOrFail($agentId);
            $agent->forceDelete();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Sales agent permanently deleted!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error deleting sales agent: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        // Query based on archive filter
        if ($this->showArchived) {
            $query = SalesAgent::onlyTrashed();
        } else {
            $query = SalesAgent::query();
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('nid_or_passport_number', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $salesAgents = $query->paginate($this->perPage);

        // Get statistics
        $stats = [
            'total' => SalesAgent::count(),
            'archived' => SalesAgent::onlyTrashed()->count(),
        ];

        return view('livewire.admin.sales-agents.index', [
            'salesAgents' => $salesAgents,
            'stats' => $stats
        ]);
    }
}
