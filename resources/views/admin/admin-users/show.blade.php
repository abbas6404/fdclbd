@extends('admin.layouts.app')

@section('title', 'Admin Details')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Admin Details</h1>
            <p class="text-muted mb-0">View admin user information</p>
        </div>
    </div>
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.admin-users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Back to Admins
    </a>
    <div>
        <a href="{{ route('admin.admin-users.edit', $adminRecord) }}" class="btn btn-primary me-2">
            <i class="fas fa-edit me-1"></i> Edit Admin
        </a>
        <form action="{{ route('admin.admin-users.destroy', $adminRecord) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to archive this admin?')">
                <i class="fas fa-archive me-1"></i> Archive Admin
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-shield me-2"></i> Admin Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Admin Code</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary">{{ $adminRecord->code }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <p class="form-control-plaintext">{{ $adminRecord->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-envelope me-1 text-info"></i>
                                {{ $adminRecord->email }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <p class="form-control-plaintext">
                                @if($adminRecord->phone)
                                    <i class="fas fa-phone me-1 text-success"></i>
                                    {{ $adminRecord->phone }}
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p class="form-control-plaintext">
                                @if($adminRecord->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($adminRecord->status === 'inactive')
                                    <span class="badge bg-secondary">Inactive</span>
                                @else
                                    <span class="badge bg-warning">Suspended</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Verified</label>
                            <p class="form-control-plaintext">
                                @if($adminRecord->email_verified_at)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation-circle me-1"></i>Not Verified
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Created At</label>
                            <p class="form-control-plaintext">{{ $adminRecord->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Updated</label>
                            <p class="form-control-plaintext">{{ $adminRecord->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i> Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.admin-users.edit', $adminRecord) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit Admin
                    </a>
                    <button class="btn btn-info">
                        <i class="fas fa-key me-1"></i> Reset Password
                    </button>
                    <button class="btn btn-success">
                        <i class="fas fa-shield-alt me-1"></i> Manage Permissions
                    </button>
                    <button class="btn btn-warning">
                        <i class="fas fa-history me-1"></i> View Activity
                    </button>
                </div>
            </div>
        </div>

        <!-- Profile Photo Card -->
        <div class="card shadow mt-3">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-camera me-2"></i> Profile Photo
                </h6>
            </div>
            <div class="card-body text-center">
                @if($adminRecord->profile_photo_path)
                    <img src="{{ Storage::url($adminRecord->profile_photo_path) }}" 
                         alt="{{ $adminRecord->name }}" 
                         class="img-fluid rounded" 
                         style="max-width: 200px; max-height: 200px; object-fit: cover;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 200px; height: 200px;">
                        <i class="fas fa-user fa-4x text-muted"></i>
                    </div>
                @endif
                <p class="mt-2 mb-0 text-muted small">
                    @if($adminRecord->profile_photo_path)
                        Profile photo uploaded
                    @else
                        No profile photo
                    @endif
                </p>
            </div>
        </div>

        <!-- Roles Card -->
        <div class="card shadow mt-3">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-tag me-2"></i> Assigned Roles
                </h6>
            </div>
            <div class="card-body">
                @if($adminRecord->roles && $adminRecord->roles->count() > 0)
                    @foreach($adminRecord->roles as $role)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary">{{ $role->name }}</span>
                            <small class="text-muted">{{ $role->permissions_count ?? 0 }} permissions</small>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center mb-0">No roles assigned</p>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection
