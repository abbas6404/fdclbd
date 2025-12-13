<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white py-1">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-check-circle me-2"></i> Requisition Confirmation
                    @if($userApprovalLevel)
                        <span class="badge bg-info ms-2">{{ $userApprovalLevel->name }}</span>
                    @endif
                </h6>
            </div>
        </div>
        <div class="card-body py-3">
            @if(!$userApprovalLevel)
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>No Approval Level Assigned:</strong> You do not have an approval level assigned. Please contact the administrator to assign you an approval level.
                </div>
            @endif

            <!-- Fast Search -->
            <div class="row mb-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control form-control-sm" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="Fast search by requisition number, employee, or project...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" wire:model.live="statusFilter">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="completed">Completed</option>
                        <option value="all">All Status</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" wire:model.live="perPage">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
            </div>

            <!-- Requisitions Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th wire:click="sortBy('requisition_number')" style="cursor: pointer;" class="small">
                                Requisition # 
                                @if($sortField === 'requisition_number')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('requisition_date')" style="cursor: pointer;" class="small">
                                Date 
                                @if($sortField === 'requisition_date')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th class="small">Employee</th>
                            <th class="small">Project</th>
                            <th class="small text-center">Items</th>
                            <th wire:click="sortBy('total_amount')" style="cursor: pointer;" class="small text-end">
                                Total Amount 
                                @if($sortField === 'total_amount')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('status')" style="cursor: pointer;" class="small">
                                Status 
                                @if($sortField === 'status')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th class="small">Current Level</th>
                            <th class="small text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requisitions as $requisition)
                        <tr>
                            <td class="small">
                                <strong>{{ $requisition->requisition_number }}</strong>
                            </td>
                            <td class="small">
                                <div>{{ $requisition->requisition_date->format('d M Y') }}</div>
                                @if($requisition->required_date)
                                <small class="text-muted">Required: {{ $requisition->required_date->format('d M Y') }}</small>
                                @endif
                            </td>
                            <td class="small">
                                <div>{{ $requisition->employee->name ?? 'N/A' }}</div>
                                @if($requisition->employee && $requisition->employee->position)
                                <small class="text-muted">{{ $requisition->employee->position }}</small>
                                @endif
                            </td>
                            <td class="small">
                                {{ $requisition->project->project_name ?? 'N/A' }}
                            </td>
                            <td class="small text-center">
                                <span class="badge bg-info">{{ $requisition->items->count() }}</span>
                            </td>
                            <td class="small text-end">
                                <strong>{{ number_format($requisition->total_amount, 0) }}</strong>
                            </td>
                            <td class="small">
                                @if($requisition->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($requisition->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($requisition->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($requisition->status === 'completed')
                                    <span class="badge bg-info">Completed</span>
                                @endif
                            </td>
                            <td class="small">
                                @if($requisition->currentApprovalLevel)
                                    <span class="badge bg-primary">{{ $requisition->currentApprovalLevel->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="small text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" 
                                            class="btn btn-primary btn-sm" 
                                            wire:click="viewRequisition({{ $requisition->id }})"
                                            title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    @if($requisition->status === 'pending')
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                wire:click="approveRequisition({{ $requisition->id }})"
                                                wire:confirm="Are you sure you want to approve this requisition?"
                                                wire:loading.attr="disabled"
                                                title="Approve">
                                            <span wire:loading.remove wire:target="approveRequisition({{ $requisition->id }})">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span wire:loading wire:target="approveRequisition({{ $requisition->id }})">
                                                <i class="fas fa-spinner fa-spin"></i>
                                            </span>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                wire:click="rejectRequisition({{ $requisition->id }})"
                                                wire:confirm="Are you sure you want to reject this requisition?"
                                                wire:loading.attr="disabled"
                                                title="Reject">
                                            <span wire:loading.remove wire:target="rejectRequisition({{ $requisition->id }})">
                                                <i class="fas fa-times"></i>
                                            </span>
                                            <span wire:loading wire:target="rejectRequisition({{ $requisition->id }})">
                                                <i class="fas fa-spinner fa-spin"></i>
                                            </span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-0">No requisitions found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <div>
                    <small class="text-muted">
                        Showing {{ $requisitions->firstItem() ?? 0 }} to {{ $requisitions->lastItem() ?? 0 }} of {{ $requisitions->total() }} requisitions
                    </small>
                </div>
                <div>
                    {{ $requisitions->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Requisition Detail Modal -->
    <div class="modal fade" id="requisitionDetailModal" tabindex="-1" aria-labelledby="requisitionDetailModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="requisitionDetailModalLabel">
                        <i class="fas fa-eye me-2"></i> Requisition Details
                        @if($selectedRequisition)
                            - {{ $selectedRequisition->requisition_number }}
                        @endif
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    @if($selectedRequisition)
                        <!-- Requisition Information -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <table class="table table-bordered mb-0 requisition-info-table">
                                    <tbody>
                                        <tr>
                                            <th class="bg-light" style="width: 40%;">Requisition Number:</th>
                                            <td><strong class="text-dark">{{ $selectedRequisition->requisition_number }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Requisition Date:</th>
                                            <td class="text-dark">{{ $selectedRequisition->requisition_date->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Required Date:</th>
                                            <td class="text-dark">{{ $selectedRequisition->required_date ? $selectedRequisition->required_date->format('d M Y') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Status:</th>
                                            <td>
                                                @if($selectedRequisition->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($selectedRequisition->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($selectedRequisition->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @elseif($selectedRequisition->status === 'completed')
                                                    <span class="badge bg-info">Completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6 mb-3">
                                <table class="table table-bordered mb-0 requisition-info-table">
                                    <tbody>
                                        <tr>
                                            <th class="bg-light" style="width: 40%;">Employee:</th>
                                            <td class="text-dark">
                                                <div class="fw-semibold">{{ $selectedRequisition->employee->name ?? 'N/A' }}</div>
                                                @if($selectedRequisition->employee && $selectedRequisition->employee->position)
                                                <small class="text-muted d-block mt-1">{{ $selectedRequisition->employee->position }}</small>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Project:</th>
                                            <td class="text-dark">{{ $selectedRequisition->project->project_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Total Items:</th>
                                            <td class="text-dark"><strong>{{ $selectedRequisition->items->count() }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Total Amount:</th>
                                            <td class="text-dark"><strong>{{ number_format($selectedRequisition->total_amount, 0) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="mb-4">
                            <h6 class="mb-3 text-primary fw-bold">
                                <i class="fas fa-list me-2"></i> Requisition Items
                                @if($selectedRequisition->currentApprovalLevel)
                                    <span class="badge bg-info ms-2">Current Level: {{ $selectedRequisition->currentApprovalLevel->name }}</span>
                                @endif
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 requisition-items-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%;" class="text-center">S.No</th>
                                            <th style="width: 15%;">Account Head</th>
                                            <th style="width: 25%;">Description</th>
                                            <th style="width: 8%;" class="text-end">Qty</th>
                                            <th style="width: 8%;">Unit</th>
                                            <th style="width: 12%;">Current Level</th>
                                            <th style="width: 12%;">Status</th>
                                            <th style="width: 15%;" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedRequisition->items as $index => $item)
                                        <tr>
                                            <td class="text-center text-dark">{{ $loop->iteration }}</td>
                                            <td class="text-dark">{{ $item->headOfAccount->account_name ?? 'N/A' }}</td>
                                            <td class="text-dark" style="word-wrap: break-word; word-break: break-word;">
                                                @if($editingItemId == $item->id)
                                                    <textarea class="form-control form-control-sm" wire:model="itemForm.description" rows="2"></textarea>
                                                @else
                                                    {{ $item->description ?? '-' }}
                                                @endif
                                            </td>
                                            <td class="text-end text-dark">
                                                @if($editingItemId == $item->id)
                                                    <input type="number" class="form-control form-control-sm text-end" wire:model="itemForm.qty" min="1">
                                                @else
                                                    {{ number_format($item->qty, 0) }}
                                                @endif
                                            </td>
                                            <td class="text-dark">
                                                @if($editingItemId == $item->id)
                                                    <input type="text" class="form-control form-control-sm" wire:model="itemForm.unit">
                                                @else
                                                    {{ $item->unit ?? '-' }}
                                                @endif
                                            </td>
                                            <td class="text-dark">
                                                @if($item->currentApprovalLevel)
                                                    <span class="badge bg-primary">{{ $item->currentApprovalLevel->name }}</span>
                                                @else
                                                    <span class="badge bg-success">Fully Approved</span>
                                                @endif
                                            </td>
                                            <td class="text-dark">
                                                @if($item->confirmation_status === 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($item->confirmation_status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($editingItemId == $item->id)
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-success" wire:click="updateItem({{ $item->id }})">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-secondary" wire:click="cancelEditItem">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                @elseif($selectedRequisition->status === 'pending' && $userApprovalLevel && $selectedRequisition->current_approval_level_id == $userApprovalLevel->id && $item->current_approval_level_id == $userApprovalLevel->id && $item->confirmation_status != 'rejected')
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-primary btn-sm" wire:click="editItem({{ $item->id }})" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-success btn-sm" wire:click="approveItem({{ $item->id }})" wire:confirm="Are you sure you want to approve this item?" title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm" wire:click="rejectItem({{ $item->id }})" wire:confirm="Are you sure you want to reject this item?" title="Reject">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Remarks -->
                        @if($selectedRequisition->remark)
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-2 text-primary fw-bold">
                                <i class="fas fa-comment me-2"></i> Remarks
                            </h6>
                            <p class="mb-0 text-dark bg-light p-3 rounded" style="word-wrap: break-word; word-break: break-word;">{{ $selectedRequisition->remark }}</p>
                        </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Open modal when requisition is selected
        document.addEventListener('livewire:init', () => {
            Livewire.on('openRequisitionModal', () => {
                const modalElement = document.getElementById('requisitionDetailModal');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        });

        // Reset selected requisition when modal is closed
        document.getElementById('requisitionDetailModal')?.addEventListener('hidden.bs.modal', function () {
            @this.closeModal();
        });
    </script>
    
    <style>
        /* Main table styles */
        .table th.small,
        .table td.small {
            font-size: 0.875rem;
            padding: 0.5rem;
            vertical-align: middle;
        }
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa !important;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        .btn-group-sm .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .expanded-row {
            background-color: #f8f9fa;
        }
        .expanded-row td {
            border-top: 2px solid #dee2e6;
        }
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Modal specific styles */
        #requisitionDetailModal .modal-body {
            padding: 1.5rem;
        }
        
        #requisitionDetailModal .requisition-info-table th,
        #requisitionDetailModal .requisition-info-table td {
            padding: 0.75rem;
            font-size: 0.95rem;
            vertical-align: middle;
        }
        
        #requisitionDetailModal .requisition-info-table th {
            font-weight: 600;
            color: #495057;
        }
        
        #requisitionDetailModal .requisition-items-table th,
        #requisitionDetailModal .requisition-items-table td {
            padding: 0.75rem;
            font-size: 0.95rem;
            vertical-align: middle;
        }
        
        #requisitionDetailModal .requisition-items-table th {
            font-weight: 600;
            color: #495057;
            white-space: nowrap;
        }
        
        #requisitionDetailModal .requisition-items-table td {
            white-space: normal;
        }
        
        #requisitionDetailModal .table-responsive {
            max-height: none;
            overflow-x: auto;
        }
        
        #requisitionDetailModal .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #dee2e6;
        }
    </style>
</div>
