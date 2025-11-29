@extends('admin.setup.setup-layout')

@section('page-title', 'Role Details')
@section('page-description', 'View role information and assigned permissions')

@section('setup-content')
<div class="card shadow">
    <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-eye me-2"></i>Role: {{ $role->name }}
        </h6>
        <div>
            @if($role->name !== 'Super Admin' || $showSuperAdmin)
                <a href="{{ route('admin.setup.role.edit', $role) }}" class="btn btn-outline-primary btn-sm me-2">
                    <i class="fas fa-edit me-1"></i>Edit Role
                </a>
            @endif
            <a href="{{ route('admin.setup.role.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>
    
    <div class="card-body p-3">
        <!-- Basic Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Role Name</label>
                    <div class="form-control-plaintext fw-medium">{{ $role->name }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Guard</label>
                    <div class="form-control-plaintext fw-medium">{{ $role->guard_name }}</div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Created By</label>
                    <div class="form-control-plaintext fw-medium">
                        {{ $role->created_by ?? 'System' }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Last Updated By</label>
                    <div class="form-control-plaintext fw-medium">
                        {{ $role->updated_by ?? 'System' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Count -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Users with this Role</label>
                    <div class="form-control-plaintext fw-medium">
                        <span class="badge bg-info fs-6">{{ $role->users_count ?? $role->users->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Total Permissions</label>
                    <div class="form-control-plaintext fw-medium">
                        <span class="badge bg-success fs-6">{{ $role->permissions->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Section -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-bold mb-0 small">Assigned Permissions Matrix</label>
                <div class="text-muted small">
                    {{ $role->permissions->count() }} permission(s) assigned
                </div>
            </div>
            
            @if($role->permissions->count() > 0)
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
                                
                                // Group permissions by group name and type
                                $rolePermissionGroups = $role->permissions->groupBy(function ($permission) {
                                    return $permission->permissionGroup ? $permission->permissionGroup->name : 'general';
                                });
                                
                                foreach($rolePermissionGroups as $group => $permissions) {
                                    $permissionMatrix[$group] = [];
                                    foreach($permissions as $permission) {
                                        $permissionMatrix[$group][$permission->type] = $permission;
                                    }
                                }
                            @endphp
                            
                            @foreach($allGroups as $group)
                                @php $groupName = $group->name; @endphp
                                <tr class="align-middle">
                                    <td class="text-center fw-bold small align-middle p-0" style="border-right: 1px solid rgb(148, 180, 212);">
                                        <span>{{ $group->sort_order }}</span>
                                    </td>
                                    <td class="fw-medium align-middle p-0">
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
                                                           checked
                                                           disabled>
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
            @else
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-warning fa-2x mb-2"></i>
                    <p class="text-muted mb-0">No permissions assigned to this role</p>
                </div>
            @endif
        </div>

        <!-- Users List (if any) -->
        @if($role->users->count() > 0)
            <div class="mb-3">
                <label class="form-label fw-bold mb-0 small">Users with this Role</label>
                <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                    <div class="row g-1">
                        @foreach($role->users as $user)
                            <div class="col-md-4 col-lg-3">
                                <div class="user-item-display">
                                    <span class="badge bg-info me-1">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <span class="small">{{ $user->name ?? $user->email }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="text-center mt-4">
            @if($role->name !== 'Super Admin' || $showSuperAdmin)
                <a href="{{ route('admin.setup.role.edit', $role) }}" class="btn btn-primary btn-sm px-4 me-2">
                    <i class="fas fa-edit me-2"></i>Edit Role
                </a>
            @endif
            <a href="{{ route('admin.setup.role.index') }}" class="btn btn-outline-secondary btn-sm px-4">
                <i class="fas fa-list me-2"></i>Back to Roles
            </a>
        </div>
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

.user-item-display {
    padding: 0.5rem;
    border-radius: 4px;
    background-color: #e3f2fd;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
}

.form-control-plaintext {
    padding: 0.375rem 0;
    background-color: transparent;
    border: none;
    font-size: 0.875rem;
}

.badge.fs-6 {
    font-size: 0.875rem !important;
    padding: 0.5rem 0.75rem;
}
</style>
@endsection
