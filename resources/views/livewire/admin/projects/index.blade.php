<div class="container-fluid">
    <!-- Main Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-building me-2"></i> All Projects
                </h6>
                <div class="d-flex gap-2">
                        <button type="button" 
                            class="btn btn-sm {{ $showArchived ? 'btn-success' : 'btn-outline-warning' }}"
                            wire:click="toggleArchive"
                            title="{{ $showArchived ? 'Show Active Projects' : 'Show Archived Projects' }}">
                        <i class="fas fa-archive me-1"></i> {{ $showArchived ? 'Active' : 'Archive' }}
                        </button>
                    <a href="{{ route('admin.projects.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Project
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Filters -->
            <div class="row mb-3 g-2">
                <div class="col-md-3">
                    <select class="form-select form-select-sm" wire:model.live="statusFilter">
                        <option value="">All Status</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                        <option value="on_hold">On Hold</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" wire:model.live="facingFilter">
                        <option value="">All Facing</option>
                        <option value="North">North</option>
                        <option value="South">South</option>
                        <option value="East">East</option>
                        <option value="West">West</option>
                        <option value="North-East">North-East</option>
                        <option value="North-West">North-West</option>
                        <option value="South-East">South-East</option>
                        <option value="South-West">South-West</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control form-control-sm" 
                               placeholder="Search by name, description, address, land area, land owner name/NID/phone..." 
                               wire:model.live.debounce.300ms="search">
                    </div>
                </div>
            </div>

            <!-- Advanced Filters -->
            @if($showAdvancedFilters)
            <div class="card border mb-3 animate-fade-in">
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">From Date</label>
                            <input type="date" class="form-control form-control-sm" wire:model.live="dateFrom">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">To Date</label>
                            <input type="date" class="form-control form-control-sm" wire:model.live="dateTo">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary w-100" wire:click="$set('dateFrom', ''); $set('dateTo', '')">
                                <i class="fas fa-times me-1"></i> Clear Dates
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Table View -->
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
                            <th wire:click="sortBy('project_name')" style="cursor: pointer;" class="user-select-none">
                                Project Name
                                @if($sortField === 'project_name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th>Address/Fac/Sto/Flo</th>
                            <th wire:click="sortBy('land_area')" style="cursor: pointer;" class="user-select-none">
                                Land Area
                                @if($sortField === 'land_area')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('available_flats_count')" style="cursor: pointer;" class="user-select-none">
                                Avl
                                @if($sortField === 'available_flats_count')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('sold_flats_count')" style="cursor: pointer;" class="user-select-none">
                                Sol
                                @if($sortField === 'sold_flats_count')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('reserved_flats_count')" style="cursor: pointer;" class="user-select-none">
                                Res
                                @if($sortField === 'reserved_flats_count')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('land_owner_flats_count')" style="cursor: pointer;" class="user-select-none">
                                L. Own
                                @if($sortField === 'land_owner_flats_count')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('project_launching_date')" style="cursor: pointer;" class="user-select-none">
                                LaU/HaN
                                @if($sortField === 'project_launching_date')
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
                        @forelse($projects as $project)
                        <tr class="project-row" style="transition: all 0.2s;">
                            <td>{{ $project->id }}</td>
                            <td>
                                <div class="fw-bold" style="cursor: pointer; color: #0066cc;" wire:click="showProjectFlats({{ $project->id }})" title="Click to view flats">
                                    {{ $project->project_name }}
                                </div>
                                @if($project->description)
                                <small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                @endif
                            </td>
                            <td style="cursor: pointer; color: #0066cc;" wire:click="showProjectFlats({{ $project->id }})" title="Click to view flats">
                                <div>{{ Str::limit($project->address, 30) }}</div>
                                <div class="mt-1">
                                    <small class="text-muted">
                                        @if($project->facing)
                                            Fac: {{ $project->facing }}
                                        @else
                                            Fac: N/A
                                        @endif
                                        @if($project->storey)
                                            | Sto: {{ $project->storey }}
                                        @else
                                            | Sto: N/A
                                        @endif
                                        @if($project->total_floors)
                                            | Flo: {{ $project->total_floors }}
                                        @else
                                            | Flo: N/A
                                        @endif
                                    </small>
                                </div>
                            </td>
                            <td>
                                {{ $project->land_area ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="badge bg-success" style="cursor: pointer;" wire:click="showProjectFlats({{ $project->id }}, 'available')" title="Click to view available flats">{{ $project->available_flats_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge bg-danger" style="cursor: pointer;" wire:click="showProjectFlats({{ $project->id }}, 'sold')" title="Click to view sold flats">{{ $project->sold_flats_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge bg-warning" style="cursor: pointer;" wire:click="showProjectFlats({{ $project->id }}, 'reserved')" title="Click to view reserved flats">{{ $project->reserved_flats_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary" style="cursor: pointer;" wire:click="showProjectFlats({{ $project->id }}, 'land_owner')" title="Click to view land owner flats">{{ $project->land_owner_flats_count ?? 0 }}</span>
                            </td>
                            <td>
                                <div>{{ $project->project_launching_date ? \Carbon\Carbon::parse($project->project_launching_date)->format('M d, y') : 'N/A' }}</div>
                                <div class="mt-1">
                                    <small class="text-muted">
                                        {{ $project->project_hand_over_date ? \Carbon\Carbon::parse($project->project_hand_over_date)->format('M d, y') : 'N/A' }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $project->status == 'ongoing' ? 'success' : ($project->status == 'completed' ? 'info' : ($project->status == 'on_hold' ? 'warning' : ($project->status == 'upcoming' ? 'primary' : 'secondary'))) }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                            type="button" 
                                            id="actionDropdown{{ $project->id }}" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actionDropdown{{ $project->id }}">
                                        <li>
                                            <a class="dropdown-item" 
                                               href="javascript:void(0)" 
                                               wire:click="showProjectFlats({{ $project->id }})">
                                                <i class="fas fa-eye me-2 text-info"></i> View Flats
                                            </a>
                                        </li>
                                        @if(!$showArchived)
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="{{ route('admin.flat.create') }}?project_id={{ $project->id }}">
                                                    <i class="fas fa-plus me-2 text-success"></i> Add Flat
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="{{ route('admin.projects.edit', $project->id) }}">
                                                    <i class="fas fa-edit me-2 text-primary"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="javascript:void(0)" 
                                                   wire:click="openDocumentModal({{ $project->id }})">
                                                    <i class="fas fa-paperclip me-2 text-info"></i> Add Document
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" 
                                                   href="javascript:void(0)" 
                                                   onclick="confirmDelete({{ $project->id }})">
                                                    <i class="fas fa-trash me-2"></i> Delete
                                                </a>
                                            </li>
                                        @else
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" 
                                                   href="javascript:void(0)" 
                                                   wire:click="restoreProject({{ $project->id }})">
                                                    <i class="fas fa-undo me-2 text-success"></i> Restore
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" 
                                                   href="javascript:void(0)" 
                                                   onclick="confirmPermanentDelete({{ $project->id }})">
                                                    <i class="fas fa-trash-alt me-2"></i> Permanently Delete
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-building fa-3x mb-3 text-muted"></i>
                                    <p class="mb-2">No projects found.</p>
                                    <a href="{{ route('admin.projects.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i> Create Your First Project
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
                    Showing <strong>{{ $projects->firstItem() ?? 0 }}</strong> to <strong>{{ $projects->lastItem() ?? 0 }}</strong> of <strong>{{ $projects->total() }}</strong> results
                </div>
                <div>
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Project Flats Modal -->
    @if($showFlatsModal && $selectedProject)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-building me-2"></i>{{ $selectedProject->project_name }} - 
                        @if($flatStatusFilter)
                            {{ ucfirst($flatStatusFilter) }} Flats
                        @else
                            All Flats
                        @endif
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('admin.projects.show', $selectedProject->id) }}" 
                           class="btn btn-sm btn-primary" 
                           target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i> View Full Details
                        </a>
                        <button type="button" class="btn-close" wire:click="closeFlatsModal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <!-- Filter Buttons -->
                    <div class="mb-3 d-flex gap-2 flex-wrap">
                        <button type="button" 
                                class="btn btn-sm {{ !$flatStatusFilter ? 'btn-primary' : 'btn-outline-primary' }}" 
                                wire:click="filterFlatsByStatus(null)">
                            <i class="fas fa-list me-1"></i> All Flats
                        </button>
                        <button type="button" 
                                class="btn btn-sm {{ $flatStatusFilter === 'available' ? 'btn-success' : 'btn-outline-success' }}" 
                                wire:click="filterFlatsByStatus('available')">
                            <i class="fas fa-check-circle me-1"></i> Available
                        </button>
                        <button type="button" 
                                class="btn btn-sm {{ $flatStatusFilter === 'sold' ? 'btn-danger' : 'btn-outline-danger' }}" 
                                wire:click="filterFlatsByStatus('sold')">
                            <i class="fas fa-tag me-1"></i> Sold
                        </button>
                        <button type="button" 
                                class="btn btn-sm {{ $flatStatusFilter === 'reserved' ? 'btn-warning' : 'btn-outline-warning' }}" 
                                wire:click="filterFlatsByStatus('reserved')">
                            <i class="fas fa-bookmark me-1"></i> Reserved
                        </button>
                        <button type="button" 
                                class="btn btn-sm {{ $flatStatusFilter === 'land_owner' ? 'btn-secondary' : 'btn-outline-secondary' }}" 
                                wire:click="filterFlatsByStatus('land_owner')">
                            <i class="fas fa-user-tie me-1"></i> Land Owner
                        </button>
                    </div>

                    @if($selectedProject->flats && $selectedProject->flats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Flat Number</th>
                                        <th>Type</th>
                                        <th>Floor</th>
                                        <th>Size (sq ft)</th>
                                        <th>Status</th>
                                        <th>Price/Sqft</th>
                                        <th>Total Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedProject->flats as $flat)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $flat->flat_number }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $flat->flat_type }}</span>
                                        </td>
                                        <td>{{ $flat->floor_number }}</td>
                                        <td>{{ $flat->flat_size ?? 'N/A' }}</td>
                                        <td>
                                            @if($flat->status == 'available')
                                                <span class="badge bg-success">Available</span>
                                            @elseif($flat->status == 'sold')
                                                <span class="badge bg-danger">Sold</span>
                                            @elseif($flat->status == 'reserved')
                                                <span class="badge bg-warning">Reserved</span>
                                            @elseif($flat->status == 'land_owner')
                                                <span class="badge bg-secondary">Land Owner</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($flat->status ?? 'N/A') }}</span>
                                            @endif
                                        </td>
                                        <td>৳{{ number_format($flat->price_per_sqft ?? 0, 2) }}</td>
                                        <td>৳{{ number_format($flat->total_price ?? 0, 2) }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.flat.show', $flat->id) }}" 
                                                   class="btn btn-outline-info" 
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.flat.edit', $flat->id) }}" 
                                                   class="btn btn-outline-primary" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if(Route::has('admin.flat-sales.index'))
                                                    <a href="{{ route('admin.flat-sales.index') }}?flat_id={{ $flat->id }}" 
                                                       class="btn btn-outline-success" 
                                                       title="Sales">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 text-muted small">
                            @if($flatStatusFilter)
                                <strong>Showing {{ ucfirst($flatStatusFilter) }} Flats:</strong> {{ $selectedProject->flats->count() }}
                            @else
                                <strong>Total Flats:</strong> {{ $selectedProject->flats->count() }} | 
                                <span class="text-success"><strong>Available:</strong> {{ $selectedProject->flats->where('status', 'available')->count() }}</span> | 
                                <span class="text-danger"><strong>Sold:</strong> {{ $selectedProject->flats->where('status', 'sold')->count() }}</span> | 
                                <span class="text-warning"><strong>Reserved:</strong> {{ $selectedProject->flats->where('status', 'reserved')->count() }}</span> | 
                                <span class="text-secondary"><strong>Land Owner:</strong> {{ $selectedProject->flats->where('status', 'land_owner')->count() }}</span>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-home fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No flats added to this project yet.</p>
                            <a href="{{ route('admin.flat.create') }}?project_id={{ $selectedProject->id }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add First Flat
                            </a>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeFlatsModal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this project? This action cannot be undone.
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
                    <div class="alert alert-danger">
                        <strong>Warning!</strong> This will permanently delete this project and all associated data. This action cannot be undone!
                    </div>
                    <p>Are you absolutely sure you want to permanently delete this project?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmPermanentDeleteBtn">
                        <i class="fas fa-trash-alt me-1"></i>Permanently Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .project-row:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
        .project-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in;
        }
    </style>

    <script>
        let projectIdToDelete = null;
        let projectIdToPermanentDelete = null;

        function confirmDelete(projectId) {
            projectIdToDelete = projectId;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function confirmPermanentDelete(projectId) {
            projectIdToPermanentDelete = projectId;
            const modal = new bootstrap.Modal(document.getElementById('permanentDeleteModal'));
            modal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (projectIdToDelete) {
                @this.deleteProject(projectIdToDelete);
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                modal.hide();
                projectIdToDelete = null;
            }
        });

        document.getElementById('confirmPermanentDeleteBtn').addEventListener('click', function() {
            if (projectIdToPermanentDelete) {
                @this.permanentDeleteProject(projectIdToPermanentDelete);
                const modal = bootstrap.Modal.getInstance(document.getElementById('permanentDeleteModal'));
                modal.hide();
                projectIdToPermanentDelete = null;
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

