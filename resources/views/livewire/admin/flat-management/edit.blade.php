<div class="container-fluid">
    <div class="card shadow border-0">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-edit me-2"></i> Edit Flat
                </h6>
                <a href="{{ route('admin.project-flat.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="update">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="project_id" class="form-label">Project <span class="text-danger">*</span></label>
                            <select class="form-select @error('project_id') is-invalid @enderror" 
                                    id="project_id" 
                                    wire:model="project_id"
                                    disabled>
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Project cannot be changed after creation</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="flat_number" class="form-label">Flat Number <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('flat_number') is-invalid @enderror" 
                                   id="flat_number" 
                                   wire:model="flat_number"
                                   placeholder="e.g., A-101">
                            @error('flat_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="flat_type" class="form-label">Type <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('flat_type') is-invalid @enderror" 
                                   id="flat_type" 
                                   wire:model="flat_type"
                                   placeholder="e.g., 2BHK, 3BHK">
                            @error('flat_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="floor_number" class="form-label">Floor <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('floor_number') is-invalid @enderror" 
                                   id="floor_number" 
                                   wire:model="floor_number"
                                   placeholder="e.g., Ground, B1, B2, 1, 2">
                            @error('floor_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="flat_size" class="form-label">Size (sq ft) <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('flat_size') is-invalid @enderror" 
                                   id="flat_size" 
                                   wire:model="flat_size"
                                   placeholder="e.g., 1500sqft, 1200 sq ft">
                            @error('flat_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    wire:model="status">
                                <option value="available">Available</option>
                                <option value="sold">Sold</option>
                                <option value="reserved">Reserved</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.project-flat.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Flat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
