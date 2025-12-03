<div class="container-fluid">
    <!-- Flat Type Info Modal -->
    @if($showFlatTypeInfo)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" wire:click.self="$set('showFlatTypeInfo', false)">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i>Flat Type Meanings
                    </h5>
                    <button type="button" class="btn-close" wire:click="$set('showFlatTypeInfo', false)" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Flat Type</th>
                                    <th>Meaning</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Studio</td><td>Single room + bathroom</td></tr>
                                <tr><td>1BHK</td><td>1 Bedroom, Hall, Kitchen</td></tr>
                                <tr><td>2BHK</td><td>2 Bedrooms, Hall, Kitchen</td></tr>
                                <tr><td>3BHK</td><td>3 Bedrooms, Hall, Kitchen</td></tr>
                                <tr><td>4BHK</td><td>4 Bedrooms, Hall, Kitchen</td></tr>
                                <tr><td>Duplex</td><td>Two-floor apartment</td></tr>
                                <tr><td>Triplex</td><td>Three-floor apartment</td></tr>
                                <tr><td>Penthouse</td><td>Top-floor luxury flat</td></tr>
                                <tr><td>Commercial</td><td>Shop/Office unit</td></tr>
                                <tr><td>Office</td><td>Office space</td></tr>
                                <tr><td>Shop</td><td>Shop room</td></tr>
                                <tr><td>Land Owner Share</td><td>Flats reserved for land owners</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showFlatTypeInfo', false)">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="card shadow">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-plus-circle me-2"></i> Add New Flat
                </h6>
                @if($selected_project)
                    <div class="flex-grow-1 d-flex justify-content-center align-items-center gap-3">
                        <div class="fw-bold text-primary">
                            <i class="fas fa-building me-1"></i>{{ $selected_project['project_name'] }}
                        </div>
                        @if($selected_project['address'])
                        <div class="small text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($selected_project['address'], 40) }}
                        </div>
                        @endif
                        @if($selected_project['facing'])
                        <div class="small text-muted">
                            <i class="fas fa-compass me-1"></i>{{ $selected_project['facing'] }}
                        </div>
                        @endif
                        @if($selected_project['land_owner_name'])
                        <div class="small text-muted">
                            <i class="fas fa-user-tie me-1"></i>{{ $selected_project['land_owner_name'] }}
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <div class="card-body py-3">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-7 px-0">
                    <!-- Flats to Add Card -->
                    <div class="card border">
                        <div class="card-header bg-light py-2">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <h6 class="mb-0"><i class="fas fa-list me-1"></i> Flats to Add ({{ count($flats_to_add) }})</h6>
                                
                                <!-- Project Search/Selection in Header -->
                                <div class="flex-grow-1 mx-3">
                            @if($selected_project)
                                        <div class="d-flex align-items-center gap-2 p-1 bg-info bg-opacity-10 rounded">
                                            <i class="fas fa-building text-primary"></i>
                                            <strong class="text-primary">{{ $selected_project['project_name'] }}</strong>
                                            <button type="button" class="btn btn-sm btn-outline-danger p-1 ms-auto" wire:click="clearProject" title="Clear Project">
                                                <i class="fas fa-times"></i>
                                        </button>
                                </div>
                            @else
                                        <input type="text" 
                                               id="project-search" 
                                               class="form-control form-control-sm" 
                                               wire:model.live.debounce.300ms="project_search" 
                                               placeholder="Search project by name or address..." 
                                               autocomplete="new-password">
                            @endif
                    </div>

                            <button type="button" class="btn btn-sm btn-primary" wire:click="addEmptyFlat">
                                <i class="fas fa-plus me-1"></i> Add
                            </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if(count($flats_to_add) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered flats-table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="fw-bold">Flat Number <span class="text-danger">*</span></th>
                                            <th class="fw-bold">
                                                Type <span class="text-danger">*</span>
                                                <i class="fas fa-info-circle text-info ms-1" 
                                                   wire:click="$set('showFlatTypeInfo', true)"
                                                   style="cursor: help;"
                                                   title="Click to view flat type meanings"></i>
                                            </th>
                                            <th class="fw-bold">Floor <span class="text-danger">*</span></th>
                                            <th class="fw-bold">Size (sq ft) <span class="text-danger">*</span></th>
                                            <th class="fw-bold">Status <span class="text-danger">*</span></th>
                                            <th class="fw-bold text-center" style="width: 80px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($flats_to_add as $index => $flat)
                                        <tr>
                                            <td>
                                                <input type="text" 
                                                       class="form-control form-control-sm @error('flats_to_add.'.$index.'.flat_number') is-invalid @enderror" 
                                                       wire:model.blur="flats_to_add.{{ $index }}.flat_number" 
                                                       placeholder="e.g., A-101">
                                                @error('flats_to_add.'.$index.'.flat_number')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       class="form-control form-control-sm @error('flats_to_add.'.$index.'.flat_type') is-invalid @enderror" 
                                                       wire:model.blur="flats_to_add.{{ $index }}.flat_type" 
                                                       placeholder="e.g., 2BHK, 3BHK">
                                                @error('flats_to_add.'.$index.'.flat_type')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       class="form-control form-control-sm @error('flats_to_add.'.$index.'.floor_number') is-invalid @enderror" 
                                                       wire:model.blur="flats_to_add.{{ $index }}.floor_number" 
                                                       placeholder="e.g., Ground, B1, B2, 1, 2">
                                                @error('flats_to_add.'.$index.'.floor_number')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       class="form-control form-control-sm @error('flats_to_add.'.$index.'.flat_size') is-invalid @enderror" 
                                                       wire:model.blur="flats_to_add.{{ $index }}.flat_size" 
                                                       placeholder="Size (e.g., 1200)"
                                                       min="0"
                                                       step="0.01">
                                                @error('flats_to_add.'.$index.'.flat_size')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm @error('flats_to_add.'.$index.'.status') is-invalid @enderror" 
                                                        wire:model.blur="flats_to_add.{{ $index }}.status">
                                                    <option value="available">Available</option>
                                                    <option value="sold">Sold</option>
                                                    <option value="reserved">Reserved</option>
                                                    <option value="land_owner">Land Owner</option>
                                                </select>
                                                @error('flats_to_add.'.$index.'.status')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td class="text-center">
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        wire:click="removeFlat({{ $index }})"
                                                        title="Remove flat">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-success" wire:click="saveFlats">
                                    <i class="fas fa-save me-1"></i> Save All Flats
                                </button>
                            </div>
                            @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-list fa-2x mb-3"></i>
                                <p class="mb-3">Click "Add" button to add a new flat</p>
                                <button type="button" class="btn btn-primary" wire:click="addEmptyFlat">
                                    <i class="fas fa-plus me-1"></i> Add Flat
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-5">
                    <!-- Search Results Area -->
                    <div class="card border" id="search-results-container">
                        <div class="card-header bg-primary text-white py-1">
                            <h6 class="mb-0">
                                <i class="fas fa-search me-1"></i> 
                                @if(strlen($project_search) >= 2)
                                    Search Results
                                @else
                                    Recent Projects ({{ count($project_results) }})
                                @endif
                            </h6>
                        </div>
                        <div class="card-body p-0" style="height: 500px; overflow-y: auto;" id="search-results-body">
                            @if(count($project_results) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="small">Name</th>
                                                <th class="small">Description</th>
                                                <th class="small">Address</th>
                                                <th class="small">Facing</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                @foreach($project_results as $result)
                                            <tr class="search-item" 
                                     wire:click="selectProject({{ $result['id'] }})"
                                     style="cursor: pointer;">
                                                <td class="small text-nowrap arrow-indicator" title="{{ $result['project_name'] ?? 'N/A' }}">
                                                    <span class="arrow-icon">▶</span>
                                                    <strong>{{ $result['project_name'] ?? 'N/A' }}</strong>
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['description'] ?? 'N/A' }}">
                                                    {{ Str::limit($result['description'] ?? 'N/A', 30) }}
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['address'] ?? 'N/A' }}">
                                                    {{ Str::limit($result['address'] ?? 'N/A', 30) }}
                                                </td>
                                                <td class="small text-nowrap">
                                                    {{ $result['facing'] ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-3 text-center text-muted">
                                    <i class="fas fa-search fa-2x mb-2"></i>
                                    <p>No projects found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
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
    /* Flats Table Styling */
    .flats-table {
        border-collapse: separate;
        border-spacing: 0;
    }
    .flats-table thead th {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-bottom: 2px solid #dee2e6;
        padding: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        vertical-align: middle;
        color: #495057;
    }
    .flats-table tbody td {
        border: 1px solid #dee2e6;
        padding: 0.75rem;
        vertical-align: middle;
        background-color: #ffffff;
    }
    .flats-table tbody tr:hover {
        background-color: #f1f3f5;
    }
    .flats-table tbody tr:hover td {
        background-color: #f1f3f5;
    }
    .flats-table tbody tr:last-child td {
        border-bottom: 1px solid #dee2e6;
    }
    .flats-table .form-control-sm {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        background-color: #ffffff;
        width: 100%;
        height: 100%;
        min-height: 38px;
        border: none;
        padding: 0.5rem;
        transform: none !important;
        transition: none !important;
    }
    .flats-table .form-control-sm:focus {
        border: none;
        box-shadow: none;
        background-color: #ffffff;
        outline: 2px solid #0d6efd;
        outline-offset: -2px;
        transform: none !important;
    }
    .flats-table .form-control-sm:active {
        transform: none !important;
    }
    .flats-table tbody td {
        padding: 0 !important;
    }
    .flats-table tbody td > * {
        width: 100%;
    }
    </style>
</div>
