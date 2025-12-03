<?php

namespace App\Livewire\Admin\FlatManagement;

use Livewire\Component;
use App\Models\ProjectFlat;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public $flatId;
    public $flat;
    
    // Form fields
    public $flat_number = '';
    public $flat_type = '';
    public $floor_number = '';
    public $flat_size = '';
    public $status = 'available';
    public $project_id = '';
    public $showFlatTypeInfo = false;

    protected $rules = [
        'flat_number' => 'required|string|max:255',
        'flat_type' => 'required|string|max:255',
        'floor_number' => 'required|string|max:255',
        'flat_size' => 'required|numeric|min:0',
        'status' => 'required|in:available,sold,reserved,land_owner',
        'project_id' => 'required|exists:projects,id',
    ];

    protected $messages = [
        'flat_number.required' => 'Flat number is required.',
        'flat_type.required' => 'Flat type is required.',
        'floor_number.required' => 'Floor number is required.',
        'flat_size.required' => 'Flat size is required.',
        'status.required' => 'Status is required.',
        'project_id.required' => 'Project is required.',
    ];

    public function mount($id)
    {
        $this->flatId = $id;
        $this->flat = ProjectFlat::findOrFail($id);
        
        // Populate form fields
        $this->flat_number = $this->flat->flat_number;
        $this->flat_type = $this->flat->flat_type;
        $this->floor_number = $this->flat->floor_number;
        $this->flat_size = $this->flat->flat_size;
        $this->status = $this->flat->status;
        $this->project_id = $this->flat->project_id;
    }

    public function update()
    {
        $this->validate();

        try {
            $this->flat->update([
                'flat_number' => $this->flat_number,
                'flat_type' => $this->flat_type,
                'floor_number' => $this->floor_number,
                'flat_size' => $this->flat_size,
                'status' => $this->status,
                'project_id' => $this->project_id,
                'updated_by' => Auth::id(),
            ]);

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Flat updated successfully!'
            ]);

            return $this->redirect(route('admin.project-flat.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error updating flat: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $projects = Project::orderBy('project_name', 'asc')->get();
        return view('livewire.admin.flat-management.edit', compact('projects'));
    }
}
