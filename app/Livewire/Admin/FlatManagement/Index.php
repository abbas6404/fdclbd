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
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 25;

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

    public function render()
    {
        $query = ProjectFlat::with('project');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('flat_number', 'like', '%' . $this->search . '%')
                  ->orWhere('flat_type', 'like', '%' . $this->search . '%')
                  ->orWhereHas('project', function ($projectQuery) {
                      $projectQuery->where('project_name', 'like', '%' . $this->search . '%');
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

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $flats = $query->paginate($this->perPage);

        // Get statistics
        $stats = [
            'total' => ProjectFlat::count(),
            'available' => ProjectFlat::where('status', 'available')->count(),
            'sold' => ProjectFlat::where('status', 'sold')->count(),
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
