<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white py-1">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-money-check-alt me-2"></i> Cheque Management
                </h6>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Filters -->
            <div class="row mb-3 g-2">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control form-control-sm" 
                               placeholder="Search by cheque number or bank name..." 
                               wire:model.live.debounce.300ms="search">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" wire:model.live="bank_filter">
                        <option value="">All Banks</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank }}">{{ $bank }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" 
                           class="form-control form-control-sm" 
                           placeholder="Date From" 
                           wire:model.live="date_from">
                </div>
                <div class="col-md-2">
                    <input type="date" 
                           class="form-control form-control-sm" 
                           placeholder="Date To" 
                           wire:model.live="date_to">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" wire:click="clearFilters">
                        <i class="fas fa-times me-1"></i> Clear
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card border">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Total Cheques</small>
                                    <h6 class="mb-0">{{ count($cheques) }}</h6>
                                </div>
                                <i class="fas fa-money-check-alt fa-2x text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Total Amount</small>
                                    <h6 class="mb-0">{{ number_format(collect($cheques)->sum('cheque_amount'), 2) }} BDT</h6>
                                </div>
                                <i class="fas fa-dollar-sign fa-2x text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Used in Invoices</small>
                                    <h6 class="mb-0">{{ collect($cheques)->sum('invoice_count') }}</h6>
                                </div>
                                <i class="fas fa-file-invoice fa-2x text-info opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Unique Banks</small>
                                    <h6 class="mb-0">{{ count($banks) }}</h6>
                                </div>
                                <i class="fas fa-university fa-2x text-warning opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 15%;">Cheque Number</th>
                            <th style="width: 20%;">Bank Name</th>
                            <th style="width: 15%;" class="text-end">Amount</th>
                            <th style="width: 15%;" class="text-center">Cheque Date</th>
                            <th style="width: 10%;" class="text-center">Invoices</th>
                            <th style="width: 15%;" class="text-center">Created</th>
                            <th style="width: 10%;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cheques as $index => $cheque)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td><strong>{{ $cheque['cheque_number'] }}</strong></td>
                            <td>{{ $cheque['bank_name'] }}</td>
                            <td class="text-end">{{ number_format($cheque['cheque_amount'], 2) }} BDT</td>
                            <td class="text-center">{{ $cheque['cheque_date_formatted'] }}</td>
                            <td class="text-center">
                                @if($cheque['invoice_count'] > 0)
                                    <span class="badge bg-info" style="cursor: pointer;" wire:click="viewDetails({{ $cheque['id'] }})">
                                        {{ $cheque['invoice_count'] }} Invoice(s)
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Not Used</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <small>{{ $cheque['created_at'] }}</small><br>
                                <small class="text-muted">by {{ $cheque['created_by'] }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" 
                                            class="btn btn-sm btn-info" 
                                            wire:click="viewDetails({{ $cheque['id'] }})"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($cheque['invoice_count'] === 0)
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            wire:click="deleteCheque({{ $cheque['id'] }})"
                                            wire:confirm="Are you sure you want to delete this cheque?"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-money-check-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No cheques found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(count($cheques) > 0)
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th class="text-end">{{ number_format(collect($cheques)->sum('cheque_amount'), 2) }} BDT</th>
                            <th colspan="4"></th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    @if($show_details && $selected_cheque)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-money-check-alt me-2"></i> Cheque Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeDetails"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>Cheque Number:</strong> {{ $selected_cheque['cheque_number'] }}
                            </div>
                            <div class="mb-2">
                                <strong>Bank Name:</strong> {{ $selected_cheque['bank_name'] }}
                            </div>
                            <div class="mb-2">
                                <strong>Amount:</strong> {{ number_format($selected_cheque['cheque_amount'], 2) }} BDT
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>Cheque Date:</strong> {{ $selected_cheque['cheque_date'] }}
                            </div>
                            <div class="mb-2">
                                <strong>Created:</strong> {{ $selected_cheque['created_at'] }}
                            </div>
                            <div class="mb-2">
                                <strong>Created By:</strong> {{ $selected_cheque['created_by'] }}
                            </div>
                        </div>
                    </div>

                    @if(count($selected_cheque['invoices']) > 0)
                    <hr>
                    <h6 class="mb-3">Used in Invoices ({{ count($selected_cheque['invoices']) }})</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selected_cheque['invoices'] as $invoice)
                                <tr>
                                    <td>#{{ $invoice['invoice_number'] }}</td>
                                    <td>{{ $invoice['customer_name'] }}</td>
                                    <td class="text-end">{{ number_format($invoice['amount'], 2) }} BDT</td>
                                    <td class="text-center">{{ $invoice['date'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> This cheque is not used in any invoices.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeDetails">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
