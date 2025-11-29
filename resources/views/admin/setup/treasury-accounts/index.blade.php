@extends('admin.setup.setup-layout')

@section('page-title', 'Treasury Accounts')
@section('page-description', 'Manage treasury accounts (Cash & Bank)')

@section('setup-content')
<div class="card shadow">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-wallet me-2"></i>Treasury Accounts
        </h6>
        @can('setup.treasury-accounts.create')
        <a href="{{ route('admin.setup.treasury-accounts.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Add New
        </a>
        @endcan
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="h3 mb-0 text-primary">{{ $stats['total_accounts'] }}</div>
                            <div class="text-muted small">Total Accounts</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="h3 mb-0 text-success">{{ $stats['cash_accounts'] }}</div>
                            <div class="text-muted small">Cash Accounts</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="h3 mb-0 text-info">{{ $stats['bank_accounts'] }}</div>
                            <div class="text-muted small">Bank Accounts</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="h3 mb-0 text-warning">{{ number_format($stats['total_balance'] / 100, 0) }}</div>
                            <div class="text-muted small">Total Balance</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Treasury Accounts List -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Account Name</th>
                        <th>Type</th>
                        <th>Bank Name</th>
                        <th>Account Number</th>
                        <th>Current Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($treasuryAccounts as $account)
                        <tr>
                            <td>
                                <strong>{{ $account->account_name }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $account->account_type === 'cash' ? 'success' : 'primary' }}">
                                    {{ ucfirst($account->account_type) }}
                                </span>
                            </td>
                            <td>{{ $account->bank_name ?? '-' }}</td>
                            <td>{{ $account->account_number ?? '-' }}</td>
                            <td>
                                <strong class="text-{{ $account->current_balance >= 0 ? 'success' : 'danger' }}">
                                    {{ number_format($account->current_balance / 100, 0) }}
                                </strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $account->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($account->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <h5>No treasury accounts found</h5>
                                    <p>Treasury accounts will be displayed here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

