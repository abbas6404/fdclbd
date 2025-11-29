<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white py-1">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-money-bill-wave me-2"></i> Payment Receive
                </h6>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-7 px-0">
                    <!-- Pending Payment Schedules Card -->
                    <div class="card border">
                        <div class="card-header bg-light py-2">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="mb-0">
                                        
                                        @if($selected_customer)
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary" 
                                                wire:click="toggleShowAllSchedules"
                                                title="{{ $show_all_schedules ? 'Show Only Pending' : 'Show All Schedules' }}">
                                            <i class="fas fa-{{ $show_all_schedules ? 'eye-slash' : 'eye' }} me-1"></i>
                                            {{ $show_all_schedules ? '' : '' }}
                                        </button>
                                        @endif

                                        @if($show_all_schedules)
                                            All Schedules
                                        @else
                                            Pending Schedules
                                        @endif
                                    </h6>
                                  
                                </div>
                                
                                <!-- Customer Search/Selection in Header -->
                                <div class="flex-grow-1 mx-3">
                                    @if($selected_customer)
                                        <div class="d-flex align-items-center gap-2 p-1 bg-info bg-opacity-10 rounded">
                                            <i class="fas fa-user text-primary"></i>
                                            <strong class="text-primary">{{ $selected_customer['name'] }}</strong>
                                            <small class="text-muted">- {{ $selected_customer['phone'] }}</small>
                                            <small class="text-muted">- {{ $selected_customer['flat_number'] ?? 'N/A' }}</small>
                                            <button type="button" class="btn btn-sm btn-outline-danger p-1 ms-auto" wire:click="clearCustomer" title="Clear Customer">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @else
                                        <input type="text" 
                                               id="customer-search" 
                                               class="form-control form-control-sm" 
                                       wire:model.live.debounce.300ms="customer_search" 
                                       wire:click="showRecentCustomers"
                                       placeholder="Search by name, phone, or email..." 
                                       autocomplete="new-password">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($selected_customer && count($pending_schedules) > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%;"></th>
                                            <th>Flat</th>
                                            <th>Term</th>
                                            <th>Receivable</th>
                                            <th>Received</th>
                                            <th>Remaining</th>
                                            <th>Due Date</th>
                                            <th>Payment Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pending_schedules as $schedule)
                                        @php
                                            $isSelected = collect($selected_schedules)->contains(function($item) use ($schedule) {
                                                return $item['schedule_id'] == $schedule['id'];
                                            });
                                            $selectedIndex = collect($selected_schedules)->search(function($item) use ($schedule) {
                                                return $item['schedule_id'] == $schedule['id'];
                                            });
                                            $paymentAmount = $isSelected && $selectedIndex !== false ? $this->selected_schedules[$selectedIndex]['amount'] : 0;
                                            $dueDate = $schedule['due_date'] ? \Carbon\Carbon::parse($schedule['due_date']) : null;
                                            $isOverdue = $dueDate && $dueDate->isPast();
                                            $isPaid = isset($schedule['status']) && $schedule['status'] === 'paid';
                                        @endphp
                                        <tr class="{{ $isSelected ? 'table-primary' : ($isOverdue ? 'table-danger' : ($isPaid ? 'table-success' : '')) }}">
                                            <td class="text-center align-middle">
                                                <input type="checkbox" 
                                                       {{ $isSelected ? 'checked' : '' }}
                                                       wire:click="toggleSchedule({{ $schedule['id'] }})"
                                                       style="width: 20px; height: 20px; cursor: pointer;">
                                            </td>
                                            <td>{{ $schedule['flat_number'] }}</td>
                                            <td>{{ $schedule['term_name'] }}</td>
                                            <td>{{ number_format($schedule['receivable_amount'], 0) }}</td>
                                            <td>{{ number_format($schedule['received_amount'], 0) }}</td>
                                            <td class="fw-bold">{{ number_format($schedule['remaining_amount'], 0) }}</td>
                                            <td class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                                {{ $dueDate ? $dueDate->format('d M Y') : 'N/A' }}
                                            </td>
                                            <td class="p-0">
                                                @if($isSelected)
                                                <input type="number" 
                                                       class="form-control form-control-sm border-0 rounded-0" 
                                                       wire:model.live="selected_schedules.{{ $selectedIndex }}.amount"
                                                       min="1" 
                                                       max="{{ $schedule['remaining_amount'] }}"
                                                       style="width: 100%; height: 100%; min-height: 38px;">
                                                @else
                                                <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                        </div>
                            @elseif($selected_customer && count($pending_schedules) === 0)
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                                <p class="mb-0">No pending payment schedules found for this customer.</p>
                                </div>
                            @else
                            <div class="text-center text-muted py-5">
                                    <i class="fas fa-user fa-2x mb-2"></i>
                                <p class="mb-0">Please select a customer to view pending payment schedules.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Cheque Entry Card -->
                    <div class="card border mt-3">
                        <div class="card-header bg-light py-1 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-money-check-alt me-1"></i> Cheque Entry</h6>
                            <button type="button" 
                                    class="btn btn-sm btn-primary" 
                                    wire:click="addEmptyCheque"
                                    wire:loading.attr="disabled"
                                    {{ !$selected_customer ? 'disabled' : '' }}>
                                <i class="fas fa-plus me-1"></i> Add Cheque
                            </button>
                        </div>
                        <div class="card-body p-0">
                            @if(count($cheques) > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Cheque Number</th>
                                            <th>Bank Name</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cheques as $index => $cheque)
                                        <tr>
                                            <td class="p-0">
                                                <input type="text" 
                                                       class="form-control form-control-sm border-0 rounded-0" 
                                                       wire:model.live="cheques.{{ $index }}.cheque_number"
                                                       placeholder="Enter cheque number"
                                                       style="width: 100%; height: 100%; min-height: 38px;">
                                            </td>
                                            <td class="p-0">
                                                <input type="text" 
                                                       class="form-control form-control-sm border-0 rounded-0" 
                                                       wire:model.live="cheques.{{ $index }}.bank_name"
                                                       placeholder="Enter bank name"
                                                       style="width: 100%; height: 100%; min-height: 38px;">
                                            </td>
                                            <td class="p-0">
                                                <input type="number" 
                                                       class="form-control form-control-sm border-0 rounded-0 text-end" 
                                                       wire:model.live="cheques.{{ $index }}.cheque_amount"
                                                       placeholder="Enter amount" 
                                                       min="1" 
                                                       step="1"
                                                       style="width: 100%; height: 100%; min-height: 38px;">
                                            </td>
                                            <td class="p-0">
                                                <input type="date" 
                                                       class="form-control form-control-sm border-0 rounded-0" 
                                                       wire:model.live="cheques.{{ $index }}.cheque_date"
                                                       style="width: 100%; height: 100%; min-height: 38px;">
                                            </td>
                                            <td class="align-middle text-center">
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        wire:click="removeCheque({{ $index }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="2" class="text-end">Total Cheque Amount:</th>
                                            <th class="text-end">
                                                @php
                                                    $total = 0;
                                                    foreach($cheques as $cheque) {
                                                        if (is_numeric($cheque['cheque_amount'])) {
                                                            $total += (int) $cheque['cheque_amount'];
                                                        }
                                                    }
                                                @endphp
                                                {{ number_format($total, 0) }}
                                            </th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-money-check-alt fa-2x mb-2"></i>
                                <p class="mb-0">Click "Add Cheque" to add cheque entries</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-5">
                    <!-- Search Results Area -->
                    <div class="card border mb-3" id="search-results-container">
                        <div class="card-header bg-primary text-white py-1">
                            <h6 class="mb-0">
                                <i class="fas fa-search me-1"></i> 
                                @if($active_search_type === 'recent')
                                    Recent Customers by Due Date ({{ count($customer_results) }})
                                @else
                                    Search Results
                                @endif
                            </h6>
                        </div>
                        <div class="card-body p-0" style="height: 300px; overflow-y: auto;" id="search-results-body">
                            @if(count($customer_results) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="small">Name</th>
                                                <th class="small">Phone</th>
                                                <th class="small">Email</th>
                                                <th class="small">NID</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                @foreach($customer_results as $result)
                                            <tr class="search-item" 
                                     wire:click="selectCustomer({{ $result['id'] }})"
                                     style="cursor: pointer;">
                                                <td class="small text-nowrap arrow-indicator" title="{{ $result['name'] ?? 'N/A' }}">
                                                    <span class="arrow-icon">▶</span>
                                                    <strong>{{ $result['name'] ?? 'N/A' }}</strong>
                                                </td>
                                                <td class="small text-nowrap">
                                                    {{ $result['phone'] ?? 'N/A' }}
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['email'] ?? 'N/A' }}">
                                                    {{ Str::limit($result['email'] ?? 'N/A', 25) }}
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['nid_or_passport_number'] ?? 'N/A' }}">
                                                    {{ Str::limit($result['nid_or_passport_number'] ?? 'N/A', 20) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-3 text-center text-muted">
                                    <i class="fas fa-search fa-2x mb-2"></i>
                                    <p>
                                        @if($active_search_type === 'recent')
                                            No customers with pending payments found
                                        @else
                                            Start searching to see results
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Summary Card -->
                    <div class="card border">
                        <div class="card-header bg-primary text-white py-1">
                            <h6 class="mb-0"><i class="fas fa-file-invoice-dollar me-1"></i> Payment Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <label class="col-sm-5 col-form-label">Customer</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm" 
                                           value="{{ $selected_customer['name'] ?? 'N/A' }}" 
                                           readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-5 col-form-label">Total Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm text-end fw-bold" 
                                           value="{{ number_format($total_payment_amount, 0) }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-5 col-form-label">Payment Method<span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    <select class="form-control form-control-sm @error('payment_method') is-invalid @enderror" 
                                            wire:model="payment_method"
                                            {{ !$selected_customer || count($selected_schedules) === 0 ? 'disabled' : '' }}>
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="card">Card</option>
                                        <option value="mobile_banking">Mobile Banking</option>
                                    </select>
                                    @error('payment_method') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-5 col-form-label">Remark</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control form-control-sm" 
                                              wire:model="remark" 
                                              rows="2" 
                                              placeholder="Enter any remarks..."
                                              {{ !$selected_customer || count($selected_schedules) === 0 ? 'disabled' : '' }}></textarea>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center gap-2 mt-4">
                                <button type="button" class="btn btn-sm btn-primary" wire:click="receivePayment" 
                                        wire:loading.attr="disabled"
                                        wire:target="receivePayment">
                                    <i class="fas fa-save me-1"></i> 
                                    <span wire:loading.remove wire:target="receivePayment">Save</span>
                                    <span wire:loading wire:target="receivePayment">Saving...</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" wire:click="saveAndPrint" 
                                        wire:loading.attr="disabled"
                                        wire:target="saveAndPrint">
                                    <i class="fas fa-print me-1"></i> 
                                    <span wire:loading.remove wire:target="saveAndPrint">Save & Print</span>
                                    <span wire:loading wire:target="saveAndPrint">Saving...</span>
                                </button>
                            </div>
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
            Livewire.on('print-payment-invoice', (data) => {
                console.log('Print payment invoice event received:', data);
                
                // Handle Livewire v3 named parameter syntax
                let invoiceId = null;
                
                if (Array.isArray(data)) {
                    const firstItem = data[0];
                    invoiceId = firstItem?.invoice_id || firstItem;
                } else if (typeof data === 'object' && data !== null) {
                    invoiceId = data.invoice_id;
                } else {
                    invoiceId = data;
                }
                
                console.log('Extracted invoice ID:', invoiceId);
                
                if (invoiceId) {
                    const printUrl = '{{ route("admin.print-templates.payment-invoice") }}?invoice_id=' + invoiceId;
                    console.log('Opening print URL:', printUrl);
                    
                    // Use globalPrint function if available, otherwise open in new window
                    if (typeof globalPrint === 'function') {
                        globalPrint(printUrl, { method: 'iframe', autoPrint: true });
                    } else {
                        // Fallback: open in new window
                        window.open(printUrl, '_blank');
                    }
                } else {
                    console.error('No invoice ID found in event data:', data);
                    alert('Error: Invoice ID not found. Please try again.');
                }
            });
        });
    </script>
</div>

