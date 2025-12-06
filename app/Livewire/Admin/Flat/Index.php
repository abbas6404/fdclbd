<?php

namespace App\Livewire\Admin\Flat;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ProjectFlat;
use App\Models\Project;
use App\Models\Attachment;
use App\Models\FlatSale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $projectFilter = '';
    public $flatTypeFilter = '';
    public $statusFilter = '';
    public $sizeFrom = '';
    public $sizeTo = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 25;
    public $showArchived = false; // Toggle to show archived flats
    
    // Document modal
    public $show_document_modal = false;
    public $selected_flat_id = null;
    public $document_attachments = [];
    public $existing_attachments = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'projectFilter' => ['except' => ''],
        'flatTypeFilter' => ['except' => ''],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProjectFilter()
    {
        $this->resetPage();
    }

    public function updatingFlatTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSizeFrom()
    {
        $this->resetPage();
    }

    public function updatingSizeTo()
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

    public function deleteFlat($flatId)
    {
        try {
            $flat = ProjectFlat::findOrFail($flatId);
            $flat->delete();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Flat deleted successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error deleting flat: ' . $e->getMessage()
            ]);
        }
    }

    public function restoreFlat($flatId)
    {
        try {
            $flat = ProjectFlat::withTrashed()->findOrFail($flatId);
            $flat->restore();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Flat restored successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error restoring flat: ' . $e->getMessage()
            ]);
        }
    }

    public function permanentDeleteFlat($flatId)
    {
        try {
            DB::beginTransaction();
            
            $flat = ProjectFlat::withTrashed()->findOrFail($flatId);
            
            // Check if flat has active (non-deleted) flat sales
            $activeSalesCount = FlatSale::where('flat_id', $flatId)->count();
            if ($activeSalesCount > 0) {
                DB::rollBack();
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => "Cannot permanently delete flat. It has {$activeSalesCount} active sale(s). Please delete or archive the sale(s) first."
                ]);
                return;
            }
            
            // Get all attachments (including soft-deleted) before deletion
            $attachments = Attachment::withTrashed()->where('flat_id', $flatId)->get();
            
            // Delete physical files from storage
            foreach ($attachments as $attachment) {
                if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
                    Storage::disk('public')->delete($attachment->file_path);
                }
            }
            
            // Permanently delete all soft-deleted attachments
            Attachment::withTrashed()
                ->where('flat_id', $flatId)
                ->forceDelete();
            
            // Permanently delete all soft-deleted flat sales
            FlatSale::withTrashed()
                ->where('flat_id', $flatId)
                ->forceDelete();
            
            // Finally, permanently delete the flat itself
            $flat->forceDelete();
            
            DB::commit();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Flat permanently deleted along with all related records and files!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error permanently deleting flat: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleArchive()
    {
        $this->showArchived = !$this->showArchived;
        $this->resetPage();
    }

    public function openSetScheduleModal($flatId)
    {
        // Find the most recent sale for this flat
        $sale = FlatSale::where('flat_id', $flatId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($sale) {
            // Redirect to payment schedules page with the sale_id as query parameter
            $this->redirect(route('admin.payment-schedules.index') . '?sale_id=' . $sale->id);
        } else {
            // No sale found - show alert and redirect to flat sales page to create sale first
            $this->dispatch('show-alert', [
                'type' => 'info',
                'message' => 'Please create a flat sale first before setting payment schedule. Redirecting to Flat Sales page...'
            ]);
            
            // Redirect to flat sales page with flat_id to create sale
            $this->redirect(route('admin.flat-sales.index') . '?flat_id=' . $flatId);
        }
    }

    public function openPaymentReceiveModal($flatId)
    {
        // Find the most recent sale for this flat
        $sale = FlatSale::where('flat_id', $flatId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($sale && $sale->customer_id) {
            // Redirect to payment receive page with the customer_id as query parameter
            $this->redirect(route('admin.payment-receive.index') . '?customer_id=' . $sale->customer_id);
        } else {
            // No sale or customer found - show alert and redirect to flat sales page to create sale first
            $this->dispatch('show-alert', [
                'type' => 'info',
                'message' => 'Please create a flat sale first before receiving payment. Redirecting to Flat Sales page...'
            ]);
            
            // Redirect to flat sales page with flat_id to create sale
            $this->redirect(route('admin.flat-sales.index') . '?flat_id=' . $flatId);
        }
    }

    public function openDocumentModal($flatId)
    {
        $this->selected_flat_id = $flatId;
        
        // Load existing attachments
        $this->existing_attachments = Attachment::where('flat_id', $flatId)
            ->orderBy('display_order', 'asc')
            ->get()
            ->map(function($attachment) {
                return [
                    'id' => $attachment->id,
                    'document_name' => $attachment->document_name,
                    'file_path' => $attachment->file_path,
                    'file_size' => $attachment->file_size,
                    'is_existing' => true,
                ];
            })
            ->toArray();
        
        $this->show_document_modal = true;
        $this->document_attachments = [];
    }

    public function closeDocumentModal()
    {
        $this->show_document_modal = false;
        $this->document_attachments = [];
        $this->existing_attachments = [];
        $this->selected_flat_id = null;
    }

    public function addDocumentAttachment()
    {
        $this->document_attachments[] = [
            'document_name' => '',
            'file' => null,
        ];
    }

    public function removeDocumentAttachment($index)
    {
        unset($this->document_attachments[$index]);
        $this->document_attachments = array_values($this->document_attachments);
    }

    public function removeExistingAttachment($attachmentId)
    {
        try {
            $attachment = Attachment::find($attachmentId);
            if ($attachment) {
                // Soft delete (model uses SoftDeletes trait)
                $attachment->delete();
                
                // Remove from existing attachments array
                $this->existing_attachments = array_filter($this->existing_attachments, function($item) use ($attachmentId) {
                    return $item['id'] != $attachmentId;
                });
                $this->existing_attachments = array_values($this->existing_attachments);
                
                $this->dispatch('show-alert', [
                    'type' => 'success',
                    'message' => 'Document removed successfully!'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error removing document: ' . $e->getMessage()
            ]);
        }
    }

    public function saveDocuments()
    {
        if (!$this->selected_flat_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Flat not selected.'
            ]);
            return;
        }

        // Check if there are any new documents to save
        $hasNewDocuments = false;
        foreach ($this->document_attachments as $attachment) {
            if (isset($attachment['file']) && $attachment['file']) {
                $hasNewDocuments = true;
                break;
            }
        }

        if (!$hasNewDocuments && empty($this->document_attachments)) {
            // No new documents to save, just close modal
            $this->closeDocumentModal();
            return;
        }

        try {
            $flat = ProjectFlat::find($this->selected_flat_id);
            if (!$flat) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Flat not found.'
                ]);
                return;
            }

            $displayOrder = Attachment::where('flat_id', $this->selected_flat_id)->max('display_order') ?? 0;
            $savedCount = 0;

            foreach ($this->document_attachments as $attachment) {
                if (isset($attachment['file']) && $attachment['file']) {
                    $file = $attachment['file'];
                    
                    if (is_object($file) && method_exists($file, 'getClientOriginalName')) {
                        $extension = $file->getClientOriginalExtension();
                        $fileName = time() . '_' . uniqid() . '.' . $extension;
                        $filePath = $file->storeAs('document_soft_copy/flat_sale', $fileName, 'public');
                        
                        Attachment::create([
                            'document_name' => $attachment['document_name'] ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                            'file_path' => $filePath,
                            'file_size' => $file->getSize(),
                            'display_order' => ++$displayOrder,
                            'flat_id' => $this->selected_flat_id,
                        ]);
                        $savedCount++;
                    }
                }
            }

            if ($savedCount > 0) {
                $this->dispatch('show-alert', [
                    'type' => 'success',
                    'message' => "{$savedCount} document(s) saved successfully!"
                ]);
            }

            // Reload existing attachments
            $this->existing_attachments = Attachment::where('flat_id', $this->selected_flat_id)
                ->orderBy('display_order', 'asc')
                ->get()
                ->map(function($attachment) {
                    return [
                        'id' => $attachment->id,
                        'document_name' => $attachment->document_name,
                        'file_path' => $attachment->file_path,
                        'file_size' => $attachment->file_size,
                        'is_existing' => true,
                    ];
                })
                ->toArray();

            // Clear new attachments
            $this->document_attachments = [];

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error saving documents: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $query = ProjectFlat::with('project');

        // Filter by archived status
        if ($this->showArchived) {
            $query->onlyTrashed(); // Show only archived (soft deleted) flats
        }
        // When showArchived is false, default behavior excludes soft deleted records

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $searchTerm = '%' . $this->search . '%';
                $q->where('flat_number', 'like', $searchTerm)
                  ->orWhere('flat_type', 'like', $searchTerm)
                  ->orWhere('floor_number', 'like', $searchTerm)
                  ->orWhereHas('project', function ($projectQuery) use ($searchTerm) {
                      $projectQuery->where('project_name', 'like', $searchTerm)
                                   ->orWhere('address', 'like', $searchTerm);
                  });
            });
        }

        // Apply project filter
        if ($this->projectFilter) {
            $query->where('project_id', $this->projectFilter);
        }

        // Apply flat type filter
        if ($this->flatTypeFilter) {
            $query->where('flat_type', $this->flatTypeFilter);
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply size range filter
        if ($this->sizeFrom) {
            $query->where('flat_size', '>=', $this->sizeFrom);
        }
        if ($this->sizeTo) {
            $query->where('flat_size', '<=', $this->sizeTo);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $flats = $query->paginate($this->perPage);

        // Get statistics
        $stats = [
            'total' => ProjectFlat::count(),
            'available' => ProjectFlat::where('status', 'available')->count(),
            'sold' => ProjectFlat::where('status', 'sold')->count(),
            'land_owner' => ProjectFlat::where('status', 'land_owner')->count(),
            'projects_count' => Project::count(),
        ];

        // Get projects for filter dropdown
        $projects = Project::select('id', 'project_name')->get();

        // Get unique flat types for filter dropdown
        $flatTypes = ProjectFlat::select('flat_type')
            ->distinct()
            ->orderBy('flat_type')
            ->pluck('flat_type');

        return view('livewire.admin.flat.index', [
            'flats' => $flats,
            'stats' => $stats,
            'projects' => $projects,
            'flatTypes' => $flatTypes
        ]);
    }
}
