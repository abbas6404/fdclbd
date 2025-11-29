<div class="container-fluid">
    <!-- Main Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-building me-2"></i> All Projects
                </h6>
                <div class="d-flex gap-2">
                    <!-- View Mode Toggle -->
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" 
                                class="btn btn-outline-primary {{ $viewMode === 'table' ? 'active' : '' }}"
                                wire:click="$set('viewMode', 'table')"
                                title="Table View">
                            <i class="fas fa-table"></i>
                        </button>
                        <button type="button" 
                                class="btn btn-outline-primary {{ $viewMode === 'grid' ? 'active' : '' }}"
                                wire:click="$set('viewMode', 'grid')"
                                title="Grid View">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" 
                                class="btn btn-outline-primary {{ $viewMode === 'card' ? 'active' : '' }}"
                                wire:click="$set('viewMode', 'card')"
                                title="Card View">
                            <i class="fas fa-th-large"></i>
                        </button>
                    </div>
                    <a href="{{ route('admin.projects.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Project
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Filters -->
            <div class="row mb-3 g-2">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control form-control-sm" 
                               placeholder="Search projects by name, address..." 
                               wire:model.live.debounce.300ms="search">
                    </div>
                </div>
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
                <div class="col-md-2">
                    <select class="form-select form-select-sm" wire:model.live="perPage">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="btn-group w-100">
                        <button type="button" 
                                class="btn btn-sm btn-outline-secondary" 
                                wire:click="$refresh"
                                title="Refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button type="button" 
                                class="btn btn-sm btn-outline-info" 
                                wire:click="$toggle('showAdvancedFilters')"
                                title="Advanced Filters">
                            <i class="fas fa-filter"></i>
                        </button>
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
            @if($viewMode === 'table')
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
                            <th>Address</th>
                            <th wire:click="sortBy('facing')" style="cursor: pointer;" class="user-select-none">
                                Facing
                                @if($sortField === 'facing')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('building_height')" style="cursor: pointer;" class="user-select-none">
                                Building Height
                                @if($sortField === 'building_height')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('land_area')" style="cursor: pointer;" class="user-select-none">
                                Land Area
                                @if($sortField === 'land_area')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th>Available</th>
                            <th>Sold</th>
                            <th>Reserved</th>
                            <th wire:click="sortBy('project_launching_date')" style="cursor: pointer;" class="user-select-none">
                                Launch Date
                                @if($sortField === 'project_launching_date')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('project_hand_over_date')" style="cursor: pointer;" class="user-select-none">
                                Handover Date
                                @if($sortField === 'project_hand_over_date')
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
                            <td style="cursor: pointer; color: #0066cc;" wire:click="showProjectFlats({{ $project->id }})" title="Click to view flats">{{ Str::limit($project->address, 30) }}</td>
                            <td>
                                @if($project->facing)
                                    <span class="badge bg-info">{{ $project->facing }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                {{ $project->building_height ?? 'N/A' }}
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
                            <td>{{ $project->project_launching_date ? \Carbon\Carbon::parse($project->project_launching_date)->format('M d, y') : 'N/A' }}</td>
                            <td>{{ $project->project_hand_over_date ? \Carbon\Carbon::parse($project->project_hand_over_date)->format('M d, y') : 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $project->status == 'ongoing' ? 'success' : ($project->status == 'completed' ? 'info' : ($project->status == 'on_hold' ? 'warning' : ($project->status == 'upcoming' ? 'primary' : 'secondary'))) }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" 
                                            class="btn btn-outline-primary" 
                                            title="View Flats"
                                            wire:click="showProjectFlats({{ $project->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.projects.edit', $project->id) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            title="Delete"
                                            onclick="confirmDelete({{ $project->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
            @endif

            <!-- Grid View -->
            @if($viewMode === 'grid')
            <div class="row g-3">
                @forelse($projects as $project)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 project-card" style="transition: transform 0.2s;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0 fw-bold" style="cursor: pointer; color: #0066cc;" wire:click="showProjectFlats({{ $project->id }})" title="Click to view flats">{{ $project->project_name }}</h6>
                                <span class="badge bg-{{ $project->status == 'ongoing' ? 'success' : ($project->status == 'completed' ? 'info' : ($project->status == 'on_hold' ? 'warning' : ($project->status == 'upcoming' ? 'primary' : 'secondary'))) }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </div>
                            <p class="text-muted small mb-2" style="cursor: pointer; color: #0066cc;" wire:click="showProjectFlats({{ $project->id }})" title="Click to view flats">{{ Str::limit($project->address, 50) }}</p>
                            <div class="small text-muted mb-2">
                                @if($project->facing)
                                    <div><i class="fas fa-compass me-1"></i> Facing: <span class="badge bg-info">{{ $project->facing }}</span></div>
                                @endif
                                @if($project->building_height)
                                    <div><i class="fas fa-arrows-alt-v me-1"></i> Height: {{ $project->building_height }}</div>
                                @endif
                                @if($project->land_area)
                                    <div><i class="fas fa-expand-arrows-alt me-1"></i> Land: {{ $project->land_area }}</div>
                                @endif
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-4">
                                    <div class="text-center p-2 bg-light rounded" style="cursor: pointer;" wire:click="showProjectFlats({{ $project->id }}, 'available')" title="Click to view available flats">
                                        <div class="fw-bold text-success">{{ $project->available_flats_count ?? 0 }}</div>
                                        <small class="text-muted">Available</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center p-2 bg-light rounded" style="cursor: pointer;" wire:click="showProjectFlats({{ $project->id }}, 'sold')" title="Click to view sold flats">
                                        <div class="fw-bold text-danger">{{ $project->sold_flats_count ?? 0 }}</div>
                                        <small class="text-muted">Sold</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center p-2 bg-light rounded" style="cursor: pointer;" wire:click="showProjectFlats({{ $project->id }}, 'reserved')" title="Click to view reserved flats">
                                        <div class="fw-bold text-warning">{{ $project->reserved_flats_count ?? 0 }}</div>
                                        <small class="text-muted">Reserved</small>
                                    </div>
                                </div>
                            </div>
                            <div class="small text-muted mb-3">
                                <div><i class="fas fa-calendar-alt me-1"></i> Launch: {{ $project->project_launching_date ? \Carbon\Carbon::parse($project->project_launching_date)->format('M d, y') : 'N/A' }}</div>
                                <div><i class="fas fa-calendar-check me-1"></i> Handover: {{ $project->project_hand_over_date ? \Carbon\Carbon::parse($project->project_hand_over_date)->format('M d, y') : 'N/A' }}</div>
                            </div>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary flex-fill" wire:click="showProjectFlats({{ $project->id }})" title="View Flats">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-building fa-3x mb-3"></i>
                        <p class="mb-2">No projects found.</p>
                        <a href="{{ route('admin.projects.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Create Your First Project
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
            @endif

            <!-- Card View -->
            @if($viewMode === 'card')
            <div class="row g-3">
                @forelse($projects as $project)
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm project-card" style="transition: all 0.2s;">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <h6 class="mb-1 fw-bold" style="cursor: pointer; color: #0066cc;" wire:click="showProjectFlats({{ $project->id }})" title="Click to view flats">{{ $project->project_name }}</h6>
                                    <p class="text-muted small mb-2" style="cursor: pointer; color: #0066cc;" wire:click="showProjectFlats({{ $project->id }})" title="Click to view flats">{{ Str::limit($project->address, 40) }}</p>
                                    <span class="badge bg-{{ $project->status == 'active' ? 'success' : ($project->status == 'completed' ? 'info' : ($project->status == 'on_hold' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted d-block">Facing</small>
                                    <div>@if($project->facing)<span class="badge bg-info">{{ $project->facing }}</span>@else<span class="text-muted">N/A</span>@endif</div>
                                    <small class="text-muted d-block mt-2">Building Height</small>
                                    <div>@if($project->building_height){{ $project->building_height }}@else<span class="text-muted">N/A</span>@endif</div>
                                    <small class="text-muted d-block mt-2">Land Area</small>
                                    <div>@if($project->land_area){{ $project->land_area }}@else<span class="text-muted">N/A</span>@endif</div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex gap-3">
                                        <div class="text-center" style="cursor: pointer;" wire:click="showProjectFlats({{ $project->id }}, 'available')" title="Click to view available flats">
                                            <div class="fw-bold text-success">{{ $project->available_flats_count ?? 0 }}</div>
                                            <small class="text-muted">Available</small>
                                        </div>
                                        <div class="text-center" style="cursor: pointer;" wire:click="showProjectFlats({{ $project->id }}, 'sold')" title="Click to view sold flats">
                                            <div class="fw-bold text-danger">{{ $project->sold_flats_count ?? 0 }}</div>
                                            <small class="text-muted">Sold</small>
                                        </div>
                                        <div class="text-center" style="cursor: pointer;" wire:click="showProjectFlats({{ $project->id }}, 'reserved')" title="Click to view reserved flats">
                                            <div class="fw-bold text-warning">{{ $project->reserved_flats_count ?? 0 }}</div>
                                            <small class="text-muted">Reserved</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Launch Date</small>
                                    <div>{{ $project->project_launching_date ? \Carbon\Carbon::parse($project->project_launching_date)->format('M d, y') : 'N/A' }}</div>
                                    <small class="text-muted d-block mt-2">Handover Date</small>
                                    <div>{{ $project->project_hand_over_date ? \Carbon\Carbon::parse($project->project_hand_over_date)->format('M d, y') : 'N/A' }}</div>
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" wire:click="showProjectFlats({{ $project->id }})" title="View Flats">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $project->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-building fa-3x mb-3"></i>
                        <p class="mb-2">No projects found.</p>
                        <a href="{{ route('admin.projects.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Create Your First Project
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
            @endif

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
                    <button type="button" class="btn-close" wire:click="closeFlatsModal" aria-label="Close"></button>
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
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($flat->status ?? 'N/A') }}</span>
                                            @endif
                                        </td>
                                        <td>৳{{ number_format($flat->price_per_sqft ?? 0, 2) }}</td>
                                        <td>৳{{ number_format($flat->total_price ?? 0, 2) }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.project-flat.show', $flat->id) }}" 
                                                   class="btn btn-outline-info" 
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.project-flat.edit', $flat->id) }}" 
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
                                <span class="text-warning"><strong>Reserved:</strong> {{ $selectedProject->flats->where('status', 'reserved')->count() }}</span>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-home fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No flats added to this project yet.</p>
                            <a href="{{ route('admin.project-flat.create') }}?project_id={{ $selectedProject->id }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add First Flat
                            </a>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeFlatsModal">Close</button>
                    <a href="{{ route('admin.projects.show', $selectedProject->id) }}" class="btn btn-primary">
                        <i class="fas fa-external-link-alt me-1"></i>View Full Details
                    </a>
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

        function confirmDelete(projectId) {
            projectIdToDelete = projectId;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
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

    </script>
</div>

