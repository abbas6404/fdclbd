<div>
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">Edit</h4>
                            <p class="mb-0">Project Update</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-edit fa-2x"></i>
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
                            <h4 class="mb-0">Required</h4>
                            <p class="mb-0">Fields Marked</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-asterisk fa-2x"></i>
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
                            <h4 class="mb-0">Auto</h4>
                            <p class="mb-0">Validation</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
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
                            <h4 class="mb-0">Real-time</h4>
                            <p class="mb-0">Live Updates</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-bolt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0">Edit Project</h5>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Projects
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="update">
                <div class="row">
                    <!-- Project Name -->
                    <div class="col-md-6 mb-3">
                        <label for="project_name" class="form-label">Project Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('project_name') is-invalid @enderror" 
                               id="project_name" 
                               wire:model="project_name" 
                               placeholder="Enter project name">
                        @error('project_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                wire:model="status">
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="on_hold">On Hold</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-12 mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  wire:model="address" 
                                  rows="3" 
                                  placeholder="Enter complete address"></textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  wire:model="description" 
                                  rows="4" 
                                  placeholder="Enter project description"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Facing -->
                    <div class="col-md-6 mb-3">
                        <label for="facing" class="form-label">Facing Direction</label>
                        <select class="form-select @error('facing') is-invalid @enderror" 
                                id="facing" 
                                wire:model="facing">
                            <option value="">Select Facing</option>
                            <option value="North">North</option>
                            <option value="South">South</option>
                            <option value="East">East</option>
                            <option value="West">West</option>
                            <option value="North-East">North-East</option>
                            <option value="North-West">North-West</option>
                            <option value="South-East">South-East</option>
                            <option value="South-West">South-West</option>
                        </select>
                        @error('facing')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Building Height -->
                    <div class="col-md-6 mb-3">
                        <label for="building_height" class="form-label">Building Height (feet)</label>
                        <input type="text" 
                               class="form-control @error('building_height') is-invalid @enderror" 
                               id="building_height" 
                               wire:model="building_height" 
                               placeholder="Enter building height">
                        @error('building_height')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Land Area -->
                    <div class="col-md-6 mb-3">
                        <label for="land_area" class="form-label">Land Area (sq ft)</label>
                        <input type="text" 
                               class="form-control @error('land_area') is-invalid @enderror" 
                               id="land_area" 
                               wire:model="land_area" 
                               placeholder="Enter land area">
                        @error('land_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Total Floors -->
                    <div class="col-md-6 mb-3">
                        <label for="total_floors" class="form-label">Total Floors</label>
                        <input type="number" 
                               class="form-control @error('total_floors') is-invalid @enderror" 
                               id="total_floors" 
                               wire:model="total_floors" 
                               min="1" 
                               placeholder="Enter total floors">
                        @error('total_floors')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Project Launching Date -->
                    <div class="col-md-6 mb-3">
                        <label for="project_launching_date" class="form-label">Project Launching Date</label>
                        <input type="date" 
                               class="form-control @error('project_launching_date') is-invalid @enderror" 
                               id="project_launching_date" 
                               wire:model="project_launching_date">
                        @error('project_launching_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Project Hand Over Date -->
                    <div class="col-md-6 mb-3">
                        <label for="project_hand_over_date" class="form-label">Project Hand Over Date</label>
                        <input type="date" 
                               class="form-control @error('project_hand_over_date') is-invalid @enderror" 
                               id="project_hand_over_date" 
                               wire:model="project_hand_over_date">
                        @error('project_hand_over_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" 
                                    class="btn btn-secondary" 
                                    wire:click="cancel">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" 
                                    class="btn btn-primary" 
                                    wire:loading.attr="disabled"
                                    wire:target="update">
                                <span wire:loading.remove wire:target="update">
                                    <i class="fas fa-save"></i> Update Project
                                </span>
                                <span wire:loading wire:target="update">
                                    <i class="fas fa-spinner fa-spin"></i> Updating...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
