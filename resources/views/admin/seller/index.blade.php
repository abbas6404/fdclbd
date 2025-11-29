@extends('admin.layouts.app')

@section('title', 'Sales Agents Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Sales Agents</li>
                    </ol>
                </div>
                <h4 class="page-title">Sales Agents Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">All Sales Agents</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.seller.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Sales Agent
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="salesAgentsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>NID/Passport</th>
                                    <th>Address</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesAgents as $agent)
                                <tr>
                                    <td>{{ $agent->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $agent->name }}</div>
                                    </td>
                                    <td>
                                        @if($agent->phone)
                                        <a href="tel:{{ $agent->phone }}" class="text-decoration-none">
                                            <i class="fas fa-phone"></i> {{ $agent->phone }}
                                        </a>
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td>{{ $agent->nid_or_passport_number ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($agent->address, 30) }}</td>
                                    <td>{{ $agent->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.seller.show', $agent->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.seller.edit', $agent->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.seller.destroy', $agent->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this sales agent?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-user-tie fa-3x mb-3"></i>
                                            <p>No sales agents found. <a href="{{ route('admin.seller.create') }}">Add your first sales agent</a></p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#salesAgentsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']]
        });
    });
</script>
@endpush
