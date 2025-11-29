@extends('admin.layouts.app')

@section('title', 'Admin Dashboard - Formonic Design & Construction Ltd')

@section('content')
@role('Super Admin|Admin')
<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
        <div class="d-flex">
            <span class="badge bg-danger fs-6">
                <i class="fas fa-crown me-2"></i>
                {{ Auth::user()->roles->first()->name }}
            </span>
        </div>
    </div>

    <!-- Treasury Account Balance Cards Row -->
    @if(isset($treasuryAccounts) && $treasuryAccounts->count() > 0)
    <div class="row mb-4">
        @foreach($treasuryAccounts as $account)
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-teal shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-teal text-uppercase mb-1">
                                {{ $account->account_name }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $currencySymbol ?? 'tk' }} {{ number_format($account->current_balance, 0, '.', ',') }}
                            </div>
                            <small class="text-muted">
                                @if($account->account_type == 'bank')
                                    <i class="fas fa-university me-1"></i>{{ $account->bank_name ?? 'Bank' }}
                                    @if($account->account_number)
                                        {{ $account->account_number }}
                                    @endif
                                @else
                                    <i class="fas fa-money-bill-wave me-1"></i>Cash Account
                                @endif
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-{{ $account->account_type == 'bank' ? 'university' : 'wallet' }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Stats Cards Row -->
    <div class="row mb-4">
        <!-- Total Users Card -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['users'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Projects Card -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Projects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['projects'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Flats/Units Card -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Flats/Units</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['flats'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-home fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers Card -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['customers'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Invoices Card -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Invoices</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['invoices'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sales Agents Card -->
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Sales Agents</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['sales_agents'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Weekly Stats Row -->
    <div class="row mb-4">
        <!-- New Users This Week Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                New Users (7 days)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lastWeekUsers ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Projects This Week Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                New Projects (7 days)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lastWeekProjects ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Invoices This Week Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                New Invoices (7 days)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lastWeekInvoices ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Customers This Week Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                New Customers (7 days)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lastWeekCustomers ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Projects -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Projects</h6>
                    @if(Route::has('admin.projects.index'))
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-primary">View All</a>
                    @endif
                </div>
                <div class="card-body">
                    @if(isset($recentProjects) && $recentProjects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentProjects as $project)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $project->project_name ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $project->address ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $project->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($project->status ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td>{{ $project->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No recent projects.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Customers -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Customers</h6>
                    @if(Route::has('admin.customers.index'))
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-primary">View All</a>
                    @endif
                </div>
                <div class="card-body">
                    @if(isset($recentCustomers) && $recentCustomers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Customer ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Registered</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCustomers as $customer)
                                    <tr>
                                        <td>
                                            <span class="badge bg-success">{{ $customer->customer_id ?? $customer->id }}</span>
                                        </td>
                                        <td>{{ $customer->name ?? 'N/A' }}</td>
                                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                                        <td>{{ $customer->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No recent customers.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-crown fa-3x text-warning mb-3"></i>
                    <h4 class="text-warning">Admin Access Required</h4>
                    <p class="text-muted">This dashboard is only accessible to Super Admin and Admin users.</p>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Go to Regular Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endrole
@endsection

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}
.border-left-dark {
    border-left: 0.25rem solid #5a5c69 !important;
}
.border-left-teal {
    border-left: 0.25rem solid #20c997 !important;
}
.text-teal {
    color: #20c997 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
</style>
@endpush
