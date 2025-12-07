<?php

namespace App\Livewire\Admin\Boq;

use Livewire\Component;
use App\Models\BoqRecord;
use App\Models\HeadOfAccount;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    // Project selection
    public $project_search = '';
    public $project_results = [];
    public $selected_project_id = '';
    public $selected_project = null;

    // Head of Account search for adding items
    public $item_account_search = '';
    public $item_account_results = [];
    public $show_account_dropdown = false;
    
    // BOQ Records (saved records)
    public $boq_records = [];
    
    // BOQ Items (for adding/editing - similar to requisition items)
    public $boq_items = [];
    
    // Change History
    public $show_history_modal = false;
    public $selected_record_history = [];
    public $selected_record_name = '';

    public function mount()
    {
        $this->loadRecentProjects();
        $this->loadRecentAccounts();
    }

    public function loadRecentProjects()
    {
        $this->project_results = Project::withCount('boqRecords')
            ->orderBy('project_name', 'asc')
            ->limit(20)
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->project_name,
                    'address' => $project->address ?? '',
                    'facing' => $project->facing ?? 'N/A',
                    'storey' => $project->storey ?? 'N/A',
                    'land_area' => $project->land_area ?? 'N/A',
                    'boq_count' => $project->boq_records_count ?? 0,
                ];
            })
            ->toArray();
    }

    public function loadRecentAccounts()
    {
        $this->item_account_results = HeadOfAccount::where('status', 'active')
            ->where('is_boq', true)
            ->where('account_level', '4')
            ->orderBy('account_name', 'asc')
            ->limit(20)
            ->get()
            ->map(function($account) {
                return [
                    'id' => $account->id,
                    'account_name' => $account->account_name,
                    'full_path' => $account->full_path ?? $account->account_name,
                    'last_rate' => $account->last_rate ?? 0,
                ];
            })
            ->toArray();
    }

    public function updatedProjectSearch()
    {
        if (strlen($this->project_search) >= 2) {
            $this->project_results = Project::withCount('boqRecords')
                ->where('project_name', 'like', "%{$this->project_search}%")
                ->orderBy('project_name', 'asc')
                ->limit(20)
                ->get()
                ->map(function($project) {
                    return [
                        'id' => $project->id,
                        'name' => $project->project_name,
                        'address' => $project->address ?? '',
                        'facing' => $project->facing ?? 'N/A',
                        'storey' => $project->storey ?? 'N/A',
                        'land_area' => $project->land_area ?? 'N/A',
                        'boq_count' => $project->boq_records_count ?? 0,
                    ];
                })
                ->toArray();
        } else {
            $this->loadRecentProjects();
        }
    }

    public function updatedItemAccountSearch()
    {
        if (strlen($this->item_account_search) >= 2) {
            $this->item_account_results = HeadOfAccount::where('status', 'active')
                ->where('is_boq', true)
                ->where('account_level', '4')
                ->where('account_name', 'like', "%{$this->item_account_search}%")
                ->orderBy('account_name', 'asc')
                ->limit(20)
                ->get()
                ->map(function($account) {
                    return [
                        'id' => $account->id,
                        'account_name' => $account->account_name,
                        'full_path' => $account->full_path ?? $account->account_name,
                        'last_rate' => $account->last_rate ?? 0,
                    ];
                })
                ->toArray();
            $this->show_account_dropdown = true;
        } else {
            if ($this->show_account_dropdown) {
                $this->loadRecentAccounts();
            }
        }
    }

    public function showAccountDropdown()
    {
        $this->show_account_dropdown = true;
        if (strlen($this->item_account_search) < 2) {
            $this->loadRecentAccounts();
        }
    }

    public function hideAccountDropdown()
    {
        $this->show_account_dropdown = false;
    }

    public function selectProject($projectId)
    {
        $project = Project::find($projectId);
        if ($project) {
            $this->selected_project_id = $project->id;
            $this->selected_project = [
                'id' => $project->id,
                'name' => $project->project_name,
                'address' => $project->address ?? '',
                'facing' => $project->facing ?? 'N/A',
                'storey' => $project->storey ?? 'N/A',
                'land_area' => $project->land_area ?? 'N/A',
            ];
            $this->project_search = $project->project_name;
            $this->loadBoqRecords();
            $this->boq_items = []; // Clear items when selecting new project
        }
    }

    public function clearProject()
    {
        $this->selected_project_id = '';
        $this->selected_project = null;
        $this->project_search = '';
        $this->boq_records = [];
        $this->boq_items = [];
    }

    public function loadBoqRecords()
    {
        if (!$this->selected_project_id) {
            $this->boq_records = [];
            return;
        }

        $this->boq_records = BoqRecord::with(['headOfAccount', 'createdBy', 'updatedBy'])
            ->where('project_id', $this->selected_project_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($record) {
                return [
                    'id' => $record->id,
                    'head_of_account_id' => $record->head_of_account_id,
                    'account_name' => $record->headOfAccount->account_name ?? 'N/A',
                    'full_path' => $record->headOfAccount->full_path ?? $record->headOfAccount->account_name ?? 'N/A',
                    'planned_quantity' => number_format($record->planned_quantity, 2),
                    'used_quantity' => number_format($record->used_quantity, 2),
                    'unit_rate' => number_format($record->unit_rate, 2),
                    'planned_amount' => number_format($record->planned_amount, 2),
                    'used_amount' => number_format($record->used_amount, 2),
                    'remaining_quantity' => number_format($record->remaining_quantity, 2),
                    'change_history' => $record->change_history ?? [],
                    'created_at' => $record->created_at ? $record->created_at->format('d M Y, h:i A') : 'N/A',
                    'created_by' => $record->createdBy->name ?? 'N/A',
                ];
            })
            ->toArray();
    }

    public function addBoqItem($accountId = null)
    {
        if (!$this->selected_project_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please select a project first.'
            ]);
            return;
        }

        if ($accountId) {
            $account = HeadOfAccount::find($accountId);
            if ($account) {
                // Check if account already exists in items
                $exists = collect($this->boq_items)->contains(function($item) use ($accountId) {
                    return isset($item['head_of_account_id']) && $item['head_of_account_id'] == $accountId;
                });
                
                // Also check if it exists in saved records
                $existsInRecords = collect($this->boq_records)->contains(function($record) use ($accountId) {
                    return isset($record['head_of_account_id']) && $record['head_of_account_id'] == $accountId;
                });
                
                if ($exists || $existsInRecords) {
                    $this->dispatch('show-alert', [
                        'type' => 'error',
                        'message' => 'This account already exists in BOQ records.'
                    ]);
                    return;
                }
                
                $this->boq_items[] = [
                    'head_of_account_id' => $account->id,
                    'account_name' => $account->account_name,
                    'full_path' => $account->full_path ?? $account->account_name,
                    'planned_quantity' => '',
                    'used_quantity' => '0',
                    'unit_rate' => $account->last_rate ?? '',
                    'account_search' => '',
                ];
                
                $this->item_account_search = '';
                $this->show_account_dropdown = false;
                $this->loadRecentAccounts();
            }
        } else {
            // Add empty row
            $this->boq_items[] = [
                'head_of_account_id' => '',
                'account_name' => '',
                'full_path' => '',
                'planned_quantity' => '',
                'used_quantity' => '0',
                'unit_rate' => '',
                'account_search' => '',
            ];
        }
    }

    public function removeBoqItem($index)
    {
        unset($this->boq_items[$index]);
        $this->boq_items = array_values($this->boq_items);
    }

    public function selectItemAccount($accountId, $itemIndex)
    {
        $account = HeadOfAccount::find($accountId);
        if ($account && isset($this->boq_items[$itemIndex])) {
            // Check for duplicates
            $exists = collect($this->boq_items)->contains(function($item, $idx) use ($accountId, $itemIndex) {
                return $idx != $itemIndex && isset($item['head_of_account_id']) && $item['head_of_account_id'] == $accountId;
            });
            
            $existsInRecords = collect($this->boq_records)->contains(function($record) use ($accountId) {
                return isset($record['head_of_account_id']) && $record['head_of_account_id'] == $accountId;
            });
            
            if ($exists || $existsInRecords) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'This account already exists in BOQ records.'
                ]);
                return;
            }
            
            $this->boq_items[$itemIndex]['head_of_account_id'] = $account->id;
            $this->boq_items[$itemIndex]['account_name'] = $account->account_name;
            $this->boq_items[$itemIndex]['full_path'] = $account->full_path ?? $account->account_name;
            $this->boq_items[$itemIndex]['unit_rate'] = $account->last_rate ?? '';
            $this->boq_items[$itemIndex]['account_search'] = '';
        }
    }

    public function getItemAccountResults($searchTerm)
    {
        if (strlen($searchTerm) < 2) {
            return [];
        }

        return HeadOfAccount::where('status', 'active')
            ->where('is_boq', true)
            ->where('account_level', '4')
            ->where('account_name', 'like', "%{$searchTerm}%")
            ->orderBy('account_name', 'asc')
            ->limit(10)
            ->get()
            ->map(function($account) {
                return [
                    'id' => $account->id,
                    'account_name' => $account->account_name,
                    'full_path' => $account->full_path ?? $account->account_name,
                ];
            })
            ->toArray();
    }

    public function editBoqRecord($recordId)
    {
        $record = BoqRecord::find($recordId);
        if ($record) {
            // Convert saved record to editable item
            $this->boq_items[] = [
                'id' => $record->id, // Keep ID for update
                'head_of_account_id' => $record->head_of_account_id,
                'account_name' => $record->headOfAccount->account_name ?? '',
                'full_path' => $record->headOfAccount->full_path ?? $record->headOfAccount->account_name ?? '',
                'planned_quantity' => $record->planned_quantity,
                'used_quantity' => $record->used_quantity,
                'unit_rate' => $record->unit_rate,
                'account_search' => '',
            ];
            
            // Remove from saved records (will be updated when saved)
            $this->boq_records = array_filter($this->boq_records, function($r) use ($recordId) {
                return $r['id'] != $recordId;
            });
            $this->boq_records = array_values($this->boq_records);
        }
    }

    public function deleteBoqRecord($recordId)
    {
        try {
            $record = BoqRecord::findOrFail($recordId);
            $record->delete();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'BOQ record deleted successfully!'
            ]);

            $this->loadBoqRecords();
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error deleting BOQ record: ' . $e->getMessage()
            ]);
        }
    }

    public function showChangeHistory($recordId)
    {
        $record = BoqRecord::with('headOfAccount')->findOrFail($recordId);
        $this->selected_record_name = $record->headOfAccount->account_name ?? 'N/A';
        $this->selected_record_history = $record->change_history ?? [];
        $this->show_history_modal = true;
    }

    public function closeHistoryModal()
    {
        $this->show_history_modal = false;
        $this->selected_record_history = [];
        $this->selected_record_name = '';
    }

    public function saveBoqRecords()
    {
        if (!$this->selected_project_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please select a project first.'
            ]);
            return;
        }

        if (count($this->boq_items) == 0) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please add at least one BOQ record.'
            ]);
            return;
        }

        // Validate all items
        foreach ($this->boq_items as $index => $item) {
            if (empty($item['head_of_account_id'])) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => "Row " . ($index + 1) . ": Please select a head of account."
                ]);
                return;
            }
            
            if (empty($item['planned_quantity']) || !is_numeric($item['planned_quantity']) || $item['planned_quantity'] < 0) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => "Row " . ($index + 1) . ": Planned quantity is required and must be a valid number."
                ]);
                return;
            }
            
            // Used quantity is optional, default to 0 if empty
            $usedQuantity = !empty($item['used_quantity']) && is_numeric($item['used_quantity']) 
                ? floatval($item['used_quantity']) 
                : 0;
            
            if ($usedQuantity < 0) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => "Row " . ($index + 1) . ": Used quantity cannot be negative."
                ]);
                return;
            }
            
            if ($usedQuantity > $item['planned_quantity']) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => "Row " . ($index + 1) . ": Used quantity cannot exceed planned quantity."
                ]);
                return;
            }
            
            if (empty($item['unit_rate']) || !is_numeric($item['unit_rate']) || $item['unit_rate'] < 0) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => "Row " . ($index + 1) . ": Unit rate is required and must be a valid number."
                ]);
                return;
            }
        }

        try {
            DB::beginTransaction();

            foreach ($this->boq_items as $item) {
                // Used quantity is optional, default to 0 if empty
                $usedQuantity = !empty($item['used_quantity']) && is_numeric($item['used_quantity']) 
                    ? floatval($item['used_quantity']) 
                    : 0;
                
                if (isset($item['id'])) {
                    // Update existing record
                    $record = BoqRecord::findOrFail($item['id']);
                    $record->update([
                        'head_of_account_id' => $item['head_of_account_id'],
                        'planned_quantity' => $item['planned_quantity'],
                        'used_quantity' => $usedQuantity,
                        'unit_rate' => $item['unit_rate'],
                    ]);
                } else {
                    // Check for duplicate
                    $duplicate = BoqRecord::where('project_id', $this->selected_project_id)
                        ->where('head_of_account_id', $item['head_of_account_id'])
                        ->first();
                    
                    if ($duplicate) {
                        $this->dispatch('show-alert', [
                            'type' => 'error',
                            'message' => 'Account "' . $item['account_name'] . '" already exists for this project.'
                        ]);
                        DB::rollBack();
                        return;
                    }

                    // Create new record
                    BoqRecord::create([
                        'project_id' => $this->selected_project_id,
                        'head_of_account_id' => $item['head_of_account_id'],
                        'planned_quantity' => $item['planned_quantity'],
                        'used_quantity' => $usedQuantity,
                        'unit_rate' => $item['unit_rate'],
                    ]);
                }

                // Update last_rate in head of account
                $account = HeadOfAccount::find($item['head_of_account_id']);
                if ($account) {
                    $account->update(['last_rate' => $item['unit_rate']]);
                }
            }

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'BOQ records saved successfully!'
            ]);

            $this->boq_items = [];
            $this->loadBoqRecords();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error saving BOQ records: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.boq.index');
    }
}
