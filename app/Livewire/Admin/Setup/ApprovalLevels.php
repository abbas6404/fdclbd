<?php

namespace App\Livewire\Admin\Setup;

use Livewire\Component;
use App\Models\ApprovalLevel;
use App\Models\User;
use App\Models\UserApprovalLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApprovalLevels extends Component
{
    // Approval Level Management
    public $levels = [];
    public $showLevelForm = false;
    public $editingLevelId = null;
    public $levelForm = [
        'name' => '',
        'slug' => '',
        'sequence' => '',
        'description' => '',
        'is_active' => true,
    ];

    // User Assignment Management
    public $showUserAssignmentModal = false;
    public $selectedLevelId = null;
    public $userSearch = '';
    public $userResults = [];
    public $assignedUsers = [];

    protected $rules = [
        'levelForm.name' => 'required|string|max:255',
        'levelForm.sequence' => 'required|integer|min:1',
        'levelForm.description' => 'nullable|string',
        'levelForm.is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadLevels();
    }

    public function loadLevels()
    {
        $this->levels = ApprovalLevel::orderBy('sequence', 'asc')->get();
    }

    public function updatedLevelFormName($value)
    {
        if (!$this->editingLevelId) {
            $this->levelForm['slug'] = Str::slug($value);
        }
    }

    public function openLevelForm($levelId = null)
    {
        if ($levelId) {
            $level = ApprovalLevel::findOrFail($levelId);
            $this->editingLevelId = $levelId;
            $this->levelForm = [
                'name' => $level->name,
                'slug' => $level->slug,
                'sequence' => $level->sequence,
                'description' => $level->description,
                'is_active' => $level->is_active,
            ];
        } else {
            $this->resetLevelForm();
        }
        $this->showLevelForm = true;
    }

    public function closeLevelForm()
    {
        $this->showLevelForm = false;
        $this->resetLevelForm();
    }

    public function resetLevelForm()
    {
        $this->editingLevelId = null;
        $this->levelForm = [
            'name' => '',
            'slug' => '',
            'sequence' => '',
            'description' => '',
            'is_active' => true,
        ];
    }

    public function saveLevel()
    {
        $this->validate();

        // Check for duplicate sequence (excluding current level if editing)
        $sequenceExists = ApprovalLevel::where('sequence', $this->levelForm['sequence'])
            ->when($this->editingLevelId, function ($q) {
                $q->where('id', '!=', $this->editingLevelId);
            })
            ->exists();

        if ($sequenceExists) {
            $this->addError('levelForm.sequence', 'This sequence number is already in use.');
            return;
        }

        // Check for duplicate slug (excluding current level if editing)
        $slugExists = ApprovalLevel::where('slug', $this->levelForm['slug'])
            ->when($this->editingLevelId, function ($q) {
                $q->where('id', '!=', $this->editingLevelId);
            })
            ->exists();

        if ($slugExists) {
            $this->addError('levelForm.slug', 'This slug is already in use.');
            return;
        }

        try {
            if ($this->editingLevelId) {
                $level = ApprovalLevel::findOrFail($this->editingLevelId);
                $level->update($this->levelForm);
                $message = 'Approval level updated successfully!';
            } else {
                ApprovalLevel::create($this->levelForm);
                $message = 'Approval level created successfully!';
            }

            $this->loadLevels();
            $this->closeLevelForm();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => $message
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error saving approval level: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteLevel($levelId)
    {
        $level = ApprovalLevel::findOrFail($levelId);

        // Check if level has assigned users
        if ($level->userApprovalLevels()->count() > 0) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Cannot delete approval level with assigned users. Please remove users first.'
            ]);
            return;
        }

        // Check if level is used in requisitions
        if ($level->requisitions()->count() > 0) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Cannot delete approval level that is being used in requisitions.'
            ]);
            return;
        }

        try {
            $level->delete();
            $this->loadLevels();
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Approval level deleted successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error deleting approval level: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleLevelStatus($levelId)
    {
        $level = ApprovalLevel::findOrFail($levelId);
        $level->update(['is_active' => !$level->is_active]);
        $this->loadLevels();
    }

    // User Assignment Methods
    public function openUserAssignmentModal($levelId)
    {
        $this->selectedLevelId = $levelId;
        $this->userSearch = '';
        $this->userResults = [];
        $this->loadAssignedUsers();
        $this->showUserAssignmentModal = true;
    }

    public function closeUserAssignmentModal()
    {
        $this->showUserAssignmentModal = false;
        $this->selectedLevelId = null;
        $this->userSearch = '';
        $this->userResults = [];
        $this->assignedUsers = [];
    }

    public function loadAssignedUsers()
    {
        if ($this->selectedLevelId) {
            $level = ApprovalLevel::findOrFail($this->selectedLevelId);
            $this->assignedUsers = $level->users()->orderBy('name')->get();
        }
    }

    public function updatedUserSearch()
    {
        if (strlen($this->userSearch) >= 2) {
            $this->userResults = User::where(function ($q) {
                $q->where('name', 'like', "%{$this->userSearch}%")
                  ->orWhere('email', 'like', "%{$this->userSearch}%");
            })
            ->whereDoesntHave('approvalLevels', function ($q) {
                $q->where('approval_levels.id', $this->selectedLevelId);
            })
            ->limit(10)
            ->get();
        } else {
            $this->userResults = [];
        }
    }

    public function assignUser($userId)
    {
        if (!$this->selectedLevelId) {
            return;
        }

        try {
            UserApprovalLevel::updateOrCreate(
                [
                    'user_id' => $userId,
                    'approval_level_id' => $this->selectedLevelId,
                ],
                [
                    'is_active' => true,
                ]
            );

            $this->userSearch = '';
            $this->userResults = [];
            $this->loadAssignedUsers();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'User assigned successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error assigning user: ' . $e->getMessage()
            ]);
        }
    }

    public function removeUser($userId)
    {
        if (!$this->selectedLevelId) {
            return;
        }

        try {
            UserApprovalLevel::where('user_id', $userId)
                ->where('approval_level_id', $this->selectedLevelId)
                ->delete();

            $this->loadAssignedUsers();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'User removed successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error removing user: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.setup.approval-levels');
    }
}
