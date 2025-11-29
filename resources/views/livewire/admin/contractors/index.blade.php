<div class="container-fluid">
    <!-- Main Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-hard-hat me-2"></i> All Contractors
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
                    @if(Route::has('admin.contractors.create'))
                    <a href="{{ route('admin.contractors.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Contractor
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
                               placeholder="Search contractors by name, phone, email, or address..." 
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
                            <th>Email</th>
                            <th>Phone</th>
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
                        @forelse($contractors as $contractor)
                        <tr class="{{ $contractor->trashed() ? 'table-secondary' : '' }}">
                            <td>{{ $contractor->id }}</td>
                            <td>
                                <div class="fw-bold">{{ $contractor->name }}</div>
                                @if($contractor->trashed())
                                <span class="badge bg-secondary small">Archived</span>
                                @endif
                            </td>
                            <td>{{ $contractor->email ?? 'N/A' }}</td>
                            <td>{{ $contractor->phone ?? 'N/A' }}</td>
                            <td>{{ Str::limit($contractor->address ?? 'N/A', 30) }}</td>
                            <td>{{ $contractor->created_at ? \Carbon\Carbon::parse($contractor->created_at)->format('d M Y') : 'N/A' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @if(!$contractor->trashed())
                                        @if(Route::has('admin.contractors.edit'))
                                        <a href="{{ route('admin.contractors.edit', $contractor->id) }}" 
                                           class="btn btn-outline-primary" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-outline-warning" 
                                                title="Archive"
                                                onclick="confirmArchive({{ $contractor->id }})">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @else
                                        <button type="button" 
                                                class="btn btn-outline-success" 
                                                title="Restore"
                                                wire:click="restoreContractor({{ $contractor->id }})"
                                                wire:confirm="Are you sure you want to restore this contractor?">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                title="Permanently Delete"
                                                onclick="confirmPermanentDelete({{ $contractor->id }})">
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
                                    <i class="fas fa-hard-hat fa-3x mb-3 text-muted"></i>
                                    <p class="mb-2">No contractors found.</p>
                                    @if(Route::has('admin.contractors.create'))
                                    <a href="{{ route('admin.contractors.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i> Create Your First Contractor
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
            @if($contractors->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Showing {{ $contractors->firstItem() }} to {{ $contractors->lastItem() }} of {{ $contractors->total() }} contractors
                </div>
                <div>
                    {{ $contractors->links() }}
                </div>
            </div>
            @endif
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
                    Are you sure you want to archive this contractor? You can restore it later if needed.
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
                        <strong>Warning!</strong> This action cannot be undone. This will permanently delete the contractor and all associated data.
                    </div>
                    Are you absolutely sure you want to permanently delete this contractor?
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
    let contractorIdToArchive = null;
    let contractorIdToPermanentDelete = null;

    function confirmArchive(contractorId) {
        contractorIdToArchive = contractorId;
        const modal = new bootstrap.Modal(document.getElementById('archiveModal'));
        modal.show();
    }

    function confirmPermanentDelete(contractorId) {
        contractorIdToPermanentDelete = contractorId;
        const modal = new bootstrap.Modal(document.getElementById('permanentDeleteModal'));
        modal.show();
    }

    document.getElementById('confirmArchiveBtn').addEventListener('click', function() {
        if (contractorIdToArchive) {
            @this.archiveContractor(contractorIdToArchive);
            const modal = bootstrap.Modal.getInstance(document.getElementById('archiveModal'));
            modal.hide();
            contractorIdToArchive = null;
        }
    });

    document.getElementById('confirmPermanentDeleteBtn').addEventListener('click', function() {
        if (contractorIdToPermanentDelete) {
            @this.permanentlyDeleteContractor(contractorIdToPermanentDelete);
            const modal = bootstrap.Modal.getInstance(document.getElementById('permanentDeleteModal'));
            modal.hide();
            contractorIdToPermanentDelete = null;
        }
    });
</script>
