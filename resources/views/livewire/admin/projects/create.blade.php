<div class="container-fluid">
    <!-- Main Card -->
    <div class="card shadow border-0">
        <div class="card-body py-3">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <!-- Project Name -->
                    <div class="col-md-6">
                        <label for="project_name" class="form-label small fw-bold">Project Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control form-control-sm @error('project_name') is-invalid @enderror" 
                               id="project_name" 
                               wire:model="project_name" 
                               placeholder="Enter project name">
                        @error('project_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label small fw-bold">Status <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm @error('status') is-invalid @enderror" 
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
                    <div class="col-12">
                        <label for="address" class="form-label small fw-bold">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-sm @error('address') is-invalid @enderror" 
                                  id="address" 
                                  wire:model="address" 
                                  rows="3" 
                                  placeholder="Enter complete address"></textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label small fw-bold">Description</label>
                        <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" 
                                  id="description" 
                                  wire:model="description" 
                                  rows="4" 
                                  placeholder="Enter project description"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Facing -->
                    <div class="col-md-6">
                        <label for="facing" class="form-label small fw-bold">Facing Direction</label>
                        <select class="form-select form-select-sm @error('facing') is-invalid @enderror" 
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
                    <div class="col-md-6">
                        <label for="building_height" class="form-label small fw-bold">Building Height (feet)</label>
                        <input type="text" 
                               class="form-control form-control-sm @error('building_height') is-invalid @enderror" 
                               id="building_height" 
                               wire:model="building_height" 
                               placeholder="Enter building height">
                        @error('building_height')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Land Area -->
                    <div class="col-md-6">
                        <label for="land_area" class="form-label small fw-bold">Land Area (sq ft)</label>
                        <input type="text" 
                               class="form-control form-control-sm @error('land_area') is-invalid @enderror" 
                               id="land_area" 
                               wire:model="land_area" 
                               placeholder="Enter land area">
                        @error('land_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Total Floors -->
                    <div class="col-md-6">
                        <label for="total_floors" class="form-label small fw-bold">Total Floors</label>
                        <input type="number" 
                               class="form-control form-control-sm @error('total_floors') is-invalid @enderror" 
                               id="total_floors" 
                               wire:model="total_floors" 
                               min="1" 
                               placeholder="Enter total floors">
                        @error('total_floors')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Project Launching Date -->
                    <div class="col-md-6">
                        <label for="project_launching_date" class="form-label small fw-bold">Project Launching Date</label>
                        <input type="date" 
                               class="form-control form-control-sm @error('project_launching_date') is-invalid @enderror" 
                               id="project_launching_date" 
                               wire:model="project_launching_date">
                        @error('project_launching_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Project Hand Over Date -->
                    <div class="col-md-6">
                        <label for="project_hand_over_date" class="form-label small fw-bold">Project Hand Over Date</label>
                        <input type="date" 
                               class="form-control form-control-sm @error('project_hand_over_date') is-invalid @enderror" 
                               id="project_hand_over_date" 
                               wire:model="project_hand_over_date">
                        @error('project_hand_over_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" 
                                    class="btn btn-sm btn-outline-secondary" 
                                    wire:click="cancel">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                            <button type="submit" 
                                    class="btn btn-sm btn-primary" 
                                    wire:loading.attr="disabled"
                                    wire:target="save">
                                <span wire:loading.remove wire:target="save">
                                    <i class="fas fa-save me-1"></i> Create Project
                                </span>
                                <span wire:loading wire:target="save">
                                    <i class="fas fa-spinner fa-spin me-1"></i> Creating...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>