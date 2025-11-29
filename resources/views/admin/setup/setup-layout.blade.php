@extends('admin.layouts.app')

@section('title', 'Setup')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">@yield('page-title', 'Setup')</h1>
            <p class="text-muted mb-0">@yield('page-description', 'System configuration and management')</p>
        </div>
    </div>

    <div class="row">
        <!-- Setup Sidebar -->
        <div class="col-lg-2">  
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>Setup Menu
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @can('setup.role')
                        <a href="{{ route('admin.setup.role.index') }}"
                           class="list-group-item list-group-item-action {{ request()->routeIs('admin.setup.role.*') ? 'active' : '' }}">
                            <i class="fas fa-user-shield me-2"></i>Role Setup
                        </a>
                        @endcan

                        @can('setup.display')
                        <a href="{{ route('admin.setup.display.index') }}"
                           class="list-group-item list-group-item-action {{ request()->routeIs('admin.setup.display.*') ? 'active' : '' }}">
                            <i class="fas fa-eye me-2"></i>Display Settings
                        </a>
                        @endcan
                        
                        @can('setup.chart-of-accounts')
                        <a href="{{ route('admin.setup.head-of-accounts.index') }}" 
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.setup.head-of-accounts.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-line me-2"></i>Head of Accounts
                        </a>
                        @endcan
                        
                        @can('setup.treasury-accounts')
                        <a href="{{ route('admin.setup.treasury-accounts.index') }}" 
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.setup.treasury-accounts.*') ? 'active' : '' }}">
                                <i class="fas fa-wallet me-2"></i>Treasury Accounts
                        </a>
                        @endcan
                        
                        @can('setup.system-settings')
                        <a href="{{ route('admin.setup.system-settings.index') }}" 
                            class="list-group-item list-group-item-action {{ request()->routeIs('admin.setup.system-settings.*') ? 'active' : '' }}">
                                <i class="fas fa-cog me-2"></i>System Settings
                        </a>
                        @endcan
                      
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-10">
            @yield('setup-content')
        </div>
    </div>
</div>
@endsection 