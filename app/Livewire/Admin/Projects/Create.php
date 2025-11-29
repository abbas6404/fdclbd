<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Create extends Component
{
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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        try {
            Project::create([
                'project_name' => $this->project_name,
                'description' => $this->description,
                'address' => $this->address,
                'facing' => $this->facing,
                'building_height' => $this->building_height ?: null,
                'land_area' => $this->land_area ?: null,
                'total_floors' => $this->total_floors ?: null,
                'project_launching_date' => $this->project_launching_date ?: null,
                'project_hand_over_date' => $this->project_hand_over_date ?: null,
                'status' => $this->status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Project created successfully!'
            ]);

            // Reset form
            $this->reset();

            // Redirect to projects list
            return $this->redirect(route('admin.projects.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error creating project: ' . $e->getMessage()
            ]);
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.projects.index');
    }

    public function render()
    {
        return view('livewire.admin.projects.create');
    }
}