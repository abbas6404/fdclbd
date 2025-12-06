<?php

namespace App\Livewire\Admin\Flat;

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
    public $show_project_modal = false;
    
    // Flat form fields
    public $flat_number = '';
    public $flat_type = '';
    public $floor_number = '';
    public $flat_size = '';
    
    // List of flats to add
    public $flats_to_add = [];
    
    // Existing flats for selected project
    public $existing_flats = [];
    
    // Show flat type info modal
    public $showFlatTypeInfo = false;

    protected $rules = [
        'selected_project_id' => 'required|exists:projects,id',
        'flats_to_add.*.flat_number' => 'required|string|max:255',
        'flats_to_add.*.flat_type' => 'required|string|max:255',
        'flats_to_add.*.floor_number' => 'required|string|max:255',
        'flats_to_add.*.flat_size' => 'required|numeric|min:0',
        'flats_to_add.*.status' => 'required|in:available,sold,reserved,land_owner',
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
        $this->project_results = Project::select('id', 'project_name', 'description', 'address', 'facing', 'status', 'land_owner_name')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->toArray();
    }

    public function updatedProjectSearch()
    {
        if (strlen($this->project_search) >= 2) {
            // Keep modal open and show search results
            if (!$this->show_project_modal) {
                $this->show_project_modal = true;
            }
            $this->project_results = Project::select('id', 'project_name', 'description', 'address', 'facing', 'status', 'land_owner_name')
                ->where('project_name', 'like', "%{$this->project_search}%")
                ->orWhere('address', 'like', "%{$this->project_search}%")
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->toArray();
        } else {
            // If modal is open, show recent projects; otherwise keep modal closed
            if ($this->show_project_modal) {
                $this->loadRecentProjects();
            }
        }
    }

    public function openProjectSearch()
    {
        $this->show_project_modal = true;
        // Load recent projects if search is empty or less than 2 characters
        if (strlen($this->project_search) < 2) {
            $this->loadRecentProjects();
        }
    }

    public function closeProjectSearch()
    {
        $this->show_project_modal = false;
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
                'land_owner_name' => $project->land_owner_name,
                'facing' => $project->facing,
                'status' => $project->status,
            ];
            $this->project_search = $project->project_name;
            // Clear results and close modal when project is selected
            $this->project_results = [];
            $this->show_project_modal = false;
            // Load existing flats for this project
            $this->loadExistingFlats();
        }
    }

    public function loadExistingFlats()
    {
        if ($this->selected_project_id) {
            $this->existing_flats = ProjectFlat::where('project_id', $this->selected_project_id)
                ->orderBy('flat_number', 'asc')
                ->get()
                ->map(function($flat) {
                    return [
                        'id' => $flat->id,
                        'flat_number' => $flat->flat_number,
                        'flat_type' => $flat->flat_type,
                        'floor_number' => $flat->floor_number,
                        'flat_size' => $flat->flat_size,
                        'status' => $flat->status,
                    ];
                })
                ->toArray();
        } else {
            $this->existing_flats = [];
        }
    }

    public function clearProject()
    {
        $this->selected_project_id = '';
        $this->selected_project = null;
        $this->project_search = '';
        $this->flats_to_add = [];
        $this->existing_flats = [];
        $this->show_project_modal = false;
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
            'status' => 'available',
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
            'flats_to_add.*.flat_size' => 'required|numeric|min:0',
            'flats_to_add.*.status' => 'required|in:available,sold,reserved,land_owner',
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
                    'status' => $flatData['status'] ?? 'available',
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => count($this->flats_to_add) . ' flat(s) created successfully!'
            ]);

            // Reload existing flats to show newly added ones
            $this->loadExistingFlats();

            // Reset form (but keep project selected)
            $this->flats_to_add = [];

            // Don't redirect, stay on page to add more flats
            // return $this->redirect(route('admin.flat.index'), navigate: true);

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
        return redirect()->route('admin.flat.index');
    }

    public function render()
    {
        $flatTypes = ['Studio', '1BHK', '2BHK', '3BHK', '4BHK', 'Penthouse'];
        
        return view('livewire.admin.flat.create', compact('flatTypes'));
    }
}
