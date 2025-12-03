<?php

namespace App\Livewire\Admin\Projects;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    use WithFileUploads;

    public $projectId;
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
    public $existingAttachments = [];
    public $deletedAttachments = [];
    public $showDeleted = false;

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

    public function mount($project)
    {
        $this->projectId = $project;
        $project = Project::findOrFail($this->projectId);
        
        // Load project data into component properties
        $this->project_name = $project->project_name;
        $this->description = $project->description ?? '';
        $this->address = $project->address;
        $this->facing = $project->facing ?? '';
        $this->storey = $project->storey ?? '';
        $this->land_area = $project->land_area ?? '';
        $this->total_floors = $project->total_floors ?? '';
        $this->project_launching_date = $project->project_launching_date ? $project->project_launching_date->format('Y-m-d') : '';
        $this->project_hand_over_date = $project->project_hand_over_date ? $project->project_hand_over_date->format('Y-m-d') : '';
        $this->land_owner_name = $project->land_owner_name ?? '';
        $this->land_owner_nid = $project->land_owner_nid ?? '';
        $this->land_owner_phone = $project->land_owner_phone ?? '';
        $this->status = $project->status;

        // Load existing attachments (not soft deleted)
        $this->existingAttachments = $project->attachments()->orderBy('display_order')->get()->toArray();
        
        // Load soft deleted attachments
        $this->deletedAttachments = $project->attachments()->onlyTrashed()->orderBy('deleted_at', 'desc')->get()->toArray();
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
                'storey' => $this->storey ?: null,
                'land_area' => $this->land_area ?: null,
                'total_floors' => $this->total_floors ?: null,
                'project_launching_date' => $this->project_launching_date ?: null,
                'project_hand_over_date' => $this->project_hand_over_date ?: null,
                'land_owner_name' => $this->land_owner_name ?: null,
                'land_owner_nid' => $this->land_owner_nid ?: null,
                'land_owner_phone' => $this->land_owner_phone ?: null,
                'status' => $this->status,
                'updated_by' => Auth::id(),
            ]);

            // Save new attachments
            if (!empty($this->attachments)) {
                $displayOrder = count($this->existingAttachments);
                foreach ($this->attachments as $index => $attachment) {
                    if (isset($attachment['file']) && $attachment['file']) {
                        $file = $attachment['file'];
                        
                        // Check if file is a Livewire temporary file or already stored
                        if (is_object($file) && method_exists($file, 'getClientOriginalName')) {
                            $extension = $file->getClientOriginalExtension();
                            $fileName = time() . '_' . uniqid() . '.' . $extension;
                            $filePath = $file->storeAs('document_soft_copy/project', $fileName, 'public');
                            
                            Attachment::create([
                                'document_name' => $attachment['document_name'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                                'file_path' => $filePath,
                                'file_size' => $file->getSize(),
                                'display_order' => $displayOrder++,
                                'project_id' => $project->id,
                            ]);
                        }
                    }
                }
            }

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

    public function removeExistingAttachment($attachmentId)
    {
        try {
            $attachment = Attachment::findOrFail($attachmentId);
            
            // Soft delete attachment record (file remains in storage for potential restore)
            $attachment->delete();
            
            // Remove from existing attachments array
            $this->existingAttachments = array_filter($this->existingAttachments, function($item) use ($attachmentId) {
                return $item['id'] != $attachmentId;
            });
            $this->existingAttachments = array_values($this->existingAttachments);
            
            // Reload deleted attachments
            $project = Project::findOrFail($this->projectId);
            $this->deletedAttachments = $project->attachments()->onlyTrashed()->orderBy('deleted_at', 'desc')->get()->toArray();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Attachment removed successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error removing attachment: ' . $e->getMessage()
            ]);
        }
    }

    public function restoreAttachment($attachmentId)
    {
        try {
            $attachment = Attachment::withTrashed()->findOrFail($attachmentId);
            
            // Restore soft deleted attachment
            $attachment->restore();
            
            // Reload attachments
            $project = Project::findOrFail($this->projectId);
            $this->existingAttachments = $project->attachments()->orderBy('display_order')->get()->toArray();
            $this->deletedAttachments = $project->attachments()->onlyTrashed()->orderBy('deleted_at', 'desc')->get()->toArray();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Attachment restored successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error restoring attachment: ' . $e->getMessage()
            ]);
        }
    }

    public function permanentlyDeleteAttachment($attachmentId)
    {
        try {
            $attachment = Attachment::withTrashed()->findOrFail($attachmentId);
            
            // Delete file from storage
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
            
            // Permanently delete attachment record
            $attachment->forceDelete();
            
            // Reload deleted attachments
            $project = Project::findOrFail($this->projectId);
            $this->deletedAttachments = $project->attachments()->onlyTrashed()->orderBy('deleted_at', 'desc')->get()->toArray();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Attachment permanently deleted!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error deleting attachment: ' . $e->getMessage()
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
