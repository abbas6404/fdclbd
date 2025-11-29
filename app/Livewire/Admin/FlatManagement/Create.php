<?php

namespace App\Livewire\Admin\FlatManagement;

use Livewire\Component;
use App\Models\ProjectFlat;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    // Project search
    public $project_search = '';
    public $project_results = [];
    public $selected_project_id = '';
    public $selected_project = null;
    
    // Flat form fields
    public $flat_number = '';
    public $flat_type = '';
    public $floor_number = '';
    public $flat_size = '';
    
    // List of flats to add
    public $flats_to_add = [];

    protected $rules = [
        'selected_project_id' => 'required|exists:projects,id',
        'flats_to_add.*.flat_number' => 'required|string|max:255',
        'flats_to_add.*.flat_type' => 'required|string|max:255',
        'flats_to_add.*.floor_number' => 'required|string|max:255',
        'flats_to_add.*.flat_size' => 'required|string|max:255',
    ];

    protected $messages = [
        'selected_project_id.required' => 'Please select a project first.',
        'flats_to_add.*.flat_number.required' => 'Flat number is required.',
        'flats_to_add.*.flat_type.required' => 'Flat type is required.',
        'flats_to_add.*.floor_number.required' => 'Floor number is required.',
        'flats_to_add.*.flat_size.required' => 'Flat size is required.',
    ];

    public function mount($project_id = null)
    {
        // If project_id is provided via query parameter, pre-select it
        if ($project_id) {
            $this->selectProject($project_id);
        } else {
            // Check for project_id in request query string
            $requestProjectId = request()->query('project_id');
            if ($requestProjectId) {
                $this->selectProject($requestProjectId);
            } else {
        // Load recent 20 projects by default
        $this->loadRecentProjects();
            }
        }
    }

    public function loadRecentProjects()
    {
        $this->project_results = Project::select('id', 'project_name', 'description', 'address', 'facing', 'status')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->toArray();
    }

    public function updatedProjectSearch()
    {
        if (strlen($this->project_search) < 2) {
            $this->project_results = [];
            return;
        }

        $this->project_results = Project::select('id', 'project_name', 'description', 'address', 'facing', 'status')
            ->where('project_name', 'like', "%{$this->project_search}%")
            ->orWhere('address', 'like', "%{$this->project_search}%")
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->toArray();
    }

    public function selectProject($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $this->selected_project_id = $project->id;
            $this->selected_project = [
                'id' => $project->id,
                'project_name' => $project->project_name,
                'address' => $project->address,
                'status' => $project->status,
            ];
            $this->project_search = $project->project_name;
            // Clear results when project is selected
            $this->project_results = [];
        }
    }

    public function clearProject()
    {
        $this->selected_project_id = '';
        $this->selected_project = null;
        $this->project_search = '';
        $this->flats_to_add = [];
        // Reload recent projects after clearing
        $this->loadRecentProjects();
    }

    public function addEmptyFlat()
    {
        if (!$this->selected_project_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please select a project first.'
            ]);
            return;
        }

        $this->flats_to_add[] = [
            'flat_number' => '',
            'flat_type' => '',
            'floor_number' => '',
            'flat_size' => '',
        ];
    }

    public function updateFlat($index, $field, $value)
    {
        if (isset($this->flats_to_add[$index])) {
                $this->flats_to_add[$index][$field] = $value;
        }
    }

    public function removeFlat($index)
    {
        unset($this->flats_to_add[$index]);
        $this->flats_to_add = array_values($this->flats_to_add); // Re-index array
    }

    public function saveFlats()
    {
        if (!$this->selected_project_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please select a project first.'
            ]);
            return;
        }

        if (count($this->flats_to_add) === 0) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please add at least one flat.'
            ]);
            return;
        }

        // Validate all flats
        $this->validate([
            'flats_to_add.*.flat_number' => 'required|string|max:255',
            'flats_to_add.*.flat_type' => 'required|string|max:255',
            'flats_to_add.*.floor_number' => 'required|string|max:255',
            'flats_to_add.*.flat_size' => 'required|string|max:255',
        ]);

        // Check for duplicate flat numbers
        $flatNumbers = collect($this->flats_to_add)->pluck('flat_number');
        if ($flatNumbers->count() !== $flatNumbers->unique()->count()) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Duplicate flat numbers found. Please ensure each flat has a unique number.'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            foreach ($this->flats_to_add as $flatData) {
                ProjectFlat::create([
                    'project_id' => $this->selected_project_id,
                    'flat_number' => $flatData['flat_number'],
                    'flat_type' => $flatData['flat_type'],
                    'floor_number' => $flatData['floor_number'],
                    'flat_size' => $flatData['flat_size'],
                    'status' => 'available',
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => count($this->flats_to_add) . ' flat(s) created successfully!'
            ]);

            // Reset form
            $this->resetForm();

            // Redirect to flats list
            return $this->redirect(route('admin.project-flat.index'), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error creating flats: ' . $e->getMessage()
            ]);
        }
    }

    public function resetForm()
    {
        $this->flats_to_add = [];
    }

    public function cancel()
    {
        return redirect()->route('admin.project-flat.index');
    }

    public function render()
    {
        $flatTypes = ['Studio', '1BHK', '2BHK', '3BHK', '4BHK', 'Penthouse'];
        
        return view('livewire.admin.flat-management.create', compact('flatTypes'));
    }
}
