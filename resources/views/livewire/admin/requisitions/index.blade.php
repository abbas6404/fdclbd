<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white py-1">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-shopping-cart me-2"></i> Purchase Requisition
                </h6>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Requisition Form -->
            <div class="row mb-4">
                <!-- Left Column -->
                <div class="col-md-7 px-0">
                    <!-- Requisition Details Card -->
                    <div class="card border">
                        <div class="card-header bg-light py-1">
                            <h6 class="mb-0"><i class="fas fa-file-alt me-1"></i> Requisition Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Required Date<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" 
                                                   id="required-date"
                                                   class="form-control form-control-sm @error('required_date') is-invalid @enderror" 
                                                   wire:model="required_date">
                                            @error('required_date') 
                                                <span class="text-danger small">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Remark</label>
                                        <div class="col-sm-8">
                                            <textarea id="requisition-remark"
                                                      class="form-control form-control-sm" 
                                                      rows="3" 
                                                      wire:model="remark" 
                                                      placeholder="Enter any remarks..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Project</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                            <input type="text" 
                                                   id="project-search" 
                                                       class="form-control form-control-sm {{ $selected_project_id ? 'bg-success bg-opacity-10 border-success' : '' }}" 
                                                   wire:model.live.debounce.300ms="project_search" 
                                                   wire:click="showRecentProjects"
                                                   placeholder="Search project..." 
                                                   autocomplete="new-password">
                                    @if($selected_project_id)
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        wire:click="clearProject"
                                                        title="Clear Project">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <label class="col-sm-4 col-form-label">Employee<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                            <input type="text" 
                                                   id="employee-search" 
                                                       class="form-control form-control-sm {{ $selected_employee_id ? 'bg-success bg-opacity-10 border-success' : '' }} @error('selected_employee_id') is-invalid @enderror" 
                                                   wire:model.live.debounce.300ms="employee_search" 
                                                   wire:click="showRecentEmployees"
                                                   placeholder="Search employee..." 
                                                   autocomplete="new-password">
                                                @if($selected_employee_id)
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        wire:click="clearEmployee"
                                                        title="Clear Employee">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                            @error('selected_employee_id') 
                                                <span class="text-danger small">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expense Head Search Card -->
                    <div class="card border mt-3">
                        <div class="card-header bg-light py-1 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-search me-1"></i> Search Expense Head</h6>
                            <div style="width: 50%;">
                                <input type="text" 
                                       id="account-search" 
                                       class="form-control form-control-sm" 
                                       wire:model.live.debounce.300ms="account_search" 
                                       wire:click="showRecentAccounts"
                                       placeholder="Search expense account..." 
                                       autocomplete="new-password">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if(count($items) > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 5%;">S.No</th>
                                            <th style="width: 18%;">Expense Head</th>
                                            <th style="width: 30%;">Description</th>
                                            <th style="width: 8%;">Qty</th>
                                            <th style="width: 8%;">Unit</th>
                                            <th style="width: 10%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                @foreach($items as $index => $item)
                                        <tr>
                                            <td class="align-middle text-center">
                                                <strong>{{ $loop->iteration }}</strong>
                                            </td>
                                            <td class="align-middle p-2" 
                                                title="{{ $item['account_name'] ?? 'N/A' }}"
                                                style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ $item['account_name'] ?? 'N/A' }}
                                            </td>
                                            <td class="p-0">
                                                    <input type="text" 
                                                       class="form-control form-control-sm border-0 rounded-0" 
                                                           wire:model="items.{{ $index }}.description"
                                                       placeholder="Description"
                                                       style="width: 100%; height: 100%; min-height: 38px;">
                                            </td>
                                            <td class="p-0">
                                                    <input type="number" 
                                                       class="form-control form-control-sm border-0 rounded-0 text-end" 
                                                       wire:model.blur="items.{{ $index }}.qty"
                                                           placeholder="Qty" 
                                                           min="1" 
                                                       step="1"
                                                       style="width: 100%; height: 100%; min-height: 38px;">
                                            </td>
                                            <td class="p-0">
                                                    <select class="form-control form-control-sm border-0 rounded-0" 
                                                            wire:model="items.{{ $index }}.unit"
                                                            style="width: 100%; height: 100%; min-height: 38px;">
                                                        @foreach($unitOptions as $key => $label)
                                                            <option value="{{ $key }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                            </td>
                                            <td class="align-middle text-center">
                                        <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        wire:click="removeItem({{ $index }})">
                                                    <i class="fas fa-trash"></i>
                                        </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th class="text-end" colspan="5">Total Items: {{ count($items) }}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                </div>
                            @else
                            <div class="text-center text-muted py-5">
                                    <i class="fas fa-list fa-2x mb-2"></i>
                                    <p class="mb-0">No items selected. Search and select expense heads from the results.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-3 d-flex justify-content-end gap-2">
                        <button class="btn btn-primary btn-sm" 
                                wire:click="saveRequisition" 
                                wire:loading.attr="disabled"
                                {{ count($items) == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-save me-1"></i> 
                            <span wire:loading.remove>Save Requisition</span>
                            <span wire:loading>Saving...</span>
                        </button>
                        <button class="btn btn-warning btn-sm" wire:click="resetForm">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-5">
                    <!-- Search Results Area -->
                    <div class="card border mb-3" id="search-results-container">
                        <div class="card-header bg-primary text-white py-1">
                            <h6 class="mb-0">
                                <i class="fas fa-search me-1"></i> 
                                @if($active_search_type === 'project')
                                    Recent Projects
                                @elseif($active_search_type === 'employee')
                                    Recent Employees
                                @elseif($active_search_type === 'account')
                                    Recent Accounts
                                @else
                                    Search Results
                                @endif
                            </h6>
                        </div>
                        <div class="card-body p-0" style="height: 400px; overflow-y: auto;" id="search-results-body">
                            @if($active_search_type === 'project' && count($project_results) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="small">Project Name</th>
                                                <th class="small">Address</th>
                                                <th class="small">Facing</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                @foreach($project_results as $result)
                                            <tr class="search-item" 
                                     wire:click="selectProject({{ $result['id'] }})"
                                     style="cursor: pointer;">
                                                <td class="small text-nowrap arrow-indicator" title="{{ $result['project_name'] ?? 'N/A' }}">
                                                    <span class="arrow-icon">▶</span>
                                                    <strong>{{ $result['project_name'] ?? 'N/A' }}</strong>
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['address'] ?? 'N/A' }}">
                                                    {{ Str::limit($result['address'] ?? 'N/A', 30) }}
                                                </td>
                                                <td class="small text-nowrap">
                                                    {{ $result['facing'] ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($active_search_type === 'employee' && count($employee_results) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="small">Name</th>
                                                <th class="small">Email</th>
                                                <th class="small">Phone</th>
                                                <th class="small">Position</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                @foreach($employee_results as $result)
                                            <tr class="search-item" 
                                     wire:click="selectEmployee({{ $result['id'] }})"
                                     style="cursor: pointer;">
                                                <td class="small text-nowrap arrow-indicator" title="{{ $result['name'] ?? 'N/A' }}">
                                                    <span class="arrow-icon">▶</span>
                                                    <strong>{{ $result['name'] ?? 'N/A' }}</strong>
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['email'] ?? 'N/A' }}">
                                                    {{ Str::limit($result['email'] ?? 'N/A', 25) }}
                                                </td>
                                                <td class="small text-nowrap">
                                                    {{ $result['phone'] ?? 'N/A' }}
                                                </td>
                                                <td class="small text-nowrap">
                                                    {{ Str::limit($result['position'] ?? 'N/A', 20) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($active_search_type === 'account' && count($account_results) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="small">Account Name</th>
                                                <th class="small">Full Path</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                @foreach($account_results as $result)
                                            <tr class="search-item" 
                                     wire:click="addItem({{ $result['id'] }})"
                                     style="cursor: pointer;">
                                                <td class="small text-nowrap arrow-indicator" title="{{ $result['account_name'] ?? 'N/A' }}">
                                                    <span class="arrow-icon">▶</span>
                                                    <strong>{{ $result['account_name'] ?? 'N/A' }}</strong>
                                                </td>
                                                <td class="small text-nowrap" title="{{ $result['full_path'] ?? 'N/A' }}">
                                                    {{ Str::limit($result['full_path'] ?? 'N/A', 40) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-3 text-center text-muted">
                                    <i class="fas fa-search fa-2x mb-2"></i>
                                    @if($active_search_type === 'project')
                                        <p>No projects found. Start searching to see results.</p>
                                    @elseif($active_search_type === 'employee')
                                        <p>No employees found. Start searching to see results.</p>
                                    @elseif($active_search_type === 'account')
                                        <p>No expense accounts found. Start searching to see results.</p>
                                    @else
                                        <p>Start searching to see results</p>
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

    <script>
        document.addEventListener('livewire:init', () => {
            // Handle amount calculation when qty or rate changes on blur
            Livewire.on('livewire:update', () => {
                // This will be called after any Livewire update
                // The calculation is handled server-side in updatedItems method
            });

            // Ensure date field is properly initialized
            document.addEventListener('DOMContentLoaded', function() {
                const requiredDateInput = document.getElementById('required-date');
                if (requiredDateInput && !requiredDateInput.value) {
                    // Set default to current date if not set
                    const today = new Date();
                    const formattedDate = today.toISOString().split('T')[0];
                    requiredDateInput.value = formattedDate;
                }
            });
        });
    </script>
</div>
