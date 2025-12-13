@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">My Profile</h1>
            <p class="text-muted mb-0">Manage your account information and settings</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column - Profile Info & Edit Form -->
        <div class="col-lg-8">
            <!-- Profile Header Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="position-relative">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                         alt="Profile" 
                                         class="rounded-circle" 
                                         style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #e9ecef;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" 
                                         style="width: 120px; height: 120px; font-size: 3rem; border: 4px solid #e9ecef;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-white" 
                                      style="width: 20px; height: 20px;"></span>
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-1 fw-bold">{{ Auth::user()->name }}</h4>
                            <p class="text-muted mb-2">
                                <i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(Auth::user()->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user me-2 text-primary"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Full Name</label>
                            <p class="mb-0 fw-semibold">{{ Auth::user()->name ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Email Address</label>
                            <p class="mb-0 fw-semibold">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Phone Number</label>
                            <p class="mb-0 fw-semibold">{{ Auth::user()->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">User Code</label>
                            <p class="mb-0 fw-semibold">{{ Auth::user()->code ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small mb-1">Address</label>
                            <p class="mb-0 fw-semibold">{{ Auth::user()->address ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">City</label>
                            <p class="mb-0 fw-semibold">{{ Auth::user()->city ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">State</label>
                            <p class="mb-0 fw-semibold">{{ Auth::user()->state ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">ZIP Code</label>
                            <p class="mb-0 fw-semibold">{{ Auth::user()->zip ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Country</label>
                            <p class="mb-0 fw-semibold">{{ Auth::user()->country ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Member Since</label>
                            <p class="mb-0 fw-semibold">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Projects Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-building me-2 text-primary"></i>Related Projects
                    </h5>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-4">
                    <!-- Statistics -->
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="mb-0 text-primary">{{ $totalProjectsCreated ?? 0 }}</h3>
                                <small class="text-muted">Projects Created</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="mb-0 text-success">{{ $totalProjectsUpdated ?? 0 }}</h3>
                                <small class="text-muted">Projects Updated</small>
                            </div>
                        </div>
                    </div>

                    <!-- Projects List -->
                    @if(isset($createdProjects) && $createdProjects->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($createdProjects as $project)
                                <div class="list-group-item border-0 px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-2">
                                                <a href="{{ route('admin.projects.show', $project->id) }}" class="text-decoration-none">
                                                    {{ $project->project_name }}
                                                </a>
                                            </h6>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                <span class="badge bg-{{ $project->status === 'ongoing' ? 'primary' : ($project->status === 'completed' ? 'success' : 'secondary') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                                </span>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-home me-1"></i>{{ $project->flats_count }} Flats
                                                </span>
                                            </div>
                                            @if($project->address)
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($project->address, 50) }}
                                                </small>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $project->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-building text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-3">No projects found</p>
                            <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> Create Project
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-lg-4">
            <!-- Account Summary Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Account Summary
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted small mb-1">Roles</label>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach(Auth::user()->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted small mb-1">Permissions</label>
                        <p class="mb-0 fw-semibold">{{ Auth::user()->getAllPermissions()->count() }} permissions assigned</p>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted small mb-1">Last Login</label>
                        <p class="mb-0 fw-semibold">{{ Auth::user()->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-muted small mb-1">Member Since</label>
                        <p class="mb-0 fw-semibold">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Account Security Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-shield-alt me-2 text-primary"></i>Account Security
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-1">Password</h6>
                            <small class="text-muted">Last changed: {{ Auth::user()->updated_at->diffForHumans() }}</small>
                        </div>
                        <a href="{{ route('admin.profile.password') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i>Change
                        </a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Two-Factor Auth</h6>
                            <small class="text-muted">Not enabled</small>
                        </div>
                        <button class="btn btn-sm btn-secondary" disabled>
                            <i class="fas fa-plus me-1"></i>Setup
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-sm text-start">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-primary btn-sm text-start">
                            <i class="fas fa-building me-2"></i>Projects
                        </a>
                        <a href="{{ route('admin.admin-users.index') }}" class="btn btn-outline-primary btn-sm text-start">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary btn-sm text-start">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" 
                                   name="profile_photo" accept="image/*">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Max size: 2MB (JPEG, PNG, JPG, GIF)</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                   name="address" value="{{ old('address', Auth::user()->address) }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                   name="city" value="{{ old('city', Auth::user()->city) }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                   name="state" value="{{ old('state', Auth::user()->state) }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ZIP Code</label>
                            <input type="text" class="form-control @error('zip') is-invalid @enderror" 
                                   name="zip" value="{{ old('zip', Auth::user()->zip) }}">
                            @error('zip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                   name="country" value="{{ old('country', Auth::user()->country) }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

