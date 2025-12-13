<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\Attachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $statusFilter = '';
    public $facingFilter = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 25;
    public $viewMode = 'table'; // table, grid, card
    public $showAdvancedFilters = false;
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedProjects = [];
    public $selectedProject = null;
    public $showFlatsModal = false;
    public $flatStatusFilter = null; // 'available', 'sold', 'reserved', or null for all
    public $showArchived = false; // Toggle to show archived projects
    
    // Document modal properties
    public $show_document_modal = false;
    public $selected_project_id = null;
    public $document_attachments = [];
    public $existing_attachments = [];

    // Delete confirmation properties
    public $projectIdToDelete = null;
    public $projectIdToPermanentDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'facingFilter' => ['except' => ''],
        'sortField' => ['except' => 'id'],
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

    public function updatingFacingFilter()
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

    public function confirmDelete($projectId)
    {
        $this->projectIdToDelete = $projectId;
        $this->dispatch('open-delete-modal');
    }

    public function deleteProject()
    {
        if (!$this->projectIdToDelete) {
            return;
        }

        try {
            $project = Project::findOrFail($this->projectIdToDelete);
            $project->delete();
            
            $this->projectIdToDelete = null;
            $this->dispatch('close-delete-modal');
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Project deleted successfully!'
            ]);
        } catch (\Exception $e) {
            $this->projectIdToDelete = null;
            $this->dispatch('close-delete-modal');
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error deleting project: ' . $e->getMessage()
            ]);
        }
    }

    public function restoreProject($projectId)
    {
        try {
            $project = Project::withTrashed()->findOrFail($projectId);
            $project->restore();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Project restored successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error restoring project: ' . $e->getMessage()
            ]);
        }
    }

    public function confirmPermanentDelete($projectId)
    {
        $this->projectIdToPermanentDelete = $projectId;
        $this->dispatch('open-permanent-delete-modal');
    }

    public function permanentDeleteProject()
    {
        if (!$this->projectIdToPermanentDelete) {
            return;
        }

        try {
            $project = Project::withTrashed()->findOrFail($this->projectIdToPermanentDelete);
            $project->forceDelete();
            
            $this->projectIdToPermanentDelete = null;
            $this->dispatch('close-permanent-delete-modal');
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Project permanently deleted!'
            ]);
        } catch (\Exception $e) {
            $this->projectIdToPermanentDelete = null;
            $this->dispatch('close-permanent-delete-modal');
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error permanently deleting project: ' . $e->getMessage()
            ]);
        }
    }

    public function printProjectFlats($projectId, $statusFilter = '')
    {
        $printUrl = route('admin.print-templates.project-flats', ['project_id' => $projectId]);
        if ($statusFilter) {
            $printUrl .= '&status=' . $statusFilter;
        }
        
        $this->dispatch('print-project-flats', url: $printUrl);
    }

    public function showProjectFlats($projectId, $status = null)
    {
        $this->flatStatusFilter = $status;
        $this->selectedProject = Project::with(['flats' => function($query) use ($status) {
            if ($status) {
                $query->where('status', $status);
            }
            $query->orderByRaw("CASE 
                WHEN status = 'available' THEN 1 
                WHEN status = 'sold' THEN 2 
                WHEN status = 'reserved' THEN 3 
                WHEN status = 'land_owner' THEN 4 
                ELSE 5 
            END");
        }, 'flats.paymentSchedules', 'flats.flatSales.customer'])->findOrFail($projectId);
        $this->showFlatsModal = true;
    }

    public function filterFlatsByStatus($status)
    {
        if ($this->selectedProject) {
            $this->flatStatusFilter = $status;
            $this->selectedProject = Project::with(['flats' => function($query) use ($status) {
                if ($status) {
                    $query->where('status', $status);
                }
                $query->orderByRaw("CASE 
                    WHEN status = 'available' THEN 1 
                    WHEN status = 'sold' THEN 2 
                    WHEN status = 'reserved' THEN 3 
                    WHEN status = 'land_owner' THEN 4 
                    ELSE 5 
                END");
            }, 'flats.paymentSchedules', 'flats.flatSales.customer'])->findOrFail($this->selectedProject->id);
        }
    }

    public function closeFlatsModal()
    {
        $this->showFlatsModal = false;
        $this->selectedProject = null;
        $this->flatStatusFilter = null;
    }

    public function toggleArchive()
    {
        $this->showArchived = !$this->showArchived;
        $this->resetPage();
    }

    // Document modal methods
    public function openDocumentModal($projectId)
    {
        $this->selected_project_id = $projectId;
        
        // Load existing attachments
        $this->existing_attachments = Attachment::where('project_id', $projectId)
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
        $this->selected_project_id = null;
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
        if (!$this->selected_project_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Project not selected.'
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
            $project = Project::find($this->selected_project_id);
            if (!$project) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Project not found.'
                ]);
                return;
            }

            $displayOrder = Attachment::where('project_id', $this->selected_project_id)->max('display_order') ?? 0;
            $savedCount = 0;

            foreach ($this->document_attachments as $attachment) {
                if (isset($attachment['file']) && $attachment['file']) {
                    $file = $attachment['file'];
                    
                    if (is_object($file) && method_exists($file, 'getClientOriginalName')) {
                        $extension = $file->getClientOriginalExtension();
                        $fileName = time() . '_' . uniqid() . '.' . $extension;
                        $filePath = $file->storeAs('document_soft_copy/project', $fileName, 'public');
                        
                        Attachment::create([
                            'document_name' => $attachment['document_name'] ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                            'file_path' => $filePath,
                            'file_size' => $file->getSize(),
                            'display_order' => ++$displayOrder,
                            'project_id' => $this->selected_project_id,
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
            $this->existing_attachments = Attachment::where('project_id', $this->selected_project_id)
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
        $query = Project::query();

        // Filter by archived status
        if ($this->showArchived) {
            $query->onlyTrashed(); // Show only archived (soft deleted) projects
        }
        // When showArchived is false, default behavior excludes soft deleted records

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $searchTerm = '%' . $this->search . '%';
                $q->where('project_name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm)
                  ->orWhere('address', 'like', $searchTerm)
                  ->orWhere('land_owner_name', 'like', $searchTerm)
                  ->orWhere('land_owner_nid', 'like', $searchTerm)
                  ->orWhere('land_owner_phone', 'like', $searchTerm);
                
                // Search by land_area if search term is numeric
                if (is_numeric($this->search)) {
                    $q->orWhere('land_area', 'like', $searchTerm);
                }
            });
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply facing filter
        if ($this->facingFilter) {
            $query->where('facing', $this->facingFilter);
        }

        // Apply date filters
        if ($this->dateFrom) {
            $query->whereDate('project_launching_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('project_launching_date', '<=', $this->dateTo);
        }

        // Get projects with counts first
        $query = $query->withCount(['flats', 'flats as available_flats_count' => function($q) {
            $q->where('status', 'available');
        }, 'flats as sold_flats_count' => function($q) {
            $q->where('status', 'sold');
        }, 'flats as reserved_flats_count' => function($q) {
            $q->where('status', 'reserved');
        }, 'flats as land_owner_flats_count' => function($q) {
            $q->where('status', 'land_owner');
        }]);

        // Apply sorting
        // For count columns, we can sort directly by the count alias
        if (in_array($this->sortField, ['available_flats_count', 'sold_flats_count', 'reserved_flats_count', 'land_owner_flats_count'])) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $projects = $query->paginate($this->perPage);

        // Get statistics with percentages
        $total = Project::count();
        $active = Project::whereIn('status', ['active', 'ongoing'])->count();
        $completed = Project::where('status', 'completed')->count();
        $on_hold = Project::where('status', 'on_hold')->count();
        
        $stats = [
            'total' => $total,
            'active' => $active,
            'completed' => $completed,
            'on_hold' => $on_hold,
            'active_percent' => $total > 0 ? round(($active / $total) * 100, 1) : 0,
            'completed_percent' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
            'on_hold_percent' => $total > 0 ? round(($on_hold / $total) * 100, 1) : 0,
        ];

        return view('livewire.admin.projects.index', [
            'projects' => $projects,
            'stats' => $stats
        ]);
    }
}

