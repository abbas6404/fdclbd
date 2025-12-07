<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white py-1">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-list me-2"></i> Purchase Orders List
                </h6>
            </div>
        </div>
        <div class="card-body py-3">
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
                               placeholder="Fast search by PO number, supplier, employee, or project...">
                    </div>
                </div>
            </div>

            <!-- Purchase Orders Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th wire:click="sortBy('purchase_order_number')" style="cursor: pointer;" class="small">
                                PO Number 
                                @if($sortField === 'purchase_order_number')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('purchase_order_date')" style="cursor: pointer;" class="small">
                                PO Date 
                                @if($sortField === 'purchase_order_date')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th class="small">Supplier</th>
                            <th class="small">Employee</th>
                            <th class="small">Project</th>
                            <th class="small">Requisition</th>
                            <th class="small text-center">Items</th>
                            <th wire:click="sortBy('total_amount')" style="cursor: pointer;" class="small text-end">
                                Total Amount 
                                @if($sortField === 'total_amount')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th class="small text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchaseOrders as $po)
                        <tr>
                            <td class="small">
                                <strong>{{ $po->purchase_order_number }}</strong>
                            </td>
                            <td class="small">
                                <div>{{ $po->purchase_order_date->format('d M Y') }}</div>
                                @if($po->required_date)
                                <small class="text-muted">Required: {{ $po->required_date->format('d M Y') }}</small>
                                @endif
                            </td>
                            <td class="small">
                                <div>{{ $po->supplier->name ?? 'N/A' }}</div>
                                @if($po->supplier && $po->supplier->phone)
                                <small class="text-muted">{{ $po->supplier->phone }}</small>
                                @endif
                            </td>
                            <td class="small">
                                {{ $po->employee->name ?? 'N/A' }}
                            </td>
                            <td class="small">
                                {{ $po->project->project_name ?? 'N/A' }}
                            </td>
                            <td class="small">
                                @if($po->requisition)
                                    <span class="badge bg-info">{{ $po->requisition->requisition_number }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="small text-center">
                                <span class="badge bg-info">{{ $po->items->count() }}</span>
                            </td>
                            <td class="small text-end">
                                <strong>৳{{ number_format($po->total_amount, 2) }}</strong>
                            </td>
                            <td class="small text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" 
                                            class="btn btn-primary btn-sm" 
                                            wire:click="viewPurchaseOrder({{ $po->id }})"
                                            title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button type="button" 
                                            class="btn btn-warning btn-sm" 
                                            wire:click="editPurchaseOrder({{ $po->id }})"
                                            title="Edit Purchase Order">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <p class="text-muted mb-0">No purchase orders found.</p>
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
                        Showing {{ $purchaseOrders->firstItem() ?? 0 }} to {{ $purchaseOrders->lastItem() ?? 0 }} of {{ $purchaseOrders->total() }} purchase orders
                    </small>
                </div>
                <div>
                    {{ $purchaseOrders->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase Order Detail Modal -->
    <div class="modal fade" id="poDetailModal" tabindex="-1" aria-labelledby="poDetailModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="poDetailModalLabel">
                        <i class="fas fa-eye me-2"></i> Purchase Order Details
                        @if($selectedPurchaseOrder)
                            - {{ $selectedPurchaseOrder->purchase_order_number }}
                        @endif
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    @if($selectedPurchaseOrder)
                        <!-- PO Information -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <table class="table table-bordered mb-0 po-info-table">
                                    <tbody>
                                        <tr>
                                            <th class="bg-light" style="width: 40%;">PO Number:</th>
                                            <td><strong class="text-dark">{{ $selectedPurchaseOrder->purchase_order_number }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">PO Date:</th>
                                            <td class="text-dark">{{ $selectedPurchaseOrder->purchase_order_date->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Required Date:</th>
                                            <td class="text-dark">{{ $selectedPurchaseOrder->required_date ? $selectedPurchaseOrder->required_date->format('d M Y') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Supplier:</th>
                                            <td class="text-dark">
                                                <div class="fw-semibold">{{ $selectedPurchaseOrder->supplier->name ?? 'N/A' }}</div>
                                                @if($selectedPurchaseOrder->supplier && $selectedPurchaseOrder->supplier->phone)
                                                <small class="text-muted d-block mt-1">{{ $selectedPurchaseOrder->supplier->phone }}</small>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6 mb-3">
                                <table class="table table-bordered mb-0 po-info-table">
                                    <tbody>
                                        <tr>
                                            <th class="bg-light" style="width: 40%;">Employee:</th>
                                            <td class="text-dark">{{ $selectedPurchaseOrder->employee->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Project:</th>
                                            <td class="text-dark">{{ $selectedPurchaseOrder->project->project_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Requisition:</th>
                                            <td class="text-dark">
                                                @if($selectedPurchaseOrder->requisition)
                                                    <span class="badge bg-info">{{ $selectedPurchaseOrder->requisition->requisition_number }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Total Items:</th>
                                            <td class="text-dark"><strong>{{ $selectedPurchaseOrder->items->count() }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Total Amount:</th>
                                            <td class="text-dark"><strong>৳{{ number_format($selectedPurchaseOrder->total_amount, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="mb-4">
                            <h6 class="mb-3 text-primary fw-bold">
                                <i class="fas fa-list me-2"></i> Purchase Order Items
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 po-items-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%;" class="text-center">S.No</th>
                                            <th style="width: 20%;">Account Head</th>
                                            <th style="width: 30%;">Description</th>
                                            <th style="width: 10%;" class="text-end">Qty</th>
                                            <th style="width: 10%;">Unit</th>
                                            <th style="width: 15%;" class="text-end">Amount</th>
                                            <th style="width: 10%;" class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedPurchaseOrder->items as $index => $item)
                                        <tr>
                                            <td class="text-center text-dark">{{ $loop->iteration }}</td>
                                            <td class="text-dark">{{ $item->headOfAccount->account_name ?? 'N/A' }}</td>
                                            <td class="text-dark" style="word-wrap: break-word; word-break: break-word;">{{ $item->description ?? '-' }}</td>
                                            <td class="text-end text-dark">{{ number_format($item->qty, 0) }}</td>
                                            <td class="text-dark">{{ $item->unit ?? '-' }}</td>
                                            <td class="text-end text-dark">৳{{ number_format($item->amount, 2) }}</td>
                                            <td class="text-center">
                                                @if($item->receiving_confirmation === 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($item->receiving_confirmation === 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($item->receiving_confirmation === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="5" class="text-end">Total Amount:</th>
                                            <th class="text-end"><strong>৳{{ number_format($selectedPurchaseOrder->total_amount, 2) }}</strong></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Remarks -->
                        @if($selectedPurchaseOrder->remark)
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-2 text-primary fw-bold">
                                <i class="fas fa-comment me-2"></i> Remarks
                            </h6>
                            <p class="mb-0 text-dark bg-light p-3 rounded" style="word-wrap: break-word; word-break: break-word;">{{ $selectedPurchaseOrder->remark }}</p>
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
        // Open modal when PO is selected
        document.addEventListener('livewire:init', () => {
            Livewire.on('openPOModal', () => {
                const modalElement = document.getElementById('poDetailModal');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        });

        // Reset selected PO when modal is closed
        document.getElementById('poDetailModal')?.addEventListener('hidden.bs.modal', function () {
            @this.closeModal();
        });
    </script>
    
    <style>
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
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        #poDetailModal .modal-body {
            padding: 1.5rem;
        }
        
        #poDetailModal .po-info-table th,
        #poDetailModal .po-info-table td {
            padding: 0.75rem;
            font-size: 0.95rem;
            vertical-align: middle;
        }
        
        #poDetailModal .po-info-table th {
            font-weight: 600;
            color: #495057;
        }
        
        #poDetailModal .po-items-table th,
        #poDetailModal .po-items-table td {
            padding: 0.75rem;
            font-size: 0.95rem;
            vertical-align: middle;
        }
        
        #poDetailModal .po-items-table th {
            font-weight: 600;
            color: #495057;
            white-space: nowrap;
        }
        
        #poDetailModal .po-items-table td {
            white-space: normal;
        }
        
        #poDetailModal .table-responsive {
            max-height: none;
            overflow-x: auto;
        }
    </style>
</div>

