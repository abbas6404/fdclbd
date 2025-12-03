<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use App\Models\Project;

class Show extends Component
{
    public $projectId;
    public $project;
    public $deletedAttachments = [];
    public $showDeleted = false;

    public function mount($project)
    {
        $this->projectId = $project;
        $this->loadProject();
    }

    public function loadProject()
    {
        $this->project = Project::with(['flats' => function($query) {
            $query->orderByRaw("CASE 
                WHEN status = 'available' THEN 1 
                WHEN status = 'sold' THEN 2 
                WHEN status = 'reserved' THEN 3 
                WHEN status = 'land_owner' THEN 4 
                ELSE 5 
            END");
        }, 'createdBy', 'updatedBy', 'attachments' => function($query) {
            $query->orderBy('display_order');
        }])
            ->findOrFail($this->projectId);
        
        // Load soft deleted attachments
        $this->deletedAttachments = $this->project->attachments()->onlyTrashed()->orderBy('deleted_at', 'desc')->get()->toArray();
    }

    public function render()
    {
        return view('livewire.admin.projects.show', [
            'project' => $this->project,
        ]);
    }
}
