@extends('admin.setup.setup-layout')

@section('page-title', 'Edit Role')
@section('page-description', 'Modify role permissions and details')

@section('setup-content')
<div class="card shadow">
    <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-edit me-2"></i>Edit Role: {{ $role->name }}
        </h6>
        <a href="{{ route('admin.setup.role.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>
    
    <div class="card-body p-3">
        <form action="{{ route('admin.setup.role.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-2">
                        <label for="name" class="form-label fw-bold small">Role Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control form-control-sm @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $role->name) }}" 
                               placeholder="e.g., Sales Manager, Project Manager, Accountant"
                               {{ $role->name === 'Super Admin' && !$showSuperAdmin ? 'readonly' : '' }}
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Permissions Section -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label fw-bold mb-0 small">Permissions Matrix</label>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="selectAll">
                            <i class="fas fa-check-double me-1"></i>All
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearAll">
                            <i class="fas fa-times me-1"></i>Clear
                        </button>
                    </div>
                </div>
                
                <div class="permissions-matrix-container border rounded" style="max-height: 90vh; overflow: auto;">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                            <th class="text-center fw-bold small">
                                    <span class="badge bg-primary rotated-text">Sort Order</span>
                                </th>  
                                <th class="text-center fw-bold small">
                                    <span class="badge bg-primary">Permission Name</span>
                                </th>
                                <th class="text-center fw-bold small">
                                    <span class="badge bg-primary rotated-text">Menu</span>
                                </th>
                                <th class="text-center fw-bold small">
                                    <span class="badge bg-info rotated-text">View Details</span>
                                </th>
                                <th class="text-center fw-bold small">
                                    <span class="badge bg-success rotated-text">Create</span>
                                </th>
                                <th class="text-center fw-bold small">
                                    <span class="badge bg-warning rotated-text">Edit</span>
                                </th>
                                <th class="text-center fw-bold small">
                                    <span class="badge bg-danger rotated-text">Delete</span>
                                </th>
                                <th class="text-center fw-bold small">
                                    <span class="badge bg-secondary rotated-text">Restore Deleted</span>
                                </th>
                                <th class="text-center fw-bold small">
                                    <span class="badge bg-dark rotated-text">Delete Permanently</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $permissionTypes = ['menu', 'view', 'create', 'edit', 'delete', 'restore', 'delete_permanent'];
                                $permissionMatrix = [];
                                
                                // Get all groups for display ordered by sort_order
                                $allGroups = \App\Models\PermissionGroup::orderBy('sort_order')->orderBy('name')->get();
                                
                                // Get all permissions grouped by their permission group
                                $allPermissions = \App\Models\Permission::with('permissionGroup')->get();
                                
                                // Group permissions by group name and type
                                foreach($allGroups as $group) {
                                    $groupPermissions = $allPermissions->where('permission_group_id', $group->id);
                                    $permissionMatrix[$group->name] = [];
                                    foreach($groupPermissions as $permission) {
                                        if (isset($permission->type)) {
                                            $permissionMatrix[$group->name][$permission->type] = $permission;
                                        }
                                    }
                                }
                            @endphp
                            
                            @foreach($allGroups as $group)
                                @php $groupName = $group->name; @endphp
                                <tr class="align-middle">
                                    <td class="text-center fw-bold small align-middle p-0 " style=" border-right: 1px solid rgb(148, 180, 212);">
                                        <span >{{ $group->sort_order }}</span>
                                    </td>
                                    <td class="fw-medium align-middle p-0 ">
                                        <div class="d-inline-block text-center" style="padding-left: 1rem;">
                                        {{ $groupName }}
                                        </div>
                                    </td>
                                    @foreach($permissionTypes as $type)
                                        <td class="text-center p-0">
                                            @if(isset($permissionMatrix[$groupName][$type]))
                                                @php $permission = $permissionMatrix[$groupName][$type]; @endphp
                                                <div class="form-check m-0 p-0">
                                                    <input style="border-color:rgb(255, 0, 43); margin:auto;" class="form-check-input permission-checkbox" 
                                                           type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}" 
                                                           id="permission_{{ $permission->id }}"
                                                           {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}
                                                           {{ $role->name === 'Super Admin' && !$showSuperAdmin ? 'disabled' : '' }}>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @error('permissions')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-sm px-4" {{ $role->name === 'Super Admin' && !$showSuperAdmin ? 'disabled' : '' }}>
                    <i class="fas fa-save me-2"></i>Update Role
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.permissions-matrix-container::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.permissions-matrix-container::-webkit-scrollbar-track {
    background: #f8f9fa;
}

.permissions-matrix-container::-webkit-scrollbar-thumb {
    background: #dee2e6;
    border-radius: 3px;
}

.permissions-matrix-container::-webkit-scrollbar-thumb:hover {
    background: #adb5bd;
}

.permissions-matrix-container .table {
    margin-bottom: 0;
}

.permissions-matrix-container .table th {
    border-bottom: 2px solid #dee2e6;
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 10;
}

.permissions-matrix-container .table td {
    vertical-align: middle;
    padding: 0.5rem 0.25rem;
    border-bottom: 1px solid #e9ecef;
}

.permissions-matrix-container .table tbody tr:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-check-input:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.form-check-label {
    cursor: pointer;
    font-size: 0.875rem;
}

.form-control-sm, .form-select-sm {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
}

.btn-sm {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.table-sm th, .table-sm td {
    padding: 0.5rem 0.25rem;
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}

.rotated-text {
    writing-mode: vertical-rl;
    text-orientation: mixed;
    transform: rotate(180deg);
    white-space: nowrap;
    display: inline-block;
    padding: 0.5rem 0.25rem;
    font-size: 0.75rem;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('selectAll');
    const clearAllBtn = document.getElementById('clearAll');
    
    // Get all permission checkboxes (including disabled ones for proper counting)
    const allPermissionCheckboxes = document.querySelectorAll('.permission-checkbox');
    const enabledPermissionCheckboxes = document.querySelectorAll('.permission-checkbox:not(:disabled)');
    
    // Select All functionality
    selectAllBtn.addEventListener('click', function() {
        enabledPermissionCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        console.log('Select All clicked - checked', enabledPermissionCheckboxes.length, 'checkboxes');
    });
    
    // Clear All functionality
    clearAllBtn.addEventListener('click', function() {
        enabledPermissionCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        console.log('Clear All clicked - unchecked', enabledPermissionCheckboxes.length, 'checkboxes');
    });
    
    // Add click event to labels for better UX
    document.querySelectorAll('.form-check-label').forEach(label => {
        label.addEventListener('click', function(e) {
            e.preventDefault();
            const checkbox = this.previousElementSibling;
            if (!checkbox.disabled) {
                checkbox.checked = !checkbox.checked;
            }
        });
    });
    
    // Add change event to all permission checkboxes
    allPermissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            console.log('Checkbox changed:', this.id, 'checked:', this.checked);
        });
    });
    
    // Debug: Log initial state
    console.log('Total permission checkboxes:', allPermissionCheckboxes.length);
    console.log('Enabled permission checkboxes:', enabledPermissionCheckboxes.length);
    console.log('Select All button:', selectAllBtn);
    console.log('Clear All button:', clearAllBtn);
});
</script>
@endsection
