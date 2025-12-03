<div>
    @if($project)
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $project->flats->count() }}</h4>
                                <p class="mb-0">Total Flats</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-home fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $project->total_units ?? 'N/A' }}</h4>
                                <p class="mb-0">Total Units</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-building fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $project->total_floors ?? 'N/A' }}</h4>
                                <p class="mb-0">Total Floors</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-layer-group fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">
                                    <span class="badge bg-{{ $project->status == 'active' ? 'success' : ($project->status == 'completed' ? 'info' : ($project->status == 'on_hold' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </h4>
                                <p class="mb-0">Status</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Project Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-building me-2"></i>Project Details
                        </h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit Project
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Projects
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Left Column - Basic Information -->
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fas fa-info-circle me-2"></i>Basic Information
                        </h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted mb-1">Project Name</label>
                            <div class="form-control-plaintext fw-semibold fs-5">{{ $project->project_name }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted mb-1">
                                <i class="fas fa-map-marker-alt me-1"></i>Address
                            </label>
                            <div class="form-control-plaintext">{{ $project->address }}</div>
                        </div>

                        @if($project->description)
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted mb-1">
                                <i class="fas fa-align-left me-1"></i>Description
                            </label>
                            <div class="form-control-plaintext">{{ $project->description }}</div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted mb-1">Status</label>
                            <div>
                                <span class="badge bg-{{ $project->status == 'active' ? 'success' : ($project->status == 'completed' ? 'info' : ($project->status == 'on_hold' ? 'warning' : ($project->status == 'planning' ? 'primary' : 'secondary'))) }} px-3 py-2">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Project Specifications -->
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fas fa-cogs me-2"></i>Project Specifications
                        </h6>

                        <div class="row">
                            @if($project->total_units)
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold small text-muted mb-1">
                                    <i class="fas fa-building me-1"></i>Total Units
                                </label>
                                <div class="form-control-plaintext fw-semibold">{{ $project->total_units }}</div>
                            </div>
                            @endif

                            @if($project->total_floors)
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold small text-muted mb-1">
                                    <i class="fas fa-layer-group me-1"></i>Total Floors
                                </label>
                                <div class="form-control-plaintext fw-semibold">{{ $project->total_floors }}</div>
                            </div>
                            @endif

                            @if($project->land_area)
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold small text-muted mb-1">
                                    <i class="fas fa-ruler-combined me-1"></i>Land Area
                                </label>
                                <div class="form-control-plaintext fw-semibold">{{ number_format($project->land_area, 2) }} sq ft</div>
                            </div>
                            @endif

                            @if($project->storey)
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold small text-muted mb-1">
                                    <i class="fas fa-layer-group me-1"></i>Storey
                                </label>
                                <div class="form-control-plaintext fw-semibold">{{ $project->storey }}</div>
                            </div>
                            @endif

                            @if($project->facing)
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold small text-muted mb-1">
                                    <i class="fas fa-compass me-1"></i>Facing
                                </label>
                                <div class="form-control-plaintext fw-semibold">{{ $project->facing }}</div>
                            </div>
                            @endif

                            @if($project->project_launching_date)
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold small text-muted mb-1">
                                    <i class="fas fa-calendar-alt me-1"></i>Launch Date
                                </label>
                                <div class="form-control-plaintext fw-semibold">
                                    {{ $project->project_launching_date->format('M d, Y') }}
                                </div>
                            </div>
                            @endif

                            @if($project->project_hand_over_date)
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold small text-muted mb-1">
                                    <i class="fas fa-calendar-check me-1"></i>Hand Over Date
                                </label>
                                <div class="form-control-plaintext fw-semibold">
                                    {{ $project->project_hand_over_date->format('M d, Y') }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Land Owner Information -->
                @if($project->land_owner_name || $project->land_owner_nid || $project->land_owner_phone)
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-12">
                        <h6 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fas fa-user-tie me-2"></i>Land Owner Information
                        </h6>
                    </div>
                    @if($project->land_owner_name)
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small text-muted mb-1">Land Owner Name</label>
                        <div class="form-control-plaintext fw-semibold">{{ $project->land_owner_name }}</div>
                    </div>
                    @endif
                    @if($project->land_owner_nid)
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small text-muted mb-1">Land Owner NID</label>
                        <div class="form-control-plaintext fw-semibold">{{ $project->land_owner_nid }}</div>
                    </div>
                    @endif
                    @if($project->land_owner_phone)
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small text-muted mb-1">Land Owner Phone</label>
                        <div class="form-control-plaintext fw-semibold">{{ $project->land_owner_phone }}</div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Document Soft Copy -->
                @if($project->attachments && $project->attachments->count() > 0)
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-12">
                        <h6 class="text-primary mb-3 border-bottom pb-2">
                            <i class="fas fa-paperclip me-2"></i>Document Soft Copy
                        </h6>
                        @foreach($project->attachments as $attachment)
                        <div class="card mb-2 border">
                            <div class="card-body p-2">
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto text-center" style="width: 30px;">
                                        <span class="text-muted">{{ $loop->iteration }}.</span>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex align-items-center">
                                            @if(in_array(strtolower(pathinfo($attachment->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <i class="fas fa-image fa-lg text-primary me-2"></i>
                                            @else
                                                <i class="fas fa-file-pdf fa-lg text-danger me-2"></i>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 small fw-bold">{{ $attachment->document_name }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <a href="{{ route('admin.documents.show', $attachment->id) }}" 
                                           target="_blank" 
                                           class="btn btn-xs btn-outline-info me-1"
                                           title="View">
                                            <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Soft Deleted Attachments (Archive) -->
                @if(!empty($deletedAttachments))
                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-muted mb-0">
                                    <i class="fas fa-archive me-2"></i>Deleted Documents (Archive)
                                </h6>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-secondary" 
                                        wire:click="$set('showDeleted', {{ !$showDeleted ? 'true' : 'false' }})">
                                    <i class="fas fa-{{ $showDeleted ? 'chevron-up' : 'chevron-down' }} me-1"></i>
                                    {{ $showDeleted ? 'Hide' : 'Show' }} ({{ count($deletedAttachments) }})
                                </button>
                            </div>
                            
                            @if($showDeleted)
                                @foreach($deletedAttachments as $deletedAttachment)
                                    <div class="card mb-2 border" style="opacity: 0.7; background-color: #f8f9fa;">
                                        <div class="card-body p-2">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-auto text-center" style="width: 30px;">
                                                    <span class="text-muted">{{ $loop->iteration }}.</span>
                                                </div>
                                                <div class="col">
                                                    <div class="d-flex align-items-center">
                                                        @if(in_array(strtolower(pathinfo($deletedAttachment['file_path'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                            <i class="fas fa-image fa-lg text-muted me-2"></i>
                                                        @else
                                                            <i class="fas fa-file-pdf fa-lg text-muted me-2"></i>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0 small fw-bold text-muted">
                                                                <s>{{ $deletedAttachment['document_name'] }}</s>
                                                            </h6>
                                                            <small class="text-muted">
                                                                Deleted: {{ \Carbon\Carbon::parse($deletedAttachment['deleted_at'])->format('M d, Y h:i A') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 text-end">
                                                    <a href="{{ route('admin.documents.show', $deletedAttachment['id']) }}" 
                                                       target="_blank" 
                                                       class="btn btn-xs btn-outline-info"
                                                       title="View">
                                                        <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Additional Information -->
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-user me-2"></i>Created By
                        </h6>
                        <div class="text-muted small">
                            @if($project->createdBy)
                                {{ $project->createdBy->name ?? $project->createdBy->email }}
                            @else
                                System
                            @endif
                            @if($project->created_at)
                                <br><small class="text-muted">on {{ $project->created_at->format('M d, Y h:i A') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-user-edit me-2"></i>Last Updated By
                        </h6>
                        <div class="text-muted small">
                            @if($project->updatedBy)
                                {{ $project->updatedBy->name ?? $project->updatedBy->email }}
                            @else
                                System
                            @endif
                            @if($project->updated_at)
                                <br><small class="text-muted">on {{ $project->updated_at->format('M d, Y h:i A') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flats List Card -->
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-home me-2"></i>Project Flats ({{ $project->flats->count() }})
                        </h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.project-flat.create') }}?project_id={{ $project->id }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Flat
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($project->flats->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
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
                                @foreach($project->flats as $flat)
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
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.project-flat.show', $flat->id) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.project-flat.edit', $flat->id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-home fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No flats added to this project yet.</p>
                        <a href="{{ route('admin.project-flat.create') }}?project_id={{ $project->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add First Flat
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-building fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Project Not Found</h5>
                <p class="text-muted">The project you are looking for does not exist.</p>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Projects
                </a>
            </div>
        </div>
    @endif
</div>
