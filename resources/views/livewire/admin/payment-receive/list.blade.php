<!-- List View -->
<div class="card shadow">
    <div class="card-header bg-primary text-white py-2">
        <h6 class="card-title mb-0">
            <i class="fas fa-list me-2"></i> Pending Payments
        </h6>
    </div>
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
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer_results as $item)
                    @php
                        $dueDate = $item['earliest_due_date'] ? \Carbon\Carbon::parse($item['earliest_due_date']) : null;
                        $isOverdue = $dueDate && $dueDate->isPast();
                    @endphp
                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}" style="cursor: pointer;">
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
                        <td class="text-center">
                            <button type="button" 
                                    class="btn btn-sm btn-primary" 
                                    wire:click="selectCustomerFromList({{ $item['customer_id'] }})">
                                <i class="fas fa-eye me-1"></i> View
                            </button>
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
