<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white py-1">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-shopping-cart me-2"></i> Flat Sales
                </h6>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Flat Sales Form -->
            <div class="row mb-4">
                <!-- Left Column -->
                <div class="col-md-7 px-0">
                    <!-- Customer Details Card -->
                    <div class="card border">
                        <div class="card-header bg-light py-1">
                            <h6 class="mb-0"><i class="fas fa-user me-1"></i> Customer Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">S. Customer</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="customer-search" class="form-control form-control-sm" 
                                                   wire:model.live.debounce.300ms="customer_search" 
                                                   wire:click="showRecentCustomers"
                                                   placeholder="Search by name, phone, email, or NID..." 
                                                   autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Name<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input id="customer-name" placeholder="Customer name" type="text" 
                                                   class="form-control form-control-sm @error('customer_name') is-invalid @enderror" 
                                                   wire:model="customer_name" autocomplete="new-password">
                                            @error('customer_name') 
                                                <span class="text-danger small">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Phone<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input id="customer-phone" placeholder="Phone number" type="text" 
                                                   class="form-control form-control-sm @error('customer_phone') is-invalid @enderror" 
                                                   wire:model="customer_phone" autocomplete="new-password">
                                            @error('customer_phone') 
                                                <span class="text-danger small">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Email</label>
                                        <div class="col-sm-8">
                                            <input id="customer-email" placeholder="Email address" type="email" 
                                                   class="form-control form-control-sm" 
                                                   wire:model="customer_email" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">NID or Pass<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input id="customer-nid" placeholder="NID or Passport number" type="text" 
                                                   class="form-control form-control-sm @error('customer_nid') is-invalid @enderror" 
                                                   wire:model="customer_nid" autocomplete="new-password">
                                            @error('customer_nid') 
                                                <span class="text-danger small">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Address</label>
                                        <div class="col-sm-8">
                                            <textarea id="customer-address" placeholder="Customer address..." 
                                                      class="form-control form-control-sm" rows="3" 
                                                      wire:model="customer_address" autocomplete="new-password"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-4 col-form-label">Sales Agent</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="seller-search" class="form-control form-control-sm" 
                                                   wire:model.live.debounce.300ms="seller_search" 
                                                   wire:click="showRecentAgents"
                                                   placeholder="Search seller by name or phone..." 
                                                   autocomplete="new-password">
                                        </div>
                                    </div>
                                    @if($seller_id)
                                    <div class="row mt-2">
                                        <div class="col-sm-8 offset-sm-4">
                                            <div class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Selected: {{ $seller_name }}
                                                <button type="button" class="btn-close btn-close-white ms-2" 
                                                        wire:click="clearSeller" style="font-size: 0.6rem;"></button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Flat Information Card -->
                    <div class="card border mt-3">
                        <div class="card-header bg-light py-1 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-home me-1"></i> Flat Information</h6>
                            <div style="width: 50%;">
                                <input type="text" id="flat-search" class="form-control form-control-sm" 
                                       wire:model.live.debounce.300ms="flat_search" 
                                       wire:click="showRecentFlats"
                                       placeholder="Search flat number, type, or project..." 
                                       autocomplete="new-password">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @error('selected_flats') 
                                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @enderror
                            @if(count($selected_flats) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="small">Flat Number</th>
                                                <th class="small">Type</th>
                                                <th class="small">Floor</th>
                                                <th class="small">Size</th>
                                                <th class="small">Project</th>
                                                <th class="small text-center" style="width: 60px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($selected_flats as $index => $flat)
                                            <tr>
                                                <td class="small">
                                                    <span class="badge bg-primary">{{ $flat['flat_number'] }}</span>
                                                </td>
                                                <td class="small">{{ $flat['flat_type'] ?? 'N/A' }}</td>
                                                <td class="small">{{ $flat['floor_number'] ?? 'N/A' }}</td>
                                                <td class="small">{{ $flat['flat_size'] ?? 'N/A' }}</td>
                                                <td class="small" title="{{ $flat['project_name'] ?? 'N/A' }}">
                                                    {{ Str::limit($flat['project_name'] ?? 'N/A', 20) }}
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            wire:click="removeFlat({{ $flat['id'] }})"
                                                            title="Remove flat">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-home fa-2x mb-2"></i>
                                    <p class="mb-0">No flats selected. Search and select flats from the results.</p>
                                </div>
                            @endif
                            <div class="d-flex justify-content-end gap-2 mt-3 p-3 border-top">
                                <button class="btn btn-primary btn-sm" wire:click="saveSale" 
                                        wire:loading.attr="disabled">
                                    <i class="fas fa-save me-1"></i> 
                                    <span wire:loading.remove>Save</span>
                                    <span wire:loading>Saving...</span>
                                </button>
                                <button class="btn btn-warning btn-sm" wire:click="resetForm">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-5">
                    <!-- Search Results Area -->
                    <div class="card border mb-3" id="search-results-container">
                        <div class="card-header bg-primary text-white py-1">
                            <h6 class="mb-0">
                                <i class="fas fa-search me-1"></i> 
                                @if($active_search_type === 'customer')
                                    Recent Customers ({{ count($customer_results) }})
                                @elseif($active_search_type === 'seller')
                                    Recent Sales Agents ({{ count($seller_results) }})
                                @elseif($active_search_type === 'flat')
                                    Recent Flats ({{ count($flat_results) }})
                                @else
                                    Search Results
                                @endif
                            </h6>
                        </div>
                        <div class="card-body p-0" style="height: 400px; overflow-y: auto;" id="search-results-body">
                            @if($active_search_type === 'customer' && count($customer_results) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="small">Name</th>
                                                <th class="small">Phone</th>
                                                <th class="small">Email</th>
                                                <th class="small">NID</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer_results as $result)
                                            <tr class="search-item" 
                                                wire:click="selectCustomer({{ $result['id'] }})"
                                                style="cursor: pointer;">
                                                <td class="small text-nowrap arrow-indicator" title="{{ $result['name'] ?? 'N/A' }}">
                                                    <span class="arrow-icon">▶</span>
                                                    <strong>{{ $result['name'] ?? 'N/A' }}</strong>
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['phone'] ?? 'N/A' }}">
                                                    {{ $result['phone'] ?? 'N/A' }}
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['email'] ?? 'N/A' }}">
                                                    {{ Str::limit($result['email'] ?? 'N/A', 25) }}
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['nid_or_passport_number'] ?? 'N/A' }}">
                                                    {{ Str::limit($result['nid_or_passport_number'] ?? 'N/A', 15) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($active_search_type === 'flat' && count($flat_results) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="small">Flat Number</th>
                                                <th class="small">Type</th>
                                                <th class="small">Floor</th>
                                                <th class="small">Size</th>
                                                <th class="small">Project</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($flat_results as $result)
                                            <tr class="search-item" 
                                                wire:click="selectFlat({{ $result['id'] }})"
                                                style="cursor: pointer;">
                                                <td class="small text-nowrap arrow-indicator" title="{{ $result['flat_number'] ?? 'N/A' }}">
                                                    <span class="arrow-icon">▶</span>
                                                    <strong>{{ $result['flat_number'] ?? 'N/A' }}</strong>
                                                </td>
                                                <td class="small text-nowrap">
                                                    {{ $result['flat_type'] ?? 'N/A' }}
                                                </td>
                                                <td class="small text-nowrap">
                                                    {{ $result['floor_number'] ?? 'N/A' }}
                                                </td>
                                                <td class="small text-nowrap">
                                                    {{ $result['flat_size'] ?? 'N/A' }}
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['project_name'] ?? 'N/A' }}">
                                                    {{ Str::limit($result['project_name'] ?? 'N/A', 20) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($active_search_type === 'seller' && count($seller_results) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="small">Name</th>
                                                <th class="small">Phone</th>
                                                <th class="small">NID</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($seller_results as $result)
                                            <tr class="search-item" 
                                                wire:click="selectSeller({{ $result['id'] }})"
                                                style="cursor: pointer;">
                                                <td class="small text-nowrap arrow-indicator" title="{{ $result['name'] ?? 'N/A' }}">
                                                    <span class="arrow-icon">▶</span>
                                                    <strong>{{ $result['name'] ?? 'N/A' }}</strong>
                                                </td>
                                                <td class="small text-nowrap">
                                                    {{ $result['phone'] ?? 'N/A' }}
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['nid_or_passport_number'] ?? 'N/A' }}">
                                                    {{ Str::limit($result['nid_or_passport_number'] ?? 'N/A', 15) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-3 text-center text-muted">
                                    <i class="fas fa-search fa-2x mb-2"></i>
                                    @if($active_search_type === 'customer')
                                        <p>No customers found</p>
                                    @elseif($active_search_type === 'seller')
                                        <p>No sales agents found</p>
                                    @elseif($active_search_type === 'flat')
                                        <p>No flats found</p>
                                    @else
                                        <p>No projects found</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    .search-item:hover {
        background-color: #f8f9fa;
    }
    .arrow-indicator {
        position: relative;
        padding-left: 1.0rem !important;
    }
    .arrow-icon {
        position: absolute;
        left: 0rem;
        color: transparent;
        font-size: 14px;
        transition: color 0.2s ease;
        line-height: 1.8;
    }
    .search-item:hover .arrow-icon {
        color: #28a745;
    }
    .table th.small,
    .table td.small {
        font-size: 1.0rem;
        padding: 0.3rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
        border-left: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
    }
    .table th.small:first-child,
    .table td.small:first-child {
        border-left: none;
    }
    .table th.small:last-child,
    .table td.small:last-child {
        border-right: none;
    }
    .table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
    }
    </style>
</div>
