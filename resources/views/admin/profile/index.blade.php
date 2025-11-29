@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="profile-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h2 mb-1 text-white fw-bold">My Profile</h1>
                <p class="text-white-50 mb-0">View your account information and settings</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8">
            <!-- Personal Information Card -->
            <div class="profile-card mb-4">
                <div class="card-header-section">
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Personal Information</h5>
                            <small class="text-muted">Your basic account details</small>
                        </div>
                    </div>
                </div>
                <div class="card-body-section">
                    <div class="row">
                        <!-- Profile Photo -->
                        <div class="col-md-3 text-center mb-4">
                            <div class="profile-photo-wrapper">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                         alt="Profile Photo" 
                                         class="profile-photo">
                                @else
                                    <div class="profile-photo-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div class="profile-status">
                                    <span class="status-dot active"></span>
                                    <small>Active</small>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Details -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-user-circle text-primary me-2"></i>
                                            Full Name
                                        </div>
                                        <div class="info-value">
                                            {{ Auth::user()->name ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-envelope text-success me-2"></i>
                                            Email Address
                                        </div>
                                        <div class="info-value">
                                            {{ Auth::user()->email }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-phone text-info me-2"></i>
                                            Phone Number
                                        </div>
                                        <div class="info-value">
                                            {{ Auth::user()->phone ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-map-marker-alt text-warning me-2"></i>
                                            Address
                                        </div>
                                        <div class="info-value">
                                            {{ Auth::user()->address ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-city text-secondary me-2"></i>
                                            City
                                        </div>
                                        <div class="info-value">
                                            {{ Auth::user()->city ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-map text-danger me-2"></i>
                                            State
                                        </div>
                                        <div class="info-value">
                                            {{ Auth::user()->state ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-mail-bulk text-primary me-2"></i>
                                            ZIP Code
                                        </div>
                                        <div class="info-value">
                                            {{ Auth::user()->zip ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-globe text-success me-2"></i>
                                            Country
                                        </div>
                                        <div class="info-value">
                                            {{ Auth::user()->country ?? 'Not provided' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-calendar-alt text-info me-2"></i>
                                            Member Since
                                        </div>
                                        <div class="info-value">
                                            {{ Auth::user()->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Security Card -->
            <div class="profile-card mb-4">
                <div class="card-header-section">
                    <div class="d-flex align-items-center">
                        <div class="header-icon security">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Account Security</h5>
                            <small class="text-muted">Manage your account security settings</small>
                        </div>
                    </div>
                </div>
                <div class="card-body-section">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="security-item">
                                <div class="security-icon">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="security-content">
                                    <h6 class="mb-1">Password</h6>
                                    <small class="text-muted">Last changed: {{ Auth::user()->updated_at->diffForHumans() }}</small>
                                </div>
                                <div class="security-action">
                                    <a href="{{ route('admin.profile.password') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i> Change
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="security-item">
                                <div class="security-icon disabled">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div class="security-content">
                                    <h6 class="mb-1">Two-Factor Authentication</h6>
                                    <small class="text-muted">Add an extra layer of security</small>
                                </div>
                                <div class="security-action">
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="fas fa-plus me-1"></i> Setup
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="security-item">
                                <div class="security-icon">
                                    <i class="fas fa-desktop"></i>
                                </div>
                                <div class="security-content">
                                    <h6 class="mb-1">Login Sessions</h6>
                                    <small class="text-muted">Manage active sessions</small>
                                </div>
                                <div class="security-action">
                                    <button class="btn btn-info btn-sm">
                                        <i class="fas fa-eye me-1"></i> View
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="security-item">
                                <div class="security-icon">
                                    <i class="fas fa-life-ring"></i>
                                </div>
                                <div class="security-content">
                                    <h6 class="mb-1">Account Recovery</h6>
                                    <small class="text-muted">Set up recovery options</small>
                                </div>
                                <div class="security-action">
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fas fa-cog me-1"></i> Setup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Account Summary Card -->
            <div class="profile-card mb-4">
                <div class="card-header-section">
                    <div class="d-flex align-items-center">
                        <div class="header-icon summary">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Account Summary</h5>
                            <small class="text-muted">Your account overview</small>
                        </div>
                    </div>
                </div>
                <div class="card-body-section">
                    <div class="summary-item">
                        <div class="summary-icon">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <div class="summary-content">
                            <h6 class="mb-1">Roles</h6>
                            <div class="role-badges">
                                @foreach(Auth::user()->roles as $role)
                                    <span class="role-badge">{{ $role->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="summary-content">
                            <h6 class="mb-1">Permissions</h6>
                            <small class="text-muted">{{ Auth::user()->getAllPermissions()->count() }} permissions assigned</small>
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="summary-content">
                            <h6 class="mb-1">Last Login</h6>
                            <small class="text-muted">{{ Auth::user()->updated_at->format('M d, Y H:i') }}</small>
                        </div>
                    </div>

                    <div class="summary-item">
                        <div class="summary-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="summary-content">
                            <h6 class="mb-1">Member Since</h6>
                            <small class="text-muted">{{ Auth::user()->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="profile-card mb-4">
                <div class="card-header-section">
                    <div class="d-flex align-items-center">
                        <div class="header-icon actions">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Quick Actions</h5>
                            <small class="text-muted">Navigate to important sections</small>
                        </div>
                    </div>
                </div>
                <div class="card-body-section">
                    <div class="action-grid">
                        <a href="{{ route('admin.dashboard') }}" class="action-item">
                            <div class="action-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="action-item">
                            <div class="action-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <span>Users</span>
                        </a>
                        <a href="{{ route('admin.setup.role.index') }}" class="action-item">
                            <div class="action-icon">
                                <i class="fas fa-user-tag"></i>
                            </div>
                            <span>Role Setup</span>
                        </a>
                        <a href="{{ route('admin.reports') }}" class="action-item">
                            <div class="action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <span>Reports</span>
                        </a>
                        <a href="{{ route('admin.profile.password') }}" class="action-item">
                            <div class="action-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <span>Password</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Information Card -->
            <div class="profile-card mb-4">
                <div class="card-header-section">
                    <div class="d-flex align-items-center">
                        <div class="header-icon system">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">System Info</h5>
                            <small class="text-muted">Technical information</small>
                        </div>
                    </div>
                </div>
                <div class="card-body-section">
                    <div class="system-item">
                        <div class="system-label">PHP Version</div>
                        <div class="system-value">{{ phpversion() }}</div>
                    </div>
                    <div class="system-item">
                        <div class="system-label">Laravel Version</div>
                        <div class="system-value">{{ app()->version() }}</div>
                    </div>
                    <div class="system-item">
                        <div class="system-label">Database</div>
                        <div class="system-value">{{ config('database.default') }}</div>
                    </div>
                    <div class="system-item">
                        <div class="system-label">Environment</div>
                        <div class="system-value">{{ config('app.env') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles moved to admin-layout.css -->
@endsection 