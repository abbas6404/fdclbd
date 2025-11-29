<div class="container-fluid">
    <!-- Main Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-plus-circle me-2"></i> Add New Customer
                </h6>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Customers
                </a>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Alert Display -->
            <div id="alert-container"></div>

            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <!-- Customer Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label small fw-bold">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control form-control-sm @error('name') is-invalid @enderror" 
                               id="name" 
                               wire:model="name" 
                               placeholder="Enter customer name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Father/Husband Name -->
                    <div class="col-md-6">
                        <label for="father_or_husband_name" class="form-label small fw-bold">Father/Husband Name</label>
                        <input type="text" 
                               class="form-control form-control-sm @error('father_or_husband_name') is-invalid @enderror" 
                               id="father_or_husband_name" 
                               wire:model="father_or_husband_name" 
                               placeholder="Enter father or husband name">
                        @error('father_or_husband_name')
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

                    <!-- NID/Passport Number -->
                    <div class="col-md-6">
                        <label for="nid_or_passport_number" class="form-label small fw-bold">NID/Passport Number</label>
                        <input type="text" 
                               class="form-control form-control-sm @error('nid_or_passport_number') is-invalid @enderror" 
                               id="nid_or_passport_number" 
                               wire:model="nid_or_passport_number" 
                               placeholder="Enter NID or passport number">
                        @error('nid_or_passport_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-12">
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
                </div>

                <!-- Form Actions -->
                <div class="row mt-4 pt-3 border-top">
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-save me-1"></i> Create Customer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
