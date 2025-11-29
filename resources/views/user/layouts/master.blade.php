<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'User Dashboard') - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
        
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #4e73df;
            --primary-dark: #3a5ccc;
            --secondary-color: #6b7280;
            --success-color: #10b981;
            --info-color: #3b82f6;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-color: #f9fafb;
            --dark-color: #1e293b;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --topbar-height: 70px;
        }
        
        body {
            background-color: #f0f2fa;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%234e73df' fill-opacity='0.05'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        #sidebar-wrapper {
            background-color: var(--dark-color);
            background-image: linear-gradient(195deg, #42424a 0%, #191919 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 35px 0 rgba(15, 23, 42, 0.1);
            height: 100vh;
            left: 0;
            overflow-y: auto;
            position: fixed;
            top: 0;
            transition: all 0.3s ease;
            width: var(--sidebar-width);
            z-index: 1040;
        }
        
        #wrapper.toggled #sidebar-wrapper {
            width: var(--sidebar-collapsed-width);
        }
        
        #wrapper.toggled .sidebar-heading span,
        #wrapper.toggled .list-group-item span {
            display: none;
        }
        
        .sidebar-heading {
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            display: flex;
            font-size: 1.2rem;
            font-weight: 700;
            height: var(--topbar-height);
            padding: 1.5rem;
            text-decoration: none;
        }
        
        .sidebar-heading .sidebar-brand-icon {
            background-color: var(--primary-color);
            border-radius: 10px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            height: 40px;
            margin-right: 1rem;
            width: 40px;
        }
        
        .sidebar-heading span {
            font-size: 1.2rem;
            font-weight: 700;
        }
        
        .list-group {
            padding: 1rem 0;
        }
        
        .list-group-item {
            background-color: transparent;
            border: none;
            border-radius: 0.5rem;
            color: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            font-weight: 500;
            margin: 0.25rem 1rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }
        
        .list-group-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .list-group-item.active {
            background-color: var(--primary-color);
            color: #fff;
        }
        
        .list-group-item i {
            font-size: 1.1rem;
            margin-right: 1rem;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            padding: 1rem;
        }
        
        /* Content Styles */
        #page-content-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            width: calc(100% - var(--sidebar-width));
        }
        
        #wrapper.toggled #page-content-wrapper {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }
        
        /* Navbar Styles */
        .topbar {
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            height: var(--topbar-height);
            padding: 0 1.5rem;
        }
        
        .menu-toggle {
            background-color: var(--primary-color);
            border: none;
            border-radius: 0.5rem;
            color: #fff;
            height: 40px;
            width: 40px;
            transition: all 0.2s ease;
        }
        
        .menu-toggle:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .navbar-search {
            position: relative;
            width: 300px;
        }
        
        .navbar-search .form-control {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 2rem;
            font-size: 0.85rem;
            height: 40px;
            padding-left: 2.5rem;
        }
        
        .navbar-search .search-icon {
            color: var(--secondary-color);
            left: 1rem;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .user-dropdown .dropdown-toggle {
            align-items: center;
            background-color: transparent;
            border: none;
            color: var(--dark-color);
            display: flex;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0;
        }
        
        .user-dropdown .dropdown-toggle::after {
            display: none;
        }
        
        .user-dropdown .user-avatar {
            align-items: center;
            background-color: var(--primary-color);
            border-radius: 50%;
            color: #fff;
            display: flex;
            font-size: 1rem;
            height: 40px;
            justify-content: center;
            margin-right: 0.75rem;
            width: 40px;
        }
        
        .user-dropdown .dropdown-menu {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            min-width: 12rem;
            padding: 0.5rem;
        }
        
        .user-dropdown .dropdown-item {
            border-radius: 0.5rem;
            color: var(--dark-color);
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.6rem 1rem;
        }
        
        .user-dropdown .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .user-dropdown .dropdown-item i {
            color: var(--secondary-color);
            margin-right: 0.75rem;
            width: 16px;
        }
        
        /* Content Container */
        .content-container {
            padding: 1.5rem;
        }
        
        /* Cards */
        .card {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: all 0.2s ease;
        }
        
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
        }
        
        .card-header h6 {
            color: var(--dark-color);
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: 0.75rem;
        }
        
        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: var(--success-color);
        }
        
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            #sidebar-wrapper {
                margin-left: -var(--sidebar-width);
            }
            
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
                width: var(--sidebar-width);
            }
            
            #wrapper.toggled .sidebar-heading span,
            #wrapper.toggled .list-group-item span {
                display: inline;
            }
            
            #page-content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            #wrapper.toggled #page-content-wrapper {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <a href="{{ route('dashboard') }}" class="sidebar-heading">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <span>{{ config('app.name') }}</span>
            </a>
            
            <div class="list-group">
                <a href="{{ route('dashboard') }}" class="list-group-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('profile') }}" class="list-group-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
           
                
                @role('Admin|Super Admin')
                <a href="{{ route('admin.dashboard') }}" class="list-group-item">
                    <i class="fas fa-user-cog"></i>
                    <span>Admin Panel</span>
                </a>
                @endrole
            </div>
            
            <div class="sidebar-footer">
                <a href="#" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();" class="list-group-item text-danger">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
                <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Topbar -->
            <nav class="navbar navbar-expand topbar sticky-top">
                <div class="container-fluid">
                    <button class="menu-toggle" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Search -->
                    <div class="navbar-search d-none d-md-block">
                        <input type="text" class="form-control" placeholder="Search...">
                        <i class="fas fa-search search-icon"></i>
                    </div>

                    <!-- Navbar Right -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Notifications Dropdown -->
                        <li class="nav-item dropdown mx-2">
                            <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    3
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationsDropdown" style="width: 300px;">
                                <h6 class="dropdown-header">Notifications Center</h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-muted">December 12, 2023</div>
                                        <span>A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="me-3">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-donate"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-muted">December 7, 2023</div>
                                        <span>Your order #2049 has been shipped!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="me-3">
                                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-muted">December 2, 2023</div>
                                        <span>Please update your billing information.</span>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-muted" href="#">Show All Notifications</a>
                            </div>
                        </li>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown user-dropdown">
                            <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="d-none d-lg-inline">
                                    {{ Auth::user()->name }}
                                </span>
                            </a>
                            <ul class="dropdown-menu shadow dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="fas fa-user"></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cogs"></i> Settings
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-list"></i> Activity Log
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End of Topbar -->

            <!-- Content Container -->
            <div class="content-container">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
            <!-- End of Content Container -->
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            const menuToggle = document.getElementById('menu-toggle');
            const wrapper = document.getElementById('wrapper');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    wrapper.classList.toggle('toggled');
                });
            }
            
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
    </script>
    @stack('scripts')
</body>
</html> 