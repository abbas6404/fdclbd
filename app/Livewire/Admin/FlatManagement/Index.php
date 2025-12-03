<?php

namespace App\Livewire\Admin\FlatManagement;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProjectFlat;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

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
            $flat = ProjectFlat::withTrashed()->findOrFail($flatId);
            $flat->forceDelete();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Flat permanently deleted!'
            ]);
        } catch (\Exception $e) {
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

        return view('livewire.admin.flat-management.index', [
            'flats' => $flats,
            'stats' => $stats,
            'projects' => $projects,
            'flatTypes' => $flatTypes
        ]);
    }
}
