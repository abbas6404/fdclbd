<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-primary">{{ number_format($stats['total']) }}</h3>
                            <p class="mb-0 text-muted small">Total Flats</p>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-home fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-success">{{ number_format($stats['available']) }}</h3>
                            <p class="mb-0 text-muted small">Available</p>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-info">{{ number_format($stats['sold']) }}</h3>
                            <p class="mb-0 text-muted small">Sold</p>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-secondary">{{ number_format($stats['land_owner']) }}</h3>
                            <p class="mb-0 text-muted small">Land Owner</p>
                        </div>
                        <div class="text-secondary">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-warning">{{ $stats['projects_count'] }}</h3>
                            <p class="mb-0 text-muted small">Projects</p>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-home me-2"></i> All Flats
                </h6>
                <div class="d-flex gap-2">
                    <button type="button" 
                            class="btn btn-sm {{ $showArchived ? 'btn-success' : 'btn-outline-warning' }}"
                            wire:click="toggleArchive"
                            title="{{ $showArchived ? 'Show Active Flats' : 'Show Archived Flats' }}">
                        <i class="fas fa-archive me-1"></i> {{ $showArchived ? 'Active' : 'Archive' }}
                    </button>
                    @if(!$showArchived)
                    <a href="{{ route('admin.flat.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Flat
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Filters -->
            <div class="row mb-3 g-2">
                <div class="col-md-2">
                    <select class="form-select form-select-sm" wire:model.live="statusFilter">
                        <option value="">All Status</option>
                        <option value="available">Available</option>
                        <option value="sold">Sold</option>
                        <option value="reserved">Reserved</option>
                        <option value="land_owner">Land Owner</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light py-0">Size</span>
                        <input type="number" 
                               class="form-control form-control-sm" 
                               placeholder="From" 
                               wire:model.live.debounce.300ms="sizeFrom"
                               min="0"
                               step="0.01">
                        <input type="number" 
                               class="form-control form-control-sm" 
                               placeholder="To" 
                               wire:model.live.debounce.300ms="sizeTo"
                               min="0"
                               step="0.01">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control form-control-sm" 
                               placeholder="Search flats by num, type, floor, and project name, address" 
                               wire:model.live.debounce.300ms="search">
                    </div>
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
                            <th wire:click="sortBy('flat_number')" style="cursor: pointer;" class="user-select-none">
                                Flat Number
                                @if($sortField === 'flat_number')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th>Project</th>
                            <th wire:click="sortBy('flat_type')" style="cursor: pointer;" class="user-select-none">
                                Type
                                @if($sortField === 'flat_type')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('floor_number')" style="cursor: pointer;" class="user-select-none">
                                Floor
                                @if($sortField === 'floor_number')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('flat_size')" style="cursor: pointer;" class="user-select-none">
                                Size (sq ft)
                                @if($sortField === 'flat_size')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('status')" style="cursor: pointer;" class="user-select-none">
                                Status
                                @if($sortField === 'status')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($flats as $flat)
                        <tr>
                            <td>{{ $flat->id }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $flat->flat_number }}</span>
                            </td>
                            <td>
                                @if($flat->project)
                                    <div class="fw-bold">{{ $flat->project->project_name }}</div>
                                    <small class="text-muted">{{ Str::limit($flat->project->address, 30) }}</small>
                                @else
                                    <div class="fw-bold text-muted">N/A</div>
                                    <small class="text-muted">No project assigned</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $flat->flat_type }}</span>
                            </td>
                            <td>{{ $flat->floor_number }}</td>
                            <td>{{ $flat->flat_size ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $flat->status == 'available' ? 'success' : ($flat->status == 'sold' ? 'danger' : ($flat->status == 'booked' ? 'warning' : 'info')) }}">
                                    {{ ucfirst($flat->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                            type="button" 
                                            id="actionDropdown{{ $flat->id }}" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown{{ $flat->id }}">
                                        @if($showArchived)
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="javascript:void(0)" 
                                                   wire:click="restoreFlat({{ $flat->id }})">
                                                    <i class="fas fa-undo me-2 text-success"></i> Restore
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" 
                                                   href="javascript:void(0)" 
                                                   onclick="confirmPermanentDelete({{ $flat->id }})">
                                                    <i class="fas fa-trash-alt me-2"></i> Permanently Delete
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="{{ route('admin.flat.show', $flat->id) }}">
                                                    <i class="fas fa-eye me-2 text-info"></i> View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="{{ route('admin.flat.edit', $flat->id) }}">
                                                    <i class="fas fa-edit me-2 text-primary"></i> Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            @if($flat->status == 'available')
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="{{ route('admin.flat-sales.index') }}?flat_id={{ $flat->id }}">
                                                    <i class="fas fa-shopping-cart me-2 text-warning"></i> Flat Sales
                                                </a>
                                            </li>
                                            @endif
                                            @if($flat->status == 'available' || $flat->status == 'sold')
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="javascript:void(0)" 
                                                   wire:click="openSetScheduleModal({{ $flat->id }})">
                                                    <i class="fas fa-calendar-alt me-2 text-info"></i> Set Schedule
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="javascript:void(0)" 
                                                   wire:click="openPaymentReceiveModal({{ $flat->id }})">
                                                    <i class="fas fa-money-bill-wave me-2 text-success"></i> Payment Receive
                                                </a>
                                            </li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="javascript:void(0)" 
                                                   wire:click="openDocumentModal({{ $flat->id }})">
                                                    <i class="fas fa-paperclip me-2 text-success"></i> Add Document
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" 
                                                   href="javascript:void(0)" 
                                                   onclick="confirmDelete({{ $flat->id }})">
                                                    <i class="fas fa-trash me-2"></i> Delete
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-home fa-3x mb-3 text-muted"></i>
                                    <p class="mb-2">No flats found.</p>
                                    <a href="{{ route('admin.flat.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i> Create Your First Flat
                                    </a>
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
                    Showing <strong>{{ $flats->firstItem() ?? 0 }}</strong> to <strong>{{ $flats->lastItem() ?? 0 }}</strong> of <strong>{{ $flats->total() }}</strong> results
                </div>
                <div>
                    {{ $flats->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this flat? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Permanent Delete Confirmation Modal -->
    <div class="modal fade" id="permanentDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Permanent Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0"><strong>Warning!</strong> This action will permanently delete this flat and cannot be undone. Are you absolutely sure?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmPermanentDeleteBtn">Permanently Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let flatIdToDelete = null;
        let flatIdToPermanentDelete = null;

        function confirmDelete(flatId) {
            flatIdToDelete = flatId;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function confirmPermanentDelete(flatId) {
            flatIdToPermanentDelete = flatId;
            const modal = new bootstrap.Modal(document.getElementById('permanentDeleteModal'));
            modal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (flatIdToDelete) {
                @this.deleteFlat(flatIdToDelete);
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                modal.hide();
                flatIdToDelete = null;
            }
        });

        document.getElementById('confirmPermanentDeleteBtn').addEventListener('click', function() {
            if (flatIdToPermanentDelete) {
                @this.permanentDeleteFlat(flatIdToPermanentDelete);
                const modal = bootstrap.Modal.getInstance(document.getElementById('permanentDeleteModal'));
                modal.hide();
                flatIdToPermanentDelete = null;
            }
        });
    </script>

    <!-- Document Modal -->
    @if($show_document_modal)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
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
                                <tr class="bg-light">
                                    <td class="text-center align-middle">
                                        <span class="text-muted">{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="align-middle">
                                        {{ $attachment['document_name'] }} <span class="badge bg-info ms-1">(Existing)</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ asset('storage/' . $attachment['file_path']) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary me-2">
                                            <i class="fas fa-eye me-1"></i> View/Download
                                        </a>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-file me-1"></i> {{ number_format($attachment['file_size'] / 1024, 2) }} KB
                                        </small>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" 
                                                class="btn btn-xs btn-outline-danger" 
                                                wire:click="removeExistingAttachment({{ $attachment['id'] }})"
                                                title="Remove">
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
                                            <small class="text-muted d-block mt-1">
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
