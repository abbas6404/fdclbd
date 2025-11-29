@extends('admin.layouts.app')

@section('title', $filterLabel . ' Admin Users')

@section('content')
<div class="container-fluid">
    <div class="card shadow border-0">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-user-shield me-2"></i> All Admin Users
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.admin-users.index', ['filter' => 'active']) }}" 
                           class="btn btn-sm {{ $filter === 'active' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-check-circle me-1"></i> Active
                        </a>
                        <a href="{{ route('admin.admin-users.index', ['filter' => 'archived']) }}" 
                           class="btn btn-sm {{ $filter === 'archived' ? 'btn-warning' : 'btn-outline-warning' }}">
                            <i class="fas fa-archive me-1"></i> Archived
                        </a>
                    </div>
                    @if($filter === 'active')
                    <a href="{{ route('admin.admin-users.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Admin
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Filters -->
            <div class="row mb-3 g-2">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control form-control-sm" 
                               placeholder="Search admin users by name, email, phone, or code..." 
                               id="searchInput"
                               onkeyup="filterTable()">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
        // Helper function to generate sort URLs
        function sortUrl($column, $currentColumn, $currentDirection) {
            $params = request()->except('sort', 'direction');
            $params['sort'] = $column;
            $params['direction'] = ($currentColumn === $column && $currentDirection === 'asc') ? 'desc' : 'asc';
            return request()->fullUrlWithQuery($params);
        }

        // Helper function to get sort icon
        function sortIcon($column, $currentColumn, $currentDirection) {
            if ($currentColumn !== $column) {
                return '<i class="fas fa-sort ms-1"></i>';
            }
            return $currentDirection === 'asc' ? 
                '<i class="fas fa-sort-up ms-1"></i>' : 
                '<i class="fas fa-sort-down ms-1"></i>';
        }
        @endphp

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover table-sm align-middle" id="adminUsersTable">
                <thead class="table-light">
                    <tr>
                        <th>
                            <a href="{{ sortUrl('code', $sortColumn ?? 'code', $sortDirection ?? 'asc') }}" 
                               class="text-decoration-none">
                                Code {!! sortIcon('code', $sortColumn ?? 'code', $sortDirection ?? 'asc') !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ sortUrl('name', $sortColumn, $sortDirection) }}" 
                               class="text-decoration-none">
                                Name {!! sortIcon('name', $sortColumn, $sortDirection) !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ sortUrl('email', $sortColumn, $sortDirection) }}" 
                               class="text-decoration-none">
                                Email {!! sortIcon('email', $sortColumn, $sortDirection) !!}
                            </a>
                        </th>
                        <th>
                            <a href="{{ sortUrl('phone', $sortColumn, $sortDirection) }}" 
                               class="text-decoration-none">
                                Phone {!! sortIcon('phone', $sortColumn, $sortDirection) !!}
                            </a>
                        </th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $admin)
                        <tr class="{{ $admin->trashed() ? 'table-secondary' : '' }}">
                            <td><span class="badge bg-primary">{{ $admin->code }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($admin->profile_photo_path)
                                        <img src="{{ Storage::url($admin->profile_photo_path) }}" 
                                             alt="{{ $admin->name }}" 
                                             class="rounded-circle me-2" 
                                             style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="avatar bg-info text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span>{{ $admin->name }}</span>
                                    @if($admin->trashed())
                                        <span class="badge bg-secondary small ms-2">Archived</span>
                                    @endif
                                    @if($admin->hasRole('Super Admin'))
                                        <span class="badge bg-danger ms-2">Super Admin</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->phone ?: 'N/A' }}</td>
                            <td>
                                @if($admin->roles && $admin->roles->count() > 0)
                                    @foreach($admin->roles as $role)
                                        <span class="badge bg-secondary me-1">{{ $role->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No roles assigned</span>
                                @endif
                            </td>
                            <td>
                                @if($admin->trashed())
                                    <span class="badge bg-warning">Archived</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @if($filter === 'active')
                                        @if($admin->hasRole('Super Admin') && !$showSuperAdmin)
                                            {{-- Super Admin is hidden, no edit/delete --}}
                                        @else
                                            <a href="{{ route('admin.admin-users.edit', $admin) }}" 
                                               class="btn btn-outline-primary" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$admin->hasRole('Super Admin'))
                                                <button type="button" 
                                                        class="btn btn-outline-warning" 
                                                        title="Archive"
                                                        onclick="confirmArchive({{ $admin->id }}, '{{ $admin->name }}')">
                                                    <i class="fas fa-archive"></i>
                                                </button>
                                            @endif
                                        @endif
                                    @else
                                        <button type="button" 
                                                class="btn btn-outline-success" 
                                                title="Restore"
                                                onclick="confirmRestore({{ $admin->id }}, '{{ $admin->name }}')">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        @if(!$admin->hasRole('Super Admin'))
                                            <button type="button" 
                                                    class="btn btn-outline-danger" 
                                                    title="Permanently Delete"
                                                    onclick="confirmPermanentDelete({{ $admin->id }}, '{{ $admin->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-user-shield fa-3x mb-3"></i>
                                <p>No {{ strtolower($filterLabel) }} admin users found.</p>
                                @if($filter === 'active')
                                    <a href="{{ route('admin.admin-users.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Add First Admin
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

            <!-- Pagination -->
            @if($admins->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <div class="text-muted small">
                    Showing <strong>{{ $admins->firstItem() ?? 0 }}</strong> to <strong>{{ $admins->lastItem() ?? 0 }}</strong> of <strong>{{ $admins->total() }}</strong> results
                </div>
                <div>
                    {{ $admins->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Archive Confirmation Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Archive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to archive <strong id="archiveAdminName"></strong>? You can restore it later if needed.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="archiveForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-warning">Archive</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Restore</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to restore <strong id="restoreAdminName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="restoreForm" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Restore</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Permanent Delete Confirmation Modal -->
    <div class="modal fade" id="permanentDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Permanent Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning!</strong> This action cannot be undone. This will permanently delete <strong id="deleteAdminName"></strong> and all associated data.
                    </div>
                    Are you absolutely sure you want to permanently delete this admin?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="permanentDeleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Permanently Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('adminUsersTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName('td');
            let found = false;
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            tr[i].style.display = found ? '' : 'none';
        }
    }

    function confirmArchive(adminId, adminName) {
        document.getElementById('archiveAdminName').textContent = adminName;
        document.getElementById('archiveForm').action = '{{ route("admin.admin-users.destroy", ":id") }}'.replace(':id', adminId);
        const modal = new bootstrap.Modal(document.getElementById('archiveModal'));
        modal.show();
    }

    function confirmRestore(adminId, adminName) {
        document.getElementById('restoreAdminName').textContent = adminName;
        document.getElementById('restoreForm').action = '{{ route("admin.admin-users.restore", ":id") }}'.replace(':id', adminId);
        const modal = new bootstrap.Modal(document.getElementById('restoreModal'));
        modal.show();
    }

    function confirmPermanentDelete(adminId, adminName) {
        document.getElementById('deleteAdminName').textContent = adminName;
        document.getElementById('permanentDeleteForm').action = '{{ route("admin.admin-users.force-delete", ":id") }}'.replace(':id', adminId);
        const modal = new bootstrap.Modal(document.getElementById('permanentDeleteModal'));
        modal.show();
    }
</script>

<style>
.avatar {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: bold;
}
</style>
@endsection