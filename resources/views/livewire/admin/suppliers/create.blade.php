<div class="container-fluid">
    <!-- Main Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-plus-circle me-2"></i> Add New Supplier
                </h6>
                <a href="{{ route('admin.supplier.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Suppliers
                </a>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Alert Display -->
            <div id="alert-container"></div>

            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <!-- Supplier Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label small fw-bold">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control form-control-sm @error('name') is-invalid @enderror" 
                               id="name" 
                               wire:model="name" 
                               placeholder="Enter supplier name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label small fw-bold">Email Address</label>
                        <input type="email" 
                               class="form-control form-control-sm @error('email') is-invalid @enderror" 
                               id="email" 
                               wire:model="email" 
                               placeholder="Enter email address">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label for="phone" class="form-label small fw-bold">Phone Number</label>
                        <input type="text" 
                               class="form-control form-control-sm @error('phone') is-invalid @enderror" 
                               id="phone" 
                               wire:model="phone" 
                               placeholder="Enter phone number">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-md-6">
                        <label for="address" class="form-label small fw-bold">Address</label>
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
                                  rows="3" 
                                  placeholder="Enter any additional description or remarks"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.supplier.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-save me-1"></i> Create Supplier
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
