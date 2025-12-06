<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white py-1">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h6 class="card-title mb-0 text-primary">
                        <i class="fas fa-calendar-alt me-2"></i> Payment Schedule
                    </h6>
                </div>
                @if($selected_sale)
                <div class="col">
                    <div class="d-flex align-items-center justify-content-center gap-2 gap-md-3 flex-wrap">
                        @php
                            $items = [];
                            if (!empty($selected_sale['project_name']) && ($selected_sale['project_name'] ?? '') !== 'N/A') {
                                $items[] = 'project';
                            }
                            if (!empty($selected_sale['project_address']) && ($selected_sale['project_address'] ?? '') !== 'N/A') {
                                $items[] = 'address';
                            }
                            if (!empty($selected_sale['flat_number']) && ($selected_sale['flat_number'] ?? '') !== 'N/A') {
                                $items[] = 'flat';
                            }
                            if (!empty($selected_sale['customer_name']) && ($selected_sale['customer_name'] ?? '') !== 'N/A') {
                                $items[] = 'customer';
                            }
                            if (!empty($selected_sale['customer_phone']) && ($selected_sale['customer_phone'] ?? '') !== 'N/A') {
                                $items[] = 'phone';
                            }
                        @endphp
                        
                        @if(in_array('project', $items))
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-building text-primary"></i>
                                <strong class="text-primary">{{ $selected_sale['project_name'] }}</strong>
                            </div>
                        @endif
                        
                        @if(in_array('address', $items))
                            @if(in_array('project', $items))
                                <span class="text-muted d-none d-md-inline">|</span>
                            @endif
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                <span class="text-dark" style="font-size: 0.9rem;">{{ Str::limit($selected_sale['project_address'], 40) }}</span>
                            </div>
                        @endif
                        
                        @if(in_array('flat', $items))
                            @if(in_array('project', $items) || in_array('address', $items))
                                <span class="text-muted d-none d-md-inline">|</span>
                            @endif
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-home text-success"></i>
                                <span class="text-dark fw-semibold">{{ $selected_sale['flat_number'] }}</span>
                            </div>
                        @endif
                        
                        @if(isset($selected_sale['flat_type']) && $selected_sale['flat_type'] !== 'N/A')
                            <span class="badge bg-secondary">{{ $selected_sale['flat_type'] }}</span>
                        @endif
                        
                        @if(in_array('customer', $items))
                            @if(!empty(array_intersect(['project', 'address', 'flat'], $items)))
                                <span class="text-muted d-none d-md-inline">|</span>
                            @endif
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-user text-info"></i>
                                <span class="text-dark">{{ $selected_sale['customer_name'] }}</span>
                            </div>
                        @endif
                        
                        @if(in_array('phone', $items))
                            @if(!empty(array_intersect(['project', 'address', 'flat', 'customer'], $items)))
                                <span class="text-muted d-none d-md-inline">|</span>
                            @endif
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-phone text-success"></i>
                                <span class="text-dark">{{ $selected_sale['customer_phone'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
                @if($selected_sale)
                <div class="col-auto">
                    <button type="button" 
                            class="btn btn-sm btn-primary" 
                            wire:click="openDocumentModal">
                        <i class="fas fa-paperclip me-1"></i> Add Document
                    </button>
                </div>
                @else
                <div class="col-md-6 ms-auto">
                    <input type="text" 
                           id="sale-search" 
                           class="form-control form-control-sm" 
                           wire:model.live.debounce.300ms="sale_search" 
                           placeholder="Search by sale number, customer, or flat..." 
                           autocomplete="new-password">
                </div>
                @endif
            </div>
        </div>
        <div class="card-body py-3">
            <div class="row">
                <!-- Left Column -->
                <div class="col-12 px-0">
                    <!-- Payment Schedule Terms Card -->
                    <div class="card border">
                        <div class="card-body p-0">
                            @if($selected_sale)
                            <!-- Schedule Items Table -->
                            @if(count($schedule_items) > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th>Term Name <span class="text-danger">*</span></th>
                                            <th>Receivable Amount <span class="text-danger">*</span></th>
                                            <th>Due Date <span class="text-danger">*</span></th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($schedule_items as $index => $item)
                                        <tr>
                                            <td class="align-middle text-center">
                                                <strong>{{ $loop->iteration }}</strong>
                                            </td>
                                            <td class="p-0">
                                                <input type="text" 
                                                       class="form-control form-control-sm border-0 rounded-0" 
                                                       style="width: 100%; height: 100%; min-height: 38px;"
                                                       wire:model.blur="schedule_items.{{ $index }}.term_name" 
                                                       placeholder="e.g., Down Payment, 1st Installment">
                                            </td>
                                            <td class="p-0">
                                                <input type="number" 
                                                       class="form-control form-control-sm border-0 rounded-0" 
                                                       style="width: 100%; height: 100%; min-height: 38px;"
                                                       wire:model.blur="schedule_items.{{ $index }}.receivable_amount" 
                                                       placeholder="Amount" 
                                                       step="0.01" 
                                                       min="0">
                                            </td>
                                            <td class="p-0">
                                                <input type="date" 
                                                       class="form-control form-control-sm border-0 rounded-0" 
                                                       style="width: 100%; height: 100%; min-height: 38px;"
                                                       wire:model.blur="schedule_items.{{ $index }}.due_date">
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-{{ $item['status'] === 'paid' ? 'success' : ($item['status'] === 'partial' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($item['status'] ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        wire:click="removeScheduleItem({{ $index }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th></th>
                                            <th>Total</th>
                                            <th>
                                                @php
                                                    $receivableTotal = 0;
                                                    foreach($schedule_items as $item) {
                                                        $amount = $item['receivable_amount'] ?? 0;
                                                        $receivableTotal += is_numeric($amount) ? (float)$amount : 0;
                                                    }
                                                @endphp
                                                {{ number_format($receivableTotal, 2) }}
                                            </th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="mt-3 p-3 d-flex justify-content-between align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-primary" wire:click="addEmptyTerm">
                                    <i class="fas fa-plus me-1"></i> Add Term
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-success" wire:click="saveSchedule" 
                                            wire:loading.attr="disabled">
                                        <i class="fas fa-save me-1"></i> 
                                        <span wire:loading.remove wire:target="saveSchedule">Save</span>
                                        <span wire:loading wire:target="saveSchedule">Saving...</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary" wire:click="saveAndPrint" 
                                            wire:loading.attr="disabled">
                                        <i class="fas fa-print me-1"></i> 
                                        <span wire:loading.remove wire:target="saveAndPrint">Save & Print</span>
                                        <span wire:loading wire:target="saveAndPrint">Saving...</span>
                                    </button>
                                </div>
                            </div>
                            @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-calendar fa-2x mb-2"></i>
                                <p class="mb-0">Click "Add Term" button to add a new payment term</p>
                            </div>
                            @endif
                            @else
                            <!-- Show search results or empty message -->
                            @if(count($sale_results) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Sale Number</th>
                                            <th>Project Name</th>
                                            <th>Flat Number</th>
                                            <th>Customer Name</th>
                                            <th>Customer Phone</th>
                                            <th>Customer NID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sale_results as $result)
                                        <tr style="cursor: pointer;" 
                                            wire:click="selectSale({{ $result['id'] }})"
                                            class="search-item">
                                            <td>
                                                <span class="text-muted">{{ $loop->iteration }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $result['sale_number'] ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $result['project_name'] ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $result['flat_number'] ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $result['customer_name'] ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $result['customer_phone'] ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted small">{{ $result['customer_nid'] ?? 'N/A' }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <p class="mb-0">Search for a flat sale to set payment schedule</p>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    .search-item:hover {
        background-color: #f8f9fa;
    }
    .arrow-indicator {
        position: relative;
        padding-left: 1.0rem !important;
    }
    .arrow-icon {
        position: absolute;
        left: 0rem;
        color: transparent;
        font-size: 14px;
        transition: color 0.2s ease;
        line-height: 1.8;
    }
    .search-item:hover .arrow-icon {
        color: #28a745;
    }
    .table th.small,
    .table td.small {
        font-size: 1.0rem;
        padding: 0.3rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
        border-left: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
    }
    .table th.small:first-child,
    .table td.small:first-child {
        border-left: none;
    }
    .table th.small:last-child,
    .table td.small:last-child {
        border-right: none;
    }
    .table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
    }
    </style>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('print-schedule', (data) => {
                console.log('Print schedule event received:', data);
                
                // Handle Livewire v3 named parameter syntax
                // When using dispatch('event', param: value), data comes as { param: value }
                let saleId = null;
                
                if (Array.isArray(data)) {
                    // If it's an array, get first element
                    const firstItem = data[0];
                    saleId = firstItem?.sale_id || firstItem;
                } else if (typeof data === 'object' && data !== null) {
                    // If it's an object, check for sale_id property
                    saleId = data.sale_id;
                } else {
                    // If it's a primitive value, use it directly
                    saleId = data;
                }
                
                console.log('Extracted sale ID:', saleId);
                
                if (saleId) {
                    const printUrl = '{{ route("admin.print-templates.payment-schedule") }}?sale_id=' + saleId;
                    console.log('Opening print URL:', printUrl);
                    
                    // Use globalPrint function if available, otherwise open in new window
                    if (typeof globalPrint === 'function') {
                        globalPrint(printUrl, { method: 'iframe', autoPrint: true });
                    } else {
                        // Fallback: open in new window
                        window.open(printUrl, '_blank');
                    }
                } else {
                    console.error('No sale ID found in event data:', data);
                    alert('Error: Sale ID not found. Please try again.');
                }
            });
        });
    </script>

    <!-- Document Modal -->
    @if($show_document_modal)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-paperclip me-2"></i> Add Document
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeDocumentModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label mb-0 fw-bold text-primary">
                            <i class="fas fa-paperclip me-1"></i> Document Soft Copy
                        </label>
                        <button type="button" 
                                class="btn btn-sm btn-outline-primary" 
                                wire:click="addDocumentAttachment">
                            <i class="fas fa-plus me-1"></i> Add File
                        </button>
                    </div>
                    
                    @if(!empty($existing_attachments) || !empty($document_attachments))
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">#</th>
                                    <th>Document Name</th>
                                    <th>File</th>
                                    <th style="width: 100px;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Existing Documents -->
                                @foreach($existing_attachments as $attachment)
                                <tr class="table-info">
                                    <td class="text-center">
                                        <span class="text-muted">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $attachment['document_name'] }}</strong>
                                        <small class="text-muted d-block">(Existing)</small>
                                    </td>
                                    <td>
                                        <a href="{{ asset('storage/' . $attachment['file_path']) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> View/Download
                                        </a>
                                        <small class="text-muted d-block mt-1">
                                            {{ number_format($attachment['file_size'] / 1024, 2) }} KB
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-xs btn-outline-danger" 
                                                wire:click="removeExistingAttachment({{ $attachment['id'] }})"
                                                wire:confirm="Are you sure you want to delete this document?"
                                                title="Delete">
                                            <i class="fas fa-trash" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                
                                <!-- New Documents -->
                                @foreach($document_attachments as $index => $attachment)
                                <tr>
                                    <td class="text-center">
                                        <span class="text-muted">{{ count($existing_attachments) + $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               class="form-control form-control-sm" 
                                               wire:model.blur="document_attachments.{{ $index }}.document_name" 
                                               placeholder="Enter document name">
                                    </td>
                                    <td>
                                        <input type="file" 
                                               class="form-control form-control-sm" 
                                               accept="image/*,.pdf,.doc,.docx"
                                               wire:model="document_attachments.{{ $index }}.file">
                                        @if(isset($attachment['file']) && $attachment['file'])
                                            <small class="text-success d-block mt-1">
                                                <i class="fas fa-file me-1"></i>
                                                {{ is_string($attachment['file']) ? $attachment['file'] : $attachment['file']->getClientOriginalName() }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-xs btn-outline-danger" 
                                                wire:click="removeDocumentAttachment({{ $index }})"
                                                title="Remove">
                                            <i class="fas fa-trash" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-file fa-2x mb-2"></i>
                        <p class="mb-0">Click "Add File" button to add a new document</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeDocumentModal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="saveDocuments" wire:loading.attr="disabled">
                        <i class="fas fa-save me-1"></i> 
                        <span wire:loading.remove wire:target="saveDocuments">Save Documents</span>
                        <span wire:loading wire:target="saveDocuments">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>


