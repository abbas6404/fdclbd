<div class="container-fluid">
    <!-- Main Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-user-tie me-2"></i> All Sales Agents
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group" role="group">
                        <input type="radio" 
                               class="btn-check" 
                               name="showArchived" 
                               id="showActive" 
                               value=""
                               wire:model.live="showArchived"
                               autocomplete="off">
                        <label class="btn btn-sm {{ !$showArchived ? 'btn-primary' : 'btn-outline-primary' }}" for="showActive">
                            <i class="fas fa-check-circle me-1"></i> Active
                        </label>

                        <input type="radio" 
                               class="btn-check" 
                               name="showArchived" 
                               id="showArchived" 
                               value="1"
                               wire:model.live="showArchived"
                               autocomplete="off">
                        <label class="btn btn-sm {{ $showArchived ? 'btn-warning' : 'btn-outline-warning' }}" for="showArchived">
                            <i class="fas fa-archive me-1"></i> Archived
                        </label>
                    </div>
                    @if(Route::has('admin.sales-agents.create'))
                    <a href="{{ route('admin.sales-agents.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Sales Agent
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Filters -->
            <div class="row mb-3 g-2">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control form-control-sm" 
                               placeholder="Search sales agents by name, phone, NID, or address..." 
                               wire:model.live.debounce.300ms="search">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" wire:model.live="perPage">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" wire:click="$refresh">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th wire:click="sortBy('id')" style="cursor: pointer;" class="user-select-none">
                                ID
                                @if($sortField === 'id')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('name')" style="cursor: pointer;" class="user-select-none">
                                Name
                                @if($sortField === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th>Phone</th>
                            <th>NID/Passport</th>
                            <th>Address</th>
                            <th wire:click="sortBy('created_at')" style="cursor: pointer;" class="user-select-none">
                                Created
                                @if($sortField === 'created_at')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesAgents as $agent)
                        <tr class="{{ $agent->trashed() ? 'table-secondary' : '' }}">
                            <td>{{ $agent->id }}</td>
                            <td>
                                <div class="fw-bold">{{ $agent->name }}</div>
                                @if($agent->trashed())
                                <span class="badge bg-secondary small">Archived</span>
                                @endif
                            </td>
                            <td>{{ $agent->phone ?? 'N/A' }}</td>
                            <td>{{ $agent->nid_or_passport_number ?? 'N/A' }}</td>
                            <td>{{ Str::limit($agent->address ?? 'N/A', 30) }}</td>
                            <td>{{ $agent->created_at ? \Carbon\Carbon::parse($agent->created_at)->format('d M Y') : 'N/A' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @if(!$agent->trashed())
                                        @if(Route::has('admin.sales-agents.edit'))
                                        <a href="{{ route('admin.sales-agents.edit', $agent->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-outline-warning" 
                                                title="Archive"
                                                onclick="confirmArchive({{ $agent->id }})">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @else
                                        <button type="button" 
                                                class="btn btn-outline-success" 
                                                title="Restore"
                                                wire:click="restoreSalesAgent({{ $agent->id }})"
                                                wire:confirm="Are you sure you want to restore this sales agent?">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                title="Permanently Delete"
                                                onclick="confirmPermanentDelete({{ $agent->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-user-tie fa-3x mb-3 text-muted"></i>
                                    <p class="mb-2">No sales agents found.</p>
                                    @if(Route::has('admin.sales-agents.create'))
                                    <a href="{{ route('admin.sales-agents.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i> Create Your First Sales Agent
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <div class="text-muted small">
                    Showing <strong>{{ $salesAgents->firstItem() ?? 0 }}</strong> to <strong>{{ $salesAgents->lastItem() ?? 0 }}</strong> of <strong>{{ $salesAgents->total() }}</strong> results
                </div>
                <div>
                    {{ $salesAgents->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Archive Confirmation Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Archive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to archive this sales agent? You can restore it later if needed.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="confirmArchiveBtn">Archive</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Permanent Delete Confirmation Modal -->
    <div class="modal fade" id="permanentDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Permanent Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning!</strong> This action cannot be undone. This will permanently delete the sales agent and all associated data.
                    </div>
                    Are you absolutely sure you want to permanently delete this sales agent?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmPermanentDeleteBtn">Permanently Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let agentIdToArchive = null;
    let agentIdToPermanentDelete = null;

    function confirmArchive(agentId) {
        agentIdToArchive = agentId;
        const modal = new bootstrap.Modal(document.getElementById('archiveModal'));
        modal.show();
    }

    function confirmPermanentDelete(agentId) {
        agentIdToPermanentDelete = agentId;
        const modal = new bootstrap.Modal(document.getElementById('permanentDeleteModal'));
        modal.show();
    }

    document.getElementById('confirmArchiveBtn').addEventListener('click', function() {
        if (agentIdToArchive) {
            @this.archiveSalesAgent(agentIdToArchive);
            const modal = bootstrap.Modal.getInstance(document.getElementById('archiveModal'));
            modal.hide();
            agentIdToArchive = null;
        }
    });

    document.getElementById('confirmPermanentDeleteBtn').addEventListener('click', function() {
        if (agentIdToPermanentDelete) {
            @this.permanentlyDeleteSalesAgent(agentIdToPermanentDelete);
            const modal = bootstrap.Modal.getInstance(document.getElementById('permanentDeleteModal'));
            modal.hide();
            agentIdToPermanentDelete = null;
        }
    });
</script>
