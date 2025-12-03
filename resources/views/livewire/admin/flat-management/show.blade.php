<div class="container-fluid">
    <div class="card shadow border-0">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-eye me-2"></i> Flat Details
                </h6>
                <div>
                    <a href="{{ route('admin.project-flat.edit', $flat->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.project-flat.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Basic Information</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold" style="width: 200px;">Flat Number:</td>
                            <td>
                                <span class="badge bg-primary">{{ $flat->flat_number }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Project:</td>
                            <td>
                                <strong>{{ $flat->project->project_name ?? 'N/A' }}</strong>
                                @if($flat->project->address)
                                    <br><small class="text-muted">{{ $flat->project->address }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Type:</td>
                            <td>
                                <span class="badge bg-info">{{ $flat->flat_type ?? 'N/A' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Floor:</td>
                            <td>{{ $flat->floor_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Size:</td>
                            <td>{{ $flat->flat_size ? number_format($flat->flat_size, 2) . ' sq ft' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status:</td>
                            <td>
                                <span class="badge bg-{{ $flat->status == 'available' ? 'success' : ($flat->status == 'sold' ? 'danger' : ($flat->status == 'reserved' ? 'warning' : ($flat->status == 'land_owner' ? 'secondary' : 'info'))) }}">
                                    {{ ucfirst(str_replace('_', ' ', $flat->status ?? 'N/A')) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Additional Information</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold" style="width: 200px;">Created At:</td>
                            <td>{{ $flat->created_at ? $flat->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Updated At:</td>
                            <td>{{ $flat->updated_at ? $flat->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                        </tr>
                        @if($flat->createdBy)
                        <tr>
                            <td class="fw-bold">Created By:</td>
                            <td>{{ $flat->createdBy->name ?? 'N/A' }}</td>
                        </tr>
                        @endif
                        @if($flat->updatedBy)
                        <tr>
                            <td class="fw-bold">Updated By:</td>
                            <td>{{ $flat->updatedBy->name ?? 'N/A' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

