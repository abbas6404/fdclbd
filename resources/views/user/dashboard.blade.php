@extends('user.layouts.master')

@section('title', 'Dashboard')

@section('content')
<!-- Page Heading -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 text-gray-800 font-weight-bold mb-0">Dashboard</h1>
        <p class="text-muted small">Welcome to your personalized control panel</p>
    </div>
    <ol class="breadcrumb bg-transparent p-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
</div>

@role('Admin|Super Admin')
<!-- Admin Access Card -->
<div class="card admin-card mb-4 border-0 overflow-hidden">
    <div class="card-body p-0">
        <div class="row g-0">
            <div class="col-md-8">
                <div class="admin-card-content p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="admin-icon-wrapper me-3">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1 fw-bold">Admin Access</h5>
                            <p class="text-white-50 mb-0">You have elevated privileges</p>
                        </div>
                    </div>
                    <p class="card-text mb-3">Access the admin panel to manage users, roles, permissions, and system settings. You have full administrative control over the application.</p>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light px-4 py-2">
                        <i class="fas fa-external-link-alt me-2"></i> Go to Admin Dashboard
                    </a>
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block">
                <div class="admin-card-decoration h-100">
                    <div class="admin-decoration-circle"></div>
                    <div class="admin-decoration-circle"></div>
                    <div class="admin-decoration-circle"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endrole

<!-- Welcome Card -->
<div class="card welcome-card mb-4 border-0 overflow-hidden">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="avatar-wrapper">
                    <div class="avatar-circle pulse">
                        <span class="initials">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="avatar-status bg-success"></div>
                </div>
            </div>
            <div class="col">
                <h2 class="h4 mb-1 fw-bold">Welcome back, {{ Auth::user()->name }}!</h2>
                <p class="text-muted mb-0">Here's what's happening with your account today.</p>
            </div>
            <div class="col-auto">
                <span class="badge bg-primary-soft px-3 py-2">
                    <i class="fas fa-user-check me-1"></i> Active Account
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-0 h-100 stat-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-primary fw-bold">Account Status</div>
                        <div class="h5 mb-0 font-weight-bold">Active</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
                <div class="progress-container">
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="small mt-2 text-end"><span class="fw-bold">100%</span> complete</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-0 h-100 stat-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-success fw-bold">Orders</div>
                        <div class="h5 mb-0 font-weight-bold">5</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="mt-3 small d-flex justify-content-between align-items-center">
                    <span class="text-success fw-bold">
                        <i class="fas fa-arrow-up me-1"></i> 12%
                    </span>
                    <span class="text-muted">Since last month</span>
                </div>
            </div>
            <a href="{{ route('orders') }}" class="card-link-overlay"></a>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-0 h-100 stat-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-info fw-bold">Profile Completion</div>
                        <div class="h5 mb-0 font-weight-bold">80%</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="progress-container">
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="small mt-2 d-flex justify-content-between">
                        <span class="text-muted">Complete your profile</span>
                        <span class="fw-bold">80%</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('profile') }}" class="card-link-overlay"></a>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-0 h-100 stat-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-warning fw-bold">Notifications</div>
                        <div class="h5 mb-0 font-weight-bold">3</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
                <div class="mt-3 small d-flex justify-content-between align-items-center">
                    <span class="text-warning fw-bold">
                        <i class="fas fa-exclamation-circle me-1"></i> 2 unread
                    </span>
                    <button class="btn btn-sm btn-link text-warning p-0">View all</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Roles & Permissions -->
    <div class="col-xl-6 mb-4">
        <div class="card border-0 h-100 role-card">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-shield-alt me-2 text-primary"></i>
                    Your Access Level
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="roleDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="roleDropdown">
                        <div class="dropdown-header">Actions:</div>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-eye fa-sm fa-fw me-2 text-gray-400"></i>
                            View Details
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-key fa-sm fa-fw me-2 text-gray-400"></i>
                            Request Access
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-question-circle fa-sm fa-fw me-2 text-gray-400"></i>
                            Help
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="fw-bold mb-3 text-uppercase small">
                        <i class="fas fa-user-tag me-2 text-primary"></i>Your Roles
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse(Auth::user()->roles as $role)
                            <span class="badge bg-primary-soft text-primary px-3 py-2">
                                <i class="fas fa-user-tag me-1"></i> {{ $role->name }}
                            </span>
                        @empty
                            <span class="badge bg-secondary px-3 py-2">No roles assigned</span>
                        @endforelse
                    </div>
                </div>
                
                <div>
                    <h6 class="fw-bold mb-3 text-uppercase small">
                        <i class="fas fa-key me-2 text-primary"></i>Your Permissions
                    </h6>
                    <div class="row g-3">
                        @forelse(Auth::user()->getAllPermissions() as $permission)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 rounded bg-light permission-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span class="small">{{ $permission->name }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    You don't have any specific permissions assigned.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-xl-6 mb-4">
        <div class="card border-0 h-100 activity-card">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent">
                <h6 class="m-0 fw-bold">
                    <i class="fas fa-history me-2 text-primary"></i>
                    Recent Activity
                </h6>
                <a href="#" class="btn btn-sm btn-primary px-3">
                    <i class="fas fa-list me-1"></i> View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon bg-primary">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold">Profile Updated</h6>
                                <span class="timeline-date"><i class="far fa-clock me-1"></i> 3 days ago</span>
                            </div>
                            <p class="text-muted small mb-0">You updated your profile information including your contact details and preferences.</p>
                            <div class="timeline-actions mt-2">
                                <a href="#" class="btn btn-sm btn-link p-0 text-primary me-3">View Details</a>
                                <a href="#" class="btn btn-sm btn-link p-0 text-muted">Dismiss</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon bg-success">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold">Order Placed</h6>
                                <span class="timeline-date"><i class="far fa-clock me-1"></i> 1 week ago</span>
                            </div>
                            <p class="text-muted small mb-0">You placed a new order (#12345) for $250.00. The order is currently being processed.</p>
                            <div class="timeline-actions mt-2">
                                <a href="#" class="btn btn-sm btn-link p-0 text-primary me-3">Track Order</a>
                                <a href="#" class="btn btn-sm btn-link p-0 text-muted">View Details</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon bg-info">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold">Account Created</h6>
                                <span class="timeline-date"><i class="far fa-clock me-1"></i> 2 weeks ago</span>
                            </div>
                            <p class="text-muted small mb-0">You created your account and completed the initial setup process.</p>
                            <div class="timeline-actions mt-2">
                                <a href="#" class="btn btn-sm btn-link p-0 text-primary me-3">View Details</a>
                                <a href="#" class="btn btn-sm btn-link p-0 text-muted">Dismiss</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Card Styles */
    .card {
        border-radius: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem 1.5rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Admin Card */
    .admin-card {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
    }
    
    .admin-card-content {
        position: relative;
        z-index: 1;
    }
    
    .admin-card-decoration {
        position: relative;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.1);
    }
    
    .admin-decoration-circle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
    }
    
    .admin-decoration-circle:nth-child(1) {
        width: 150px;
        height: 150px;
        top: -30px;
        right: -30px;
    }
    
    .admin-decoration-circle:nth-child(2) {
        width: 100px;
        height: 100px;
        bottom: 30px;
        right: 40px;
    }
    
    .admin-decoration-circle:nth-child(3) {
        width: 70px;
        height: 70px;
        bottom: -20px;
        right: -20px;
    }
    
    .admin-icon-wrapper {
        width: 50px;
        height: 50px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    /* Welcome Card */
    .welcome-card {
        background: linear-gradient(to right, #ffffff, #f8f9fc);
        border-left: 4px solid var(--primary-color);
    }
    
    .avatar-wrapper {
        position: relative;
    }
    
    .avatar-circle {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border-radius: 50%;
        color: white;
        font-size: 24px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }
    
    .avatar-status {
        position: absolute;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        border: 2px solid white;
        bottom: 0;
        right: 0;
    }
    
    .pulse {
        position: relative;
    }
    
    .pulse::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: rgba(78, 115, 223, 0.6);
        z-index: -1;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }
        70% {
            transform: scale(1.2);
            opacity: 0;
        }
        100% {
            transform: scale(1.2);
            opacity: 0;
        }
    }
    
    /* Stat Cards */
    .stat-card {
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        z-index: 1;
    }
    
    .stat-primary::before {
        background: linear-gradient(90deg, #4e73df, #224abe);
    }
    
    .stat-success::before {
        background: linear-gradient(90deg, #1cc88a, #13855c);
    }
    
    .stat-info::before {
        background: linear-gradient(90deg, #36b9cc, #258391);
    }
    
    .stat-warning::before {
        background: linear-gradient(90deg, #f6c23e, #dda20a);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }
    
    .stat-primary .stat-icon {
        background-color: rgba(78, 115, 223, 0.1);
        color: var(--primary-color);
    }
    
    .stat-success .stat-icon {
        background-color: rgba(28, 200, 138, 0.1);
        color: var(--success-color);
    }
    
    .stat-info .stat-icon {
        background-color: rgba(54, 185, 204, 0.1);
        color: var(--info-color);
    }
    
    .stat-warning .stat-icon {
        background-color: rgba(246, 194, 62, 0.1);
        color: var(--warning-color);
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.1);
    }
    
    .progress-container {
        margin-top: 1rem;
    }
    
    .progress-sm {
        height: 6px;
        border-radius: 3px;
        overflow: hidden;
    }
    
    /* Card Link Overlay */
    .card-link-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
    }
    
    /* Role Card */
    .role-card {
        border-left: 4px solid transparent;
        border-image: linear-gradient(to bottom, var(--primary-color), var(--info-color));
        border-image-slice: 1;
    }
    
    .permission-item {
        transition: all 0.2s ease;
    }
    
    .permission-item:hover {
        background-color: rgba(78, 115, 223, 0.05) !important;
        transform: translateX(3px);
    }
    
    /* Activity Card */
    .activity-card {
        border-left: 4px solid transparent;
        border-image: linear-gradient(to bottom, var(--primary-color), var(--success-color));
        border-image-slice: 1;
    }
    
    .timeline {
        position: relative;
        padding: 1.5rem;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 40px;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.8rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    
    .timeline-content {
        background-color: #f9fafc;
        border-radius: 0.75rem;
        padding: 1rem;
        transition: all 0.2s ease;
    }
    
    .timeline-content:hover {
        background-color: #f0f2fa;
        transform: translateX(3px);
    }
    
    .timeline-date {
        font-size: 0.75rem;
        color: var(--secondary-color);
    }
    
    .timeline-actions {
        display: flex;
        align-items: center;
    }
    
    /* Responsive */
    @media (max-width: 576px) {
        .avatar-circle {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }
</style>
@endpush 