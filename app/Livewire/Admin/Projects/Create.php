<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Create extends Component
{
    use WithFileUploads;

    public $project_name = '';
    public $description = '';
    public $address = '';
    public $facing = '';
    public $storey = '';
    public $land_area = '';
    public $total_floors = '';
    public $project_launching_date = '';
    public $project_hand_over_date = '';
    public $land_owner_name = '';
    public $land_owner_nid = '';
    public $land_owner_phone = '';
    public $status = 'upcoming';
    public $attachments = [];
    public $tempFiles = [];

    protected $rules = [
        'project_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'address' => 'required|string',
        'facing' => 'nullable|string|in:North,South,East,West,North-East,North-West,South-East,South-West',
        'storey' => 'nullable|integer|min:1',
        'land_area' => 'nullable|numeric|min:0',
        'total_floors' => 'nullable|integer|min:1',
        'project_launching_date' => 'nullable|date',
        'project_hand_over_date' => 'nullable|date|after_or_equal:project_launching_date',
        'land_owner_name' => 'nullable|string|max:255',
        'land_owner_nid' => 'nullable|string|max:255',
        'land_owner_phone' => 'nullable|string|max:255',
        'status' => 'required|in:upcoming,ongoing,completed,on_hold,cancelled',
        'attachments.*.document_name' => 'required_with:attachments.*.file|string|max:255',
        'attachments.*.file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240', // 10MB max, images and PDF only
        'tempFiles.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
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
            $project = Project::create([
                'project_name' => $this->project_name,
                'description' => $this->description,
                'address' => $this->address,
                'facing' => $this->facing,
                'storey' => $this->storey ?: null,
                'land_area' => $this->land_area ?: null,
                'total_floors' => $this->total_floors ?: null,
                'project_launching_date' => $this->project_launching_date ?: null,
                'project_hand_over_date' => $this->project_hand_over_date ?: null,
                'land_owner_name' => $this->land_owner_name ?: null,
                'land_owner_nid' => $this->land_owner_nid ?: null,
                'land_owner_phone' => $this->land_owner_phone ?: null,
                'status' => $this->status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Save attachments
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $index => $attachment) {
                    if (isset($attachment['file']) && $attachment['file']) {
                        $file = $attachment['file'];
                        
                        // Check if file is a Livewire temporary file or already stored
                        if (is_object($file) && method_exists($file, 'getClientOriginalName')) {
                            $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                            $filePath = $file->storeAs('attachments/projects', $fileName, 'public');
                            
                            Attachment::create([
                                'document_name' => $attachment['document_name'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                                'file_path' => $filePath,
                                'file_size' => $file->getSize(),
                                'display_order' => $index,
                                'project_id' => $project->id,
                            ]);
                        }
                    }
                }
            }

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

    public function addAttachment()
    {
        $this->attachments[] = [
            'document_name' => '',
            'file' => null,
        ];
    }

    public function updatedTempFiles()
    {
        if (!empty($this->tempFiles)) {
            foreach ($this->tempFiles as $file) {
                $this->attachments[] = [
                    'document_name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'file' => $file,
                ];
            }
            $this->tempFiles = [];
        }
    }

    public function processDroppedFiles()
    {
        $this->updatedTempFiles();
    }

    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
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