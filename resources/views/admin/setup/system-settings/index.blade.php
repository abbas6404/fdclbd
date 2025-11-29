@extends('admin.setup.setup-layout')

@section('page-title', 'System Settings')
@section('page-description', 'View and manage all system settings')

@section('setup-content')
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-cog me-2"></i>System Settings
        </h6>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @forelse($systemSettings as $group => $settings)
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-folder me-2"></i>{{ ucfirst($group) }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Setting Key</th>
                                    <th>Value</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($settings as $setting)
                                    <tr>
                                        <td>
                                            <code class="text-primary">{{ $setting->key }}</code>
                                        </td>
                                        <td>
                                            <strong>{{ $setting->value }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $setting->type }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $setting->description ?? 'No description' }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-cog fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No system settings found</h5>
                <p class="text-muted">System settings will be displayed here.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

