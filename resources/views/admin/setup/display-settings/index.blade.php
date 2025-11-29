@extends('admin.setup.setup-layout')

@section('page-title', 'Public System Settings')
@section('page-description', 'View all public system settings and their current values')

@section('setup-content')
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-primary">
                    <i class="fas fa-globe me-2"></i> Public System Settings
                </h5>
                <div>
                    <a href="{{ route('admin.setup.display.edit') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit Public Settings
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Public Settings List -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Setting Key</th>
                            <th>Current Value</th>
                            <th>Type</th>
                            <th>Group</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($systemSettings as $setting)
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
                                <span class="badge bg-info">{{ $setting->group }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $setting->description ?? 'No description' }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i>
                                No public system settings found. Only public settings are displayed here.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            <!-- Quick Actions -->
            <div class="text-center mt-4">
               
                <a href="{{ route('admin.setup.display.edit') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-edit me-2"></i>Edit Public System Settings
                </a>
            </div>
        </div>
    </div>
@endsection
