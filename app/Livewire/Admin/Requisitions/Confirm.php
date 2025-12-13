<?php

namespace App\Livewire\Admin\Requisitions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\RequisitionItemApproval;
use App\Models\ApprovalLevel;
use App\Models\RequisitionApproval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Confirm extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending'; // pending, approved, rejected, all
    public $sortField = 'requisition_date';
    public $sortDirection = 'desc';
    public $perPage = 25;
    public $selectedRequisition = null; // Track which requisition to show in modal
    public $userApprovalLevel = null; // Current user's approval level
    public $editingItemId = null; // Track which item is being edited
    public $itemForm = []; // Form data for editing item

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
        $this->selectedRequisition = Requisition::with(['employee', 'project', 'items.headOfAccount', 'items.currentApprovalLevel', 'items.itemApprovals.user', 'createdBy'])
            ->findOrFail($requisitionId);
        
        $this->editingItemId = null;
        $this->itemForm = [];
        
        $this->dispatch('openRequisitionModal');
    }

    public function editItem($itemId)
    {
        $item = RequisitionItem::findOrFail($itemId);
        $this->editingItemId = $itemId;
        $this->itemForm = [
            'description' => $item->description,
            'unit' => $item->unit,
            'qty' => $item->qty,
        ];
    }

    public function cancelEditItem()
    {
        $this->editingItemId = null;
        $this->itemForm = [];
    }

    public function updateItem($itemId)
    {
        $item = RequisitionItem::findOrFail($itemId);
        $requisition = $item->requisition;
        
        // Check if user can edit items at this level
        if (!$this->userApprovalLevel || $requisition->current_approval_level_id != $this->userApprovalLevel->id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'You are not authorized to edit items at this level.'
            ]);
            return;
        }

        // Check if item is at user's approval level
        if (!$item->current_approval_level_id || $item->current_approval_level_id != $this->userApprovalLevel->id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'This item is not at your approval level.'
            ]);
            return;
        }

        $this->validate([
            'itemForm.description' => 'nullable|string|max:500',
            'itemForm.unit' => 'required|string',
            'itemForm.qty' => 'required|integer|min:1',
        ]);

        try {
            $item->update([
                'description' => $this->itemForm['description'] ?? '',
                'unit' => $this->itemForm['unit'],
                'qty' => (int) $this->itemForm['qty'],
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Item updated successfully!'
            ]);

            $this->cancelEditItem();
            $this->viewRequisition($requisition->id); // Refresh requisition data
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error updating item: ' . $e->getMessage()
            ]);
        }
    }

    public function approveItem($itemId)
    {
        $item = RequisitionItem::findOrFail($itemId);
        $requisition = $item->requisition;
        
        // Check if user can approve items at this level
        if (!$this->userApprovalLevel || $requisition->current_approval_level_id != $this->userApprovalLevel->id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'You are not authorized to approve items at this level.'
            ]);
            return;
        }

        // Check if item is at user's approval level
        if (!$item->current_approval_level_id || $item->current_approval_level_id != $this->userApprovalLevel->id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'This item is not at your approval level.'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            // Record item approval
            RequisitionItemApproval::create([
                'requisition_item_id' => $item->id,
                'user_id' => Auth::id(),
                'approval_level_id' => $this->userApprovalLevel->id,
                'approval_date' => now(),
                'approval_status' => 'approved',
                'remarks' => 'Approved by ' . Auth::user()->name . ' at ' . $this->userApprovalLevel->name . ' level.',
            ]);

            // Get next approval level
            $nextLevel = ApprovalLevel::where('sequence', '>', $item->current_approval_sequence)
                ->orderBy('sequence')
                ->first();

            if ($nextLevel) {
                // Move item to next approval level
                $item->update([
                    'current_approval_level_id' => $nextLevel->id,
                    'current_approval_sequence' => $nextLevel->sequence,
                    'updated_by' => Auth::id(),
                ]);
            } else {
                // No more levels, item is fully approved
                $item->update([
                    'current_approval_level_id' => null,
                    'current_approval_sequence' => null,
                    'confirmation_status' => 'confirmed',
                    'updated_by' => Auth::id(),
                ]);
            }

            DB::commit();

            // Check if all items are approved at this level or moved to next level
            $this->checkAndMoveRequisition($requisition);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Item approved successfully!'
            ]);

            $this->viewRequisition($requisition->id); // Refresh requisition data
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error approving item: ' . $e->getMessage()
            ]);
        }
    }

    public function rejectItem($itemId)
    {
        $item = RequisitionItem::findOrFail($itemId);
        $requisition = $item->requisition;
        
        // Check if user can reject items at this level
        if (!$this->userApprovalLevel || $requisition->current_approval_level_id != $this->userApprovalLevel->id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'You are not authorized to reject items at this level.'
            ]);
            return;
        }

        // Check if item is at user's approval level
        if (!$item->current_approval_level_id || $item->current_approval_level_id != $this->userApprovalLevel->id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'This item is not at your approval level.'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            // Record item rejection
            RequisitionItemApproval::create([
                'requisition_item_id' => $item->id,
                'user_id' => Auth::id(),
                'approval_level_id' => $this->userApprovalLevel->id,
                'approval_date' => now(),
                'approval_status' => 'rejected',
                'remarks' => 'Rejected by ' . Auth::user()->name . ' at ' . $this->userApprovalLevel->name . ' level.',
            ]);

            // Mark item as rejected
            $item->update([
                'confirmation_status' => 'rejected',
                'current_approval_level_id' => null,
                'current_approval_sequence' => null,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Item rejected successfully!'
            ]);

            $this->viewRequisition($requisition->id); // Refresh requisition data
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error rejecting item: ' . $e->getMessage()
            ]);
        }
    }

    protected function checkAndMoveRequisition($requisition)
    {
        // Reload requisition with items
        $requisition->load('items');
        
        // Get all items (excluding rejected ones)
        $allItems = $requisition->items()->where('confirmation_status', '!=', 'rejected')->get();
        
        // Check if all items are fully approved (confirmed status and no current approval level)
        $allItemsApproved = $allItems->every(function ($item) {
            return $item->confirmation_status === 'confirmed' && 
                   $item->current_approval_level_id === null;
        });

        if ($allItemsApproved && $allItems->isNotEmpty()) {
            // All items are fully approved, automatically approve the requisition
            $requisition->update([
                'status' => 'approved',
                'current_approval_level_id' => null,
                'current_approval_sequence' => null,
                'updated_by' => Auth::id(),
            ]);
            
            // Record requisition approval
            RequisitionApproval::create([
                'requisition_id' => $requisition->id,
                'user_id' => Auth::id(),
                'approval_level_id' => null, // System auto-approval
                'approval_date' => now(),
                'approval_status' => 'approved',
                'remarks' => 'Requisition automatically approved - all items confirmed.',
            ]);
            
            return;
        }

        // Get items that are still pending approval (have a current approval level)
        $pendingItems = $requisition->items()
            ->where('confirmation_status', '!=', 'rejected')
            ->whereNotNull('current_approval_level_id')
            ->get();

        if ($pendingItems->isEmpty()) {
            return; // No pending items
        }

        // Check if all pending items have moved past the current requisition level
        $allItemsMoved = true;
        foreach ($pendingItems as $item) {
            if ($item->current_approval_level_id == $requisition->current_approval_level_id) {
                $allItemsMoved = false;
                break;
            }
        }

        if ($allItemsMoved) {
            // All items have moved to next level or beyond, move requisition to next level
            $nextLevel = ApprovalLevel::where('sequence', '>', $requisition->current_approval_sequence)
                ->orderBy('sequence')
                ->first();

            if ($nextLevel) {
                $requisition->update([
                    'current_approval_level_id' => $nextLevel->id,
                    'current_approval_sequence' => $nextLevel->sequence,
                    'updated_by' => Auth::id(),
                ]);
            }
        }
    }

    public function closeModal()
    {
        $this->selectedRequisition = null;
    }

    public function approveRequisition($requisitionId)
    {
        $requisition = Requisition::with('currentApprovalLevel')->findOrFail($requisitionId);
        $user = Auth::user();
        $userApprovalLevel = $user->getCurrentApprovalLevel();
        
        // Check if requisition is pending
        if ($requisition->status !== 'pending') {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Only pending requisitions can be approved.'
            ]);
            return;
        }

        // Check if user has approval level assigned
        if (!$userApprovalLevel) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'You do not have an approval level assigned. Please contact administrator.'
            ]);
            return;
        }

        // Check if requisition is at user's approval level
        if (!$requisition->current_approval_level_id || $requisition->current_approval_level_id != $userApprovalLevel->id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'This requisition is not at your approval level.'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            // Record approval
            RequisitionApproval::create([
                'requisition_id' => $requisition->id,
                'user_id' => $user->id,
                'approval_level_id' => $userApprovalLevel->id,
                'approval_date' => now(),
                'approval_status' => 'approved',
            ]);

            // Get next approval level
            $nextLevel = $userApprovalLevel->getNextLevel();

            if ($nextLevel) {
                // Move to next approval level
                $requisition->update([
                    'current_approval_level_id' => $nextLevel->id,
                    'current_approval_sequence' => $nextLevel->sequence,
                    'updated_by' => Auth::id(),
                ]);

                $this->dispatch('show-alert', [
                    'type' => 'success',
                    'message' => "Requisition {$requisition->requisition_number} approved and moved to next level ({$nextLevel->name})."
                ]);
            } else {
                // No more levels, fully approved
                $requisition->update([
                    'status' => 'approved',
                    'current_approval_level_id' => null,
                    'current_approval_sequence' => null,
                    'updated_by' => Auth::id(),
                ]);

                $this->dispatch('show-alert', [
                    'type' => 'success',
                    'message' => "Requisition {$requisition->requisition_number} fully approved!"
                ]);
            }

            DB::commit();
            
            // Close modal if open
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error approving requisition: ' . $e->getMessage()
            ]);
        }
    }

    public function rejectRequisition($requisitionId)
    {
        $requisition = Requisition::with('currentApprovalLevel')->findOrFail($requisitionId);
        $user = Auth::user();
        $userApprovalLevel = $user->getCurrentApprovalLevel();
        
        // Check if requisition is pending
        if ($requisition->status !== 'pending') {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Only pending requisitions can be rejected.'
            ]);
            return;
        }

        // Check if user has approval level assigned
        if (!$userApprovalLevel) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'You do not have an approval level assigned. Please contact administrator.'
            ]);
            return;
        }

        // Check if requisition is at user's approval level
        if (!$requisition->current_approval_level_id || $requisition->current_approval_level_id != $userApprovalLevel->id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'This requisition is not at your approval level.'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            // Record rejection
            RequisitionApproval::create([
                'requisition_id' => $requisition->id,
                'user_id' => $user->id,
                'approval_level_id' => $userApprovalLevel->id,
                'approval_date' => now(),
                'approval_status' => 'rejected',
            ]);

            // Reject the requisition
            $requisition->update([
                'status' => 'rejected',
                'current_approval_level_id' => null,
                'current_approval_sequence' => null,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => "Requisition {$requisition->requisition_number} rejected."
            ]);
            
            // Close modal if open
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error rejecting requisition: ' . $e->getMessage()
            ]);
        }
    }

    public function mount()
    {
        // Get current user's approval level
        $this->userApprovalLevel = Auth::user()->getCurrentApprovalLevel();
    }

    public function render()
    {
        $user = Auth::user();
        $userApprovalLevel = $user->getCurrentApprovalLevel();

        $query = Requisition::with(['employee', 'project', 'items.headOfAccount', 'createdBy', 'currentApprovalLevel'])
            ->where('status', 'pending') // Only show pending requisitions
            ->when($userApprovalLevel, function ($q) use ($userApprovalLevel) {
                // Show only requisitions at user's approval level
                $q->where('current_approval_level_id', $userApprovalLevel->id);
            }, function ($q) {
                // If user has no approval level, show nothing
                $q->whereRaw('1 = 0');
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
            'userApprovalLevel' => $userApprovalLevel,
        ]);
    }
}
