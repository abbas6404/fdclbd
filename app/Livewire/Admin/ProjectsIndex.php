<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
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

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
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
                ELSE 4 
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
                    ELSE 4 
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

    public function render()
    {
        $query = Project::query();

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('project_name', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply date filters
        if ($this->dateFrom) {
            $query->whereDate('project_launching_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('project_launching_date', '<=', $this->dateTo);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $projects = $query->withCount(['flats', 'flats as available_flats_count' => function($q) {
            $q->where('status', 'available');
        }, 'flats as sold_flats_count' => function($q) {
            $q->where('status', 'sold');
        }, 'flats as reserved_flats_count' => function($q) {
            $q->where('status', 'reserved');
        }])->paginate($this->perPage);

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
