<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $projectId;
    public $project_name = '';
    public $description = '';
    public $address = '';
    public $facing = '';
    public $building_height = '';
    public $land_area = '';
    public $total_floors = '';
    public $project_launching_date = '';
    public $project_hand_over_date = '';
    public $status = 'upcoming';

    protected $rules = [
        'project_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'address' => 'required|string',
        'facing' => 'nullable|string|in:North,South,East,West,North-East,North-West,South-East,South-West',
        'building_height' => 'nullable|string|max:255',
        'land_area' => 'nullable|string|max:255',
        'total_floors' => 'nullable|integer|min:1',
        'project_launching_date' => 'nullable|date',
        'project_hand_over_date' => 'nullable|date|after_or_equal:project_launching_date',
        'status' => 'required|in:upcoming,ongoing,completed,on_hold,cancelled',
    ];

    protected $messages = [
        'project_name.required' => 'Project name is required.',
        'address.required' => 'Address is required.',
        'project_hand_over_date.after_or_equal' => 'Hand over date must be after or equal to launching date.',
        'facing.in' => 'Please select a valid facing direction.',
        'status.in' => 'Please select a valid status.',
    ];

    public function mount($project)
    {
        $this->projectId = $project;
        $project = Project::findOrFail($this->projectId);
        
        // Load project data into component properties
        $this->project_name = $project->project_name;
        $this->description = $project->description ?? '';
        $this->address = $project->address;
        $this->facing = $project->facing ?? '';
        $this->building_height = $project->building_height ?? '';
        $this->land_area = $project->land_area ?? '';
        $this->total_floors = $project->total_floors ?? '';
        $this->project_launching_date = $project->project_launching_date ? $project->project_launching_date->format('Y-m-d') : '';
        $this->project_hand_over_date = $project->project_hand_over_date ? $project->project_hand_over_date->format('Y-m-d') : '';
        $this->status = $project->status;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function update()
    {
        $this->validate();

        try {
            $project = Project::findOrFail($this->projectId);
            
            $project->update([
                'project_name' => $this->project_name,
                'description' => $this->description,
                'address' => $this->address,
                'facing' => $this->facing ?: null,
                'building_height' => $this->building_height ?: null,
                'land_area' => $this->land_area ?: null,
                'total_floors' => $this->total_floors ?: null,
                'project_launching_date' => $this->project_launching_date ?: null,
                'project_hand_over_date' => $this->project_hand_over_date ?: null,
                'status' => $this->status,
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Project updated successfully!'
            ]);

            // Redirect to projects list
            return $this->redirect(route('admin.projects.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error updating project: ' . $e->getMessage()
            ]);
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.projects.index');
    }

    public function render()
    {
        return view('livewire.admin.projects.edit');
    }
}
