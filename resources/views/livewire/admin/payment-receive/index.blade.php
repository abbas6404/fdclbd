<div class="container-fluid">
    <!-- Top Header -->
    @if($view_mode === 'list')
    <div class="card shadow mb-3">
        <div class="card-header bg-white py-1">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h6 class="card-title mb-0 text-primary" style="font-size: 0.95rem;">
                        <i class="fas fa-money-bill-wave me-2"></i> Payment Receive
                    </h6>
                </div>
                <div class="col-md-6 ms-auto">
                    <input type="text" 
                           class="form-control form-control-sm" 
                           wire:model.live.debounce.300ms="customer_search" 
                           placeholder="Search by customer, flat, project, or sale number..." 
                           autocomplete="new-password">
                </div>
            </div>
        </div>
        <!-- Pending Payments Table in Card Body -->
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-hover table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Customer</th>
                            <th>Sale Number</th>
                            <th>Project</th>
                            <th>Flat</th>
                            <th>Pending Terms</th>
                            <th>Total Remaining</th>
                            <th>Earliest Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer_results as $item)
                        @php
                            $dueDate = $item['earliest_due_date'] ? \Carbon\Carbon::parse($item['earliest_due_date']) : null;
                            $isOverdue = $dueDate && $dueDate->isPast();
                        @endphp
                        <tr class="{{ $isOverdue ? 'table-danger' : '' }} search-item" 
                            style="cursor: pointer;"
                            wire:click="selectCustomerFromList({{ $item['customer_id'] }})">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-user text-primary"></i>
                                    <div>
                                        <strong>{{ $item['customer_name'] ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $item['customer_phone'] ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $item['sale_number'] ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $item['project_name'] ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($item['project_address'] ?? 'N/A', 30) }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $item['flat_number'] ?? 'N/A' }}</span>
                                @if($item['flat_type'] && $item['flat_type'] !== 'N/A')
                                    <br><small class="text-muted">{{ $item['flat_type'] }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">{{ $item['pending_count'] ?? 0 }}</span>
                            </td>
                            <td class="text-end fw-bold text-primary">
                                ৳{{ number_format($item['total_remaining'] ?? 0, 0) }}
                            </td>
                            <td class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                @if($dueDate)
                                    {{ $dueDate->format('d M Y') }}
                                    @if($isOverdue)
                                        <br><small class="badge bg-danger">Overdue</small>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-search fa-3x mb-3"></i>
                                    <p class="mb-2">No pending payments found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($view_mode === 'detail')
        <!-- Detail View -->
        <div class="card shadow">
            <div class="card-header bg-white py-2">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h6 class="card-title mb-0 text-primary">
                            <i class="fas fa-file-invoice-dollar me-2"></i> Payment Details
                        </h6>
                    </div>
                    @if($selected_customer)
                    <div class="col">
                        <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-user text-info"></i>
                                <strong class="text-primary">{{ $selected_customer['name'] ?? 'N/A' }}</strong>
                            </div>
                            <span class="text-muted d-none d-md-inline">|</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-phone text-success"></i>
                                <span class="text-dark">{{ $selected_customer['phone'] ?? 'N/A' }}</span>
                            </div>
                            <span class="text-muted d-none d-md-inline">|</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-building text-primary"></i>
                                <strong class="text-primary">{{ $selected_customer['project_name'] ?? 'N/A' }}</strong>
                            </div>
                            <span class="text-muted d-none d-md-inline">|</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                <span class="text-dark" style="font-size: 0.9rem;">{{ Str::limit($selected_customer['project_address'] ?? 'N/A', 40) }}</span>
                            </div>
                            <span class="text-muted d-none d-md-inline">|</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-home text-success"></i>
                                <span class="text-dark fw-semibold">{{ $selected_customer['flat_number'] ?? 'N/A' }}</span>
                            </div>
                            @if(isset($selected_customer['flat_type']) && $selected_customer['flat_type'] !== 'N/A')
                                <span class="badge bg-secondary">{{ $selected_customer['flat_type'] }}</span>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="col-auto">
                        <button type="button" 
                                class="btn btn-sm btn-primary" 
                                wire:click="toggleShowAllSchedules"
                                title="{{ $show_all_schedules ? 'Show Only Pending' : 'Show All Schedules' }}">
                            <i class="fas fa-eye me-1"></i> View
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
            <div class="row">
                <!-- Left Column -->
                <div class="col-12 px-0">
                    <!-- Pending Payment Schedules Card -->
                    <div class="card border" style="overflow: visible;">
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

                    <!-- Payment Summary Card -->
                    <div class="card border mt-3">
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
    @endif
    
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

