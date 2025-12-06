<?php

namespace App\Livewire\Admin\PaymentSchedules;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\FlatSale;
use App\Models\FlatSalePaymentSchedule;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithFileUploads;

    // Search fields
    public $sale_search = '';
    public $sale_results = [];
    public $selected_sale_id = '';
    public $selected_flat_id = null; // Store flat_id from selected sale
    public $selected_sale = null;
    
    // Payment schedule items
    public $schedule_items = [];
    
    // Document modal
    public $show_document_modal = false;
    public $document_attachments = [];
    public $existing_attachments = [];

    public function mount()
    {
        // Load recent 20 flat sales by default
        $this->loadRecentSales();
        
        // Check if sale_id is passed in query string
        if (request()->has('sale_id')) {
            $saleId = request()->get('sale_id');
            $this->selectSale($saleId);
        }
    }

    public function loadRecentSales()
    {
        $this->sale_results = FlatSale::with(['customer', 'flat.project', 'salesAgent'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($sale) {
                $project = $sale->flat->project ?? null;
                return [
                    'id' => $sale->id,
                    'sale_number' => $sale->sale_number,
                    'project_name' => $project->project_name ?? 'N/A',
                    'flat_number' => $sale->flat->flat_number ?? 'N/A',
                    'customer_name' => $sale->customer->name ?? 'N/A',
                    'customer_phone' => $sale->customer->phone ?? 'N/A',
                    'customer_nid' => $sale->customer->nid_or_passport_number ?? 'N/A',
                ];
            })
            ->toArray();
    }

    public function updatedSaleSearch()
    {
        if (strlen($this->sale_search) < 2) {
            // Show recent sales when search is cleared
            $this->loadRecentSales();
            return;
        }

        $this->sale_results = FlatSale::with(['customer', 'flat.project', 'salesAgent'])
            ->where(function($query) {
                $query->where('sale_number', 'like', "%{$this->sale_search}%")
                      ->orWhereHas('customer', function($q) {
                          $q->where('name', 'like', "%{$this->sale_search}%")
                            ->orWhere('phone', 'like', "%{$this->sale_search}%");
                      })
                      ->orWhereHas('flat', function($q) {
                          $q->where('flat_number', 'like', "%{$this->sale_search}%")
                            ->orWhereHas('project', function($projectQuery) {
                                $projectQuery->where('project_name', 'like', "%{$this->sale_search}%");
                            });
                      });
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($sale) {
                $project = $sale->flat->project ?? null;
                return [
                    'id' => $sale->id,
                    'sale_number' => $sale->sale_number,
                    'project_name' => $project->project_name ?? 'N/A',
                    'flat_number' => $sale->flat->flat_number ?? 'N/A',
                    'customer_name' => $sale->customer->name ?? 'N/A',
                    'customer_phone' => $sale->customer->phone ?? 'N/A',
                    'customer_nid' => $sale->customer->nid_or_passport_number ?? 'N/A',
                ];
            })
            ->toArray();
    }

    public function selectSale($saleId)
    {
        $sale = FlatSale::with(['customer', 'flat.project', 'salesAgent'])->find($saleId);
        if ($sale) {
            $this->selected_sale_id = $sale->id;
            $this->selected_flat_id = $sale->flat_id; // Store flat_id
            $project = $sale->flat->project ?? null;
            $this->selected_sale = [
                'id' => $sale->id,
                'sale_number' => $sale->sale_number,
                'project_name' => $project->project_name ?? 'N/A',
                'project_address' => $project->address ?? 'N/A',
                'flat_number' => $sale->flat->flat_number ?? 'N/A',
                'flat_type' => $sale->flat->flat_type ?? 'N/A',
                'customer_name' => $sale->customer->name ?? 'N/A',
                'customer_phone' => $sale->customer->phone ?? 'N/A',
            ];
            $this->sale_search = $sale->sale_number;
            $this->sale_results = [];
            
            // Load existing payment schedules
            $this->loadSchedules();
        }
    }

    public function loadSchedules()
    {
        if ($this->selected_flat_id) {
            $schedules = FlatSalePaymentSchedule::where('flat_id', $this->selected_flat_id)
                ->orderBy('due_date', 'asc')
                ->get()
                ->map(function($schedule) {
                    return [
                        'id' => $schedule->id,
                        'term_name' => $schedule->term_name,
                        'receivable_amount' => $schedule->receivable_amount,
                        'received_amount' => $schedule->received_amount ?? 0,
                        'due_date' => $schedule->due_date ? $schedule->due_date->format('Y-m-d') : '',
                        'status' => $schedule->status ?? 'pending',
                    ];
                })
                ->toArray();
            
            $this->schedule_items = $schedules;
        }
    }

    public function addEmptyTerm()
    {
        if (!$this->selected_flat_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please select a flat sale first.'
            ]);
            return;
        }

        $this->schedule_items[] = [
            'id' => null, // New item
            'term_name' => '',
            'receivable_amount' => '',
            'received_amount' => 0,
            'due_date' => '',
            'status' => 'pending',
        ];
    }

    public function updateScheduleItem($index, $field, $value)
    {
        if (isset($this->schedule_items[$index])) {
            if ($field === 'receivable_amount' || $field === 'received_amount') {
                $this->schedule_items[$index][$field] = $value !== '' ? (float) $value : '';
            } else {
                $this->schedule_items[$index][$field] = $value;
            }
        }
    }

    public function removeScheduleItem($index)
    {
        if (isset($this->schedule_items[$index])) {
            $item = $this->schedule_items[$index];
            // If it's an existing schedule (has id), delete it from database
            if (!empty($item['id'])) {
                try {
                    FlatSalePaymentSchedule::find($item['id'])->delete();
                } catch (\Exception $e) {
                    // Ignore if already deleted
                }
            }
        }
        unset($this->schedule_items[$index]);
        $this->schedule_items = array_values($this->schedule_items);
    }

    public function saveSchedule()
    {
        if (!$this->selected_flat_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please select a flat sale first.'
            ]);
            return;
        }

        if (count($this->schedule_items) === 0) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please add at least one payment term.'
            ]);
            return;
        }

        // Validate all schedule items
        foreach ($this->schedule_items as $index => $item) {
            if (empty($item['term_name']) || empty($item['receivable_amount']) || empty($item['due_date'])) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Please fill in all required fields for all payment terms.'
                ]);
                return;
            }
        }

        try {
            DB::beginTransaction();

            // Get existing schedule IDs from database
            $existingScheduleIds = FlatSalePaymentSchedule::where('flat_id', $this->selected_flat_id)
                ->pluck('id')
                ->toArray();
            
            // Get IDs from current schedule items
            $currentScheduleIds = collect($this->schedule_items)
                ->pluck('id')
                ->filter()
                ->toArray();
            
            // Delete schedules that are no longer in the list
            $idsToDelete = array_diff($existingScheduleIds, $currentScheduleIds);
            if (!empty($idsToDelete)) {
                FlatSalePaymentSchedule::whereIn('id', $idsToDelete)->delete();
            }

            // Update or create schedules
            foreach ($this->schedule_items as $item) {
                if (!empty($item['id'])) {
                    // Update existing schedule
                    $schedule = FlatSalePaymentSchedule::find($item['id']);
                    if ($schedule) {
                        $schedule->update([
                            'term_name' => $item['term_name'],
                            'receivable_amount' => $item['receivable_amount'],
                            'received_amount' => $item['received_amount'] ?? 0,
                            'due_date' => $item['due_date'],
                            'status' => $item['status'] ?? 'pending',
                            'updated_by' => Auth::id(),
                        ]);
                    }
                } else {
                    // Create new schedule
                    FlatSalePaymentSchedule::create([
                        'flat_id' => $this->selected_flat_id,
                        'term_name' => $item['term_name'],
                        'receivable_amount' => $item['receivable_amount'],
                        'received_amount' => $item['received_amount'] ?? 0,
                        'due_date' => $item['due_date'],
                        'status' => $item['status'] ?? 'pending',
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Payment schedule saved successfully!'
            ]);

            // Reload schedules
            $this->loadSchedules();

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error saving payment schedule: ' . $e->getMessage()
            ]);
        }
    }

    public function saveAndPrint()
    {
        if (!$this->selected_flat_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please select a flat sale first.'
            ]);
            return;
        }

        if (count($this->schedule_items) === 0) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please add at least one payment term.'
            ]);
            return;
        }

        // Validate all schedule items
        foreach ($this->schedule_items as $index => $item) {
            if (empty($item['term_name']) || empty($item['receivable_amount']) || empty($item['due_date'])) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Please fill in all required fields for all payment terms.'
                ]);
                return;
            }
        }

        try {
            DB::beginTransaction();

            // Get existing schedule IDs from database
            $existingScheduleIds = FlatSalePaymentSchedule::where('flat_id', $this->selected_flat_id)
                ->pluck('id')
                ->toArray();
            
            // Get IDs from current schedule items
            $currentScheduleIds = collect($this->schedule_items)
                ->pluck('id')
                ->filter()
                ->toArray();
            
            // Delete schedules that are no longer in the list
            $idsToDelete = array_diff($existingScheduleIds, $currentScheduleIds);
            if (!empty($idsToDelete)) {
                FlatSalePaymentSchedule::whereIn('id', $idsToDelete)->delete();
            }

            // Update or create schedules
            foreach ($this->schedule_items as $item) {
                if (!empty($item['id'])) {
                    // Update existing schedule
                    $schedule = FlatSalePaymentSchedule::find($item['id']);
                    if ($schedule) {
                        $schedule->update([
                            'term_name' => $item['term_name'],
                            'receivable_amount' => $item['receivable_amount'],
                            'received_amount' => $item['received_amount'] ?? 0,
                            'due_date' => $item['due_date'],
                            'status' => $item['status'] ?? 'pending',
                            'updated_by' => Auth::id(),
                        ]);
                    }
                } else {
                    // Create new schedule
                    FlatSalePaymentSchedule::create([
                        'flat_id' => $this->selected_flat_id,
                        'term_name' => $item['term_name'],
                        'receivable_amount' => $item['receivable_amount'],
                        'received_amount' => $item['received_amount'] ?? 0,
                        'due_date' => $item['due_date'],
                        'status' => $item['status'] ?? 'pending',
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Payment schedule saved successfully!'
            ]);

            // Reload schedules
            $this->loadSchedules();
            
            // Trigger print with sale ID - using Livewire v3 syntax
            $this->dispatch('print-schedule', sale_id: $this->selected_sale_id);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error saving payment schedule: ' . $e->getMessage()
            ]);
        }
    }

    public function clearSale()
    {
        $this->selected_sale_id = '';
        $this->selected_sale = null;
        $this->sale_search = '';
        $this->schedule_items = [];
        // Reload recent sales after clearing
        $this->loadRecentSales();
    }

    // Document modal methods
    public function openDocumentModal()
    {
        if (!$this->selected_sale_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please select a flat sale first.'
            ]);
            return;
        }
        
        // Load existing attachments
        $sale = FlatSale::with('flat')->find($this->selected_sale_id);
        if ($sale && $sale->flat) {
            $this->existing_attachments = Attachment::where('flat_id', $sale->flat->id)
                ->orderBy('display_order', 'asc')
                ->get()
                ->map(function($attachment) {
                    return [
                        'id' => $attachment->id,
                        'document_name' => $attachment->document_name,
                        'file_path' => $attachment->file_path,
                        'file_size' => $attachment->file_size,
                        'is_existing' => true,
                    ];
                })
                ->toArray();
        }
        
        $this->show_document_modal = true;
        $this->document_attachments = [];
    }

    public function closeDocumentModal()
    {
        $this->show_document_modal = false;
        $this->document_attachments = [];
        $this->existing_attachments = [];
    }

    public function addDocumentAttachment()
    {
        $this->document_attachments[] = [
            'document_name' => '',
            'file' => null,
        ];
    }

    public function removeDocumentAttachment($index)
    {
        unset($this->document_attachments[$index]);
        $this->document_attachments = array_values($this->document_attachments);
    }

    public function removeExistingAttachment($attachmentId)
    {
        try {
            $attachment = Attachment::find($attachmentId);
            if ($attachment) {
                // Soft delete (model uses SoftDeletes trait)
                // File remains in storage, only record is marked as deleted
                $attachment->delete();
                
                // Remove from existing attachments array
                $this->existing_attachments = array_filter($this->existing_attachments, function($item) use ($attachmentId) {
                    return $item['id'] != $attachmentId;
                });
                $this->existing_attachments = array_values($this->existing_attachments);
                
                $this->dispatch('show-alert', [
                    'type' => 'success',
                    'message' => 'Document removed successfully!'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error removing document: ' . $e->getMessage()
            ]);
        }
    }

    public function saveDocuments()
    {
        if (!$this->selected_sale_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Please select a flat sale first.'
            ]);
            return;
        }

        // Check if there are any new documents to save
        $hasNewDocuments = false;
        foreach ($this->document_attachments as $attachment) {
            if (isset($attachment['file']) && $attachment['file']) {
                $hasNewDocuments = true;
                break;
            }
        }

        if (!$hasNewDocuments && empty($this->document_attachments)) {
            // No new documents to save, just close modal
            $this->closeDocumentModal();
            return;
        }

        try {
            $sale = FlatSale::with('flat')->find($this->selected_sale_id);
            if (!$sale || !$sale->flat) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Flat sale or flat not found.'
                ]);
                return;
            }

            $flatId = $sale->flat->id;
            $displayOrder = Attachment::where('flat_id', $flatId)->max('display_order') ?? 0;
            $savedCount = 0;

            foreach ($this->document_attachments as $attachment) {
                if (isset($attachment['file']) && $attachment['file']) {
                    $file = $attachment['file'];
                    
                    if (is_object($file) && method_exists($file, 'getClientOriginalName')) {
                        $extension = $file->getClientOriginalExtension();
                        $fileName = time() . '_' . uniqid() . '.' . $extension;
                        $filePath = $file->storeAs('document_soft_copy/flat_sale', $fileName, 'public');
                        
                        Attachment::create([
                            'document_name' => $attachment['document_name'] ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                            'file_path' => $filePath,
                            'file_size' => $file->getSize(),
                            'display_order' => ++$displayOrder,
                            'flat_id' => $flatId,
                        ]);
                        $savedCount++;
                    }
                }
            }

            if ($savedCount > 0) {
                $this->dispatch('show-alert', [
                    'type' => 'success',
                    'message' => "{$savedCount} document(s) saved successfully!"
                ]);
            }

            // Reload existing attachments
            $this->existing_attachments = Attachment::where('flat_id', $flatId)
                ->orderBy('display_order', 'asc')
                ->get()
                ->map(function($attachment) {
                    return [
                        'id' => $attachment->id,
                        'document_name' => $attachment->document_name,
                        'file_path' => $attachment->file_path,
                        'file_size' => $attachment->file_size,
                        'is_existing' => true,
                    ];
                })
                ->toArray();

            // Clear new attachments
            $this->document_attachments = [];

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Error saving documents: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.payment-schedules.index');
    }
}

