@extends('admin.layouts.app')

@section('title', 'Add New Admin')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Add New Admin</h1>
            <p class="text-muted mb-0">Create a new admin user account</p>
        </div>
    </div>
<div class="row">
    <!-- Left Side - User Information -->
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-shield me-2"></i> User Information
                    </h6>
                    <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.admin-users.store') }}" enctype="multipart/form-data" id="adminForm">
                    @csrf
                    
                    <!-- Hidden field to ensure roles are always submitted -->
                    <input type="hidden" name="roles" id="rolesHidden" value="">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label fw-bold">Admin Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" required 
                                       placeholder="e.g., ADM001">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="e.g., +880 1234-567890">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required minlength="8">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" required minlength="8">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="show_password" onchange="togglePasswordVisibility()">
                                    <label class="form-check-label" for="show_password">
                                        Show Password
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-1"></i> Create Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Side - Role Selection -->
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-tag me-2"></i> Role Assignment
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Select Roles <span class="text-danger">*</span></label>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllRoles">
                                <i class="fas fa-check-double me-1"></i>All
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="clearAllRoles">
                                <i class="fas fa-times me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                    
                    <div class="roles-container border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                        @if($roles->count() > 0)
                            @foreach($roles as $role)
                                <div class="form-check role-item mb-2">
                                    <input class="form-check-input role-checkbox" 
                                           type="checkbox" 
                                           value="{{ $role->id }}" 
                                           id="role_{{ $role->id }}"
                                           {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center" for="role_{{ $role->id }}">
                                        <span class="badge bg-primary me-2">{{ $role->name }}</span>
                                        <small class="text-muted">({{ $role->users_count ?? 0 }} users)</small>
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-exclamation-triangle text-warning fa-2x mb-2"></i>
                                <p class="text-muted mb-0 small">No roles available</p>
                            </div>
                        @endif
                    </div>
                    
                    @error('roles')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> At least one role must be selected. Users can have multiple roles for enhanced permissions.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.roles-container::-webkit-scrollbar {
    width: 6px;
}

.roles-container::-webkit-scrollbar-track {
    background: #f8f9fa;
}

.roles-container::-webkit-scrollbar-thumb {
    background: #dee2e6;
    border-radius: 3px;
}

.roles-container::-webkit-scrollbar-thumb:hover {
    background: #adb5bd;
}

.role-item {
    transition: all 0.2s ease;
    padding: 8px;
    border-radius: 6px;
}

.role-item:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-check-label {
    cursor: pointer;
    transition: color 0.2s ease;
}

.form-check-label:hover {
    color: #0d6efd;
}

.role-checkbox:checked + .form-check-label .badge {
    background-color: #198754 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('selectAllRoles');
    const clearAllBtn = document.getElementById('clearAllRoles');
    const roleCheckboxes = document.querySelectorAll('.role-checkbox');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('adminForm');
    const rolesHidden = document.getElementById('rolesHidden');
    
    // Function to update hidden field with selected roles
    function updateRolesHidden() {
        const checkedRoles = document.querySelectorAll('.role-checkbox:checked');
        const roleIds = Array.from(checkedRoles).map(checkbox => checkbox.value);
        rolesHidden.value = roleIds.join(',');
    }
    
    // Select All Roles
    selectAllBtn.addEventListener('click', function() {
        roleCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSubmitButton();
        updateRolesHidden();
    });
    
    // Clear All Roles
    clearAllBtn.addEventListener('click', function() {
        roleCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSubmitButton();
        updateRolesHidden();
    });
    
    // Update submit button state and hidden field when roles change
    roleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSubmitButton();
            updateRolesHidden();
        });
    });
    
    // Function to update submit button state
    function updateSubmitButton() {
        const checkedRoles = document.querySelectorAll('.role-checkbox:checked');
        if (checkedRoles.length === 0) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Select Roles First';
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Create Admin';
        }
    }
    
    // Initialize submit button state and hidden field
    updateSubmitButton();
    updateRolesHidden();
    
    // Form validation
    form.addEventListener('submit', function(e) {
        const checkedRoles = document.querySelectorAll('.role-checkbox:checked');
        if (checkedRoles.length === 0) {
            e.preventDefault();
            alert('Please select at least one role before creating the admin user.');
            return false;
        }
        
        // Update hidden field before submission
        updateRolesHidden();
    });
});

function togglePasswordVisibility() {
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('password_confirmation');
    const showPassword = document.getElementById('show_password');
    
    if (showPassword.checked) {
        passwordField.type = 'text';
        confirmPasswordField.type = 'text';
    } else {
        passwordField.type = 'password';
        confirmPasswordField.type = 'password';
    }
}
</script>
</div>
@endsection
