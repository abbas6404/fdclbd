@extends('admin.setup.setup-layout')

@section('page-title', 'Role Management')
@section('page-description', 'Manage user roles and permissions')

@section('setup-content')
<div class="card shadow">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-user-shield me-2"></i>Roles
        </h6>
        
        <div class="d-flex align-items-center gap-2">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="showActive">
                    <i class="fas fa-eye me-1"></i>Active
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="showArchived">
                    <i class="fas fa-archive me-1"></i>Archived
                </button>
            </div>
            <a href="{{ route('admin.setup.role.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Add Role
            </a>
        </div>
    </div>
    <div class="card-body">

        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Users Count</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr class="{{ $showArchived ? 'table-secondary' : '' }}">
                            <td>
                                <strong>{{ $role->name }}</strong>
                                @if($role->name === 'Super Admin')
                                    <span class="badge bg-danger ms-2">Super Admin</span>
                                @elseif($role->name === 'Admin')
                                    <span class="badge bg-warning ms-2">Admin</span>
                                @endif
                                @if($showArchived)
                                    <span class="badge bg-secondary ms-2">Archived</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $role->permissions_count ?? 0 }} permissions</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $role->users_count ?? 0 }} users</span>
                            </td>
                            <td>
                                {{ $role->created_at ? $role->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.setup.role.show', $role) }}" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($showArchived)
                                        <!-- Archived role actions -->
                                        @if($role->name !== 'Super Admin')
                                            <form action="{{ route('admin.setup.role.restore', $role->id) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to restore this role?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.setup.role.force-delete', $role->id) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to permanently delete this role? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Permanently Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <!-- Active role actions -->
                                        @if($role->name !== 'Super Admin' || $showSuperAdmin)
                                            <a href="{{ route('admin.setup.role.edit', $role) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            @if($role->name !== 'Admin' && $role->name !== 'Super Admin')
                                                <form action="{{ route('admin.setup.role.destroy', $role) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this role? This will affect all users with this role.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <h5>No roles found</h5>
                                    <p>Get started by creating your first role.</p>
                                    <a href="{{ route('admin.setup.role.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>Create First Role
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($roles->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showActiveBtn = document.getElementById('showActive');
    const showArchivedBtn = document.getElementById('showArchived');
    
    // Set initial button states
    if ({{ $showArchived ? 'true' : 'false' }}) {
        showArchivedBtn.classList.remove('btn-outline-secondary');
        showArchivedBtn.classList.add('btn-secondary');
    } else {
        showActiveBtn.classList.remove('btn-outline-secondary');
        showActiveBtn.classList.add('btn-secondary');
    }
    
    // Handle button clicks
    showActiveBtn.addEventListener('click', function() {
        window.location.href = '{{ route("admin.setup.role.index") }}';
    });
    
    showArchivedBtn.addEventListener('click', function() {
        window.location.href = '{{ route("admin.setup.role.index", ["show_archived" => true]) }}';
    });
});
</script>
@endsection
