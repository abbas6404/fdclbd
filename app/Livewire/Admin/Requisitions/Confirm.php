<?php

namespace App\Livewire\Admin\Requisitions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Requisition;
use Illuminate\Support\Facades\Auth;

class Confirm extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending'; // pending, approved, rejected, all
    public $sortField = 'requisition_date';
    public $sortDirection = 'desc';
    public $perPage = 25;
    public $selectedRequisition = null; // Track which requisition to show in modal

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'pending'],
        'sortField' => ['except' => 'requisition_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
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

    public function viewRequisition($requisitionId)
    {
        $this->selectedRequisition = Requisition::with(['employee', 'project', 'items.chartOfAccount', 'createdBy'])
            ->findOrFail($requisitionId);
        
        $this->dispatch('openRequisitionModal');
    }

    public function closeModal()
    {
        $this->selectedRequisition = null;
    }

    public function approveRequisition($requisitionId)
    {
        $requisition = Requisition::findOrFail($requisitionId);
        
        if ($requisition->status !== 'pending') {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Only pending requisitions can be approved.'
            ]);
            return;
        }

        try {
            $requisition->update([
                'status' => 'approved',
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => "Requisition {$requisition->requisition_number} approved successfully!"
            ]);
            
            // Close modal if open
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error approving requisition: ' . $e->getMessage()
            ]);
        }
    }

    public function rejectRequisition($requisitionId)
    {
        $requisition = Requisition::findOrFail($requisitionId);
        
        if ($requisition->status !== 'pending') {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Only pending requisitions can be rejected.'
            ]);
            return;
        }

        try {
            $requisition->update([
                'status' => 'rejected',
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => "Requisition {$requisition->requisition_number} rejected."
            ]);
            
            // Close modal if open
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error rejecting requisition: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $query = Requisition::with(['employee', 'project', 'items.chartOfAccount', 'createdBy'])
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('requisition_number', 'like', "%{$this->search}%")
                        ->orWhereHas('employee', function ($q) {
                            $q->where('name', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('project', function ($q) {
                            $q->where('project_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $requisitions = $query->paginate($this->perPage);

        return view('livewire.admin.requisitions.confirm', [
            'requisitions' => $requisitions,
        ]);
    }
}
