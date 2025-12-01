<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

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

    public function deleteProject($projectId)
    {
        try {
            $project = Project::findOrFail($projectId);
            $project->delete();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Project deleted successfully!'
            ]);
        } catch (\Exception $e) {
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

    public function permanentDeleteProject($projectId)
    {
        try {
            $project = Project::withTrashed()->findOrFail($projectId);
            $project->forceDelete();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Project permanently deleted!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error permanently deleting project: ' . $e->getMessage()
            ]);
        }
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
        }])->findOrFail($projectId);
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
            }])->findOrFail($this->selectedProject->id);
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

