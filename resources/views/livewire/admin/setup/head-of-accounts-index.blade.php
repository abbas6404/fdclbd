<div>
    <div class="card shadow">
        <div class="card-header py-3">
            <div class="row align-items-center g-3">
                <div class="col-md-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Head of Accounts
                    </h6>
                </div>
                <div class="col-md-9">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       wire:model.live.debounce.300ms="search"
                                       placeholder="Search accounts...">
                                @if($search)
                                    <button class="btn btn-outline-secondary" type="button" wire:click="$set('search', '')" title="Clear">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="button" 
                                    class="btn btn-sm {{ $allExpanded ? 'btn-outline-secondary' : 'btn-outline-primary' }}" 
                                    wire:click="toggleExpandCollapseAll"
                                    title="{{ $allExpanded ? 'Collapse All Accounts' : 'Expand All Accounts' }}">
                                @if($allExpanded)
                                    <i class="fas fa-chevron-up me-1"></i>Collapse All
                                @else
                                    <i class="fas fa-chevron-down me-1"></i>Expand All
                                @endif
                            </button>
                        </div>
                        @can('setup.chart-of-accounts.create')
                        <div class="col-auto">
                            <a href="{{ route('admin.setup.head-of-accounts.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Create Account
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Accounts Tree -->
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Account Name</th>
                            <th>Type</th>
                            <th>Parent</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accountsTree as $treeItem)
                            @php
                                $account = $treeItem['account'];
                                $level = $treeItem['level'];
                                $children = $treeItem['children'];
                                $hasChildren = isset($treeItem['hasChildren']) ? $treeItem['hasChildren'] : ($children->count() > 0);
                                $levelMismatch = $treeItem['levelMismatch'] ?? false;
                                $expectedLevel = $treeItem['expectedLevel'] ?? (int)$account->account_level;
                            @endphp
                            
                            <tr class="account-row align-middle" data-account-id="{{ $account->id }}" style="border-left: {{ $level * 3 }}px solid {{ $level == 0 ? '#0d6efd' : ($level == 1 ? '#198754' : ($level == 2 ? '#fd7e14' : '#6c757d')) }};">
                                <td class="py-2">
                                    <div style="padding-left: {{ $level * 20 }}px; display: flex; align-items: center; gap: 8px;">
                                        @if($hasChildren)
                                            <button type="button" 
                                                    class="btn btn-link p-0 border-0 bg-transparent account-tree-toggle" 
                                                    wire:click="toggleExpand({{ $account->id }})"
                                                    style="text-decoration: none; min-width: 24px; width: 24px; height: 24px; display: inline-flex !important; align-items: center !important; justify-content: center !important; z-index: 10; position: relative; background: transparent !important; border: none !important; padding: 0 !important; cursor: pointer; flex-shrink: 0;">
                                                <i class="fas fa-chevron-right account-tree-icon {{ $this->isExpanded($account->id) ? 'expanded' : '' }}" 
                                                   style="color: #495057 !important; font-size: 1rem !important; display: inline-block !important; visibility: visible !important; opacity: 1 !important; width: 16px !important; height: 16px !important; line-height: 16px !important; transition: transform 0.2s;"></i>
                                            </button>
                                        @else
                                            <span style="display: inline-block; width: 24px; flex-shrink: 0;"></span>
                                        @endif
                                        <strong class="account-name-cell">{{ $account->account_name }}</strong>
                                        <span class="account-level-badge ms-2">L{{ $account->account_level }}</span>
                                        @if((int)$account->account_level < 4)
                                            <button type="button" 
                                                    class="btn btn-link btn-sm p-0 ms-2 text-primary" 
                                                    wire:click="openAddModal({{ $account->id }}, '{{ addslashes($account->account_name) }}', '{{ $account->account_type }}')"
                                                    title="Add child account">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        @endif
                                        @if($levelMismatch)
                                            <i class="fas fa-exclamation-triangle text-danger ms-2" 
                                               title="Level mismatch! Expected Level {{ $expectedLevel }}, but account has Level {{ $account->account_level }}"
                                               data-bs-toggle="tooltip"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $account->account_type === 'income' ? 'success' : 'danger' }}">
                                        {{ ucfirst($account->account_type) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $account->parent ? $account->parent->account_name : '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $account->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($account->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @can('setup.chart-of-accounts.edit')
                                        <a href="{{ route('admin.setup.head-of-accounts.edit', $account->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('setup.chart-of-accounts.delete')
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="confirmDelete({{ $account->id }}, '{{ addslashes($account->account_name) }}')"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            
                            @if($hasChildren)
                                @foreach($children as $childItem)
                                    @php
                                        $childAccount = $childItem['account'];
                                        $childLevel = $childItem['level'];
                                        $childChildren = $childItem['children'];
                                        $childHasChildren = isset($childItem['hasChildren']) ? $childItem['hasChildren'] : ($childChildren->count() > 0);
                                        $childLevelMismatch = $childItem['levelMismatch'] ?? false;
                                        $childExpectedLevel = $childItem['expectedLevel'] ?? (int)$childAccount->account_level;
                                    @endphp
                                    
                                    <tr class="account-child-{{ $account->id }} {{ $this->isExpanded($account->id) ? '' : 'collapsed' }} align-middle" data-account-id="{{ $childAccount->id }}" data-parent-id="{{ $account->id }}" style="border-left: {{ $childLevel * 3 }}px solid {{ $childLevel == 0 ? '#0d6efd' : ($childLevel == 1 ? '#198754' : ($childLevel == 2 ? '#fd7e14' : '#6c757d')) }};">
                                        <td class="py-2">
                                            <div style="padding-left: {{ $childLevel * 20 }}px; display: flex; align-items: center; gap: 8px;">
                                                @if($childHasChildren)
                                                    <button type="button" 
                                                            class="btn btn-link p-0 border-0 bg-transparent account-tree-toggle" 
                                                            wire:click="toggleExpand({{ $childAccount->id }})"
                                                            style="text-decoration: none; min-width: 24px; width: 24px; height: 24px; display: inline-flex !important; align-items: center !important; justify-content: center !important; z-index: 10; position: relative; background: transparent !important; border: none !important; padding: 0 !important; cursor: pointer; flex-shrink: 0;">
                                                        <i class="fas fa-chevron-right account-tree-icon {{ $this->isExpanded($childAccount->id) ? 'expanded' : '' }}" 
                                                           style="color: #495057 !important; font-size: 1rem !important; display: inline-block !important; visibility: visible !important; opacity: 1 !important; width: 16px !important; height: 16px !important; line-height: 16px !important; transition: transform 0.2s;"></i>
                                                    </button>
                                                @else
                                                    <span style="display: inline-block; width: 24px; flex-shrink: 0;"></span>
                                                @endif
                                                <strong class="account-name-cell">{{ $childAccount->account_name }}</strong>
                                                <span class="account-level-badge ms-2">L{{ $childAccount->account_level }}</span>
                                                @if((int)$childAccount->account_level < 4)
                                                    <button type="button" 
                                                            class="btn btn-link btn-sm p-0 ms-2 text-primary" 
                                                            wire:click="openAddModal({{ $childAccount->id }}, '{{ addslashes($childAccount->account_name) }}', '{{ $childAccount->account_type }}')"
                                                            title="Add child account">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </button>
                                                @endif
                                                @if($childLevelMismatch)
                                                    <i class="fas fa-exclamation-triangle text-danger ms-2" 
                                                       title="Level mismatch! Expected Level {{ $childExpectedLevel }}, but account has Level {{ $childAccount->account_level }}"
                                                       data-bs-toggle="tooltip"></i>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $childAccount->account_type === 'income' ? 'success' : 'danger' }}">
                                                {{ ucfirst($childAccount->account_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $childAccount->parent ? $childAccount->parent->account_name : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $childAccount->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($childAccount->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('setup.chart-of-accounts.edit')
                                                <a href="{{ route('admin.setup.head-of-accounts.edit', $childAccount->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endcan
                                                @can('setup.chart-of-accounts.delete')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmDelete({{ $childAccount->id }}, '{{ addslashes($childAccount->account_name) }}')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    @if($childHasChildren)
                                        @foreach($childChildren as $grandChildItem)
                                            @php
                                                $grandChildAccount = $grandChildItem['account'];
                                                $grandChildLevel = $grandChildItem['level'];
                                                $grandChildChildren = $grandChildItem['children'];
                                                $grandChildHasChildren = isset($grandChildItem['hasChildren']) ? $grandChildItem['hasChildren'] : ($grandChildChildren->count() > 0);
                                                $grandChildLevelMismatch = $grandChildItem['levelMismatch'] ?? false;
                                                $grandChildExpectedLevel = $grandChildItem['expectedLevel'] ?? (int)$grandChildAccount->account_level;
                                            @endphp
                                            
                                            <tr class="account-child-{{ $account->id }} account-child-{{ $childAccount->id }} {{ ($this->isExpanded($account->id) && $this->isExpanded($childAccount->id)) ? '' : 'collapsed' }} align-middle" data-account-id="{{ $grandChildAccount->id }}" data-parent-id="{{ $childAccount->id }}" data-grandparent-id="{{ $account->id }}" style="border-left: {{ $grandChildLevel * 3 }}px solid {{ $grandChildLevel == 0 ? '#0d6efd' : ($grandChildLevel == 1 ? '#198754' : ($grandChildLevel == 2 ? '#fd7e14' : '#6c757d')) }};">
                                                <td class="py-2">
                                                    <div style="padding-left: {{ $grandChildLevel * 20 }}px; display: flex; align-items: center; gap: 8px;">
                                                        @if($grandChildHasChildren)
                                                            <button type="button" 
                                                                    class="btn btn-link p-0 border-0 bg-transparent account-tree-toggle" 
                                                                    wire:click="toggleExpand({{ $grandChildAccount->id }})"
                                                                    style="text-decoration: none; min-width: 24px; width: 24px; height: 24px; display: inline-flex !important; align-items: center !important; justify-content: center !important; z-index: 10; position: relative; background: transparent !important; border: none !important; padding: 0 !important; cursor: pointer; flex-shrink: 0;">
                                                                <i class="fas fa-chevron-right account-tree-icon {{ $this->isExpanded($grandChildAccount->id) ? 'expanded' : '' }}" 
                                                                   style="color: #495057 !important; font-size: 1rem !important; display: inline-block !important; visibility: visible !important; opacity: 1 !important; width: 16px !important; height: 16px !important; line-height: 16px !important; transition: transform 0.2s;"></i>
                                                            </button>
                                                        @else
                                                            <span style="display: inline-block; width: 24px; flex-shrink: 0;"></span>
                                                        @endif
                                                        <strong class="account-name-cell">{{ $grandChildAccount->account_name }}</strong>
                                                        <span class="account-level-badge ms-2">L{{ $grandChildAccount->account_level }}</span>
                                                        @if((int)$grandChildAccount->account_level < 4)
                                                            <button type="button" 
                                                                    class="btn btn-link btn-sm p-0 ms-2 text-primary" 
                                                                    wire:click="openAddModal({{ $grandChildAccount->id }}, '{{ addslashes($grandChildAccount->account_name) }}', '{{ $grandChildAccount->account_type }}')"
                                                                    title="Add child account">
                                                                <i class="fas fa-plus-circle"></i>
                                                            </button>
                                                        @endif
                                                        @if($grandChildLevelMismatch)
                                                            <i class="fas fa-exclamation-triangle text-danger ms-2" 
                                                               title="Level mismatch! Expected Level {{ $grandChildExpectedLevel }}, but account has Level {{ $grandChildAccount->account_level }}"
                                                               data-bs-toggle="tooltip"></i>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $grandChildAccount->account_type === 'income' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($grandChildAccount->account_type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $grandChildAccount->parent ? $grandChildAccount->parent->account_name : '-' }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $grandChildAccount->status === 'active' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($grandChildAccount->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @can('setup.chart-of-accounts.edit')
                                                        <a href="{{ route('admin.setup.head-of-accounts.edit', $grandChildAccount->id) }}" 
                                                           class="btn btn-sm btn-outline-primary" 
                                                           title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @endcan
                                                        @can('setup.chart-of-accounts.delete')
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                onclick="confirmDelete({{ $grandChildAccount->id }}, '{{ addslashes($grandChildAccount->account_name) }}')"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                            @if($grandChildHasChildren)
                                                @foreach($grandChildChildren as $greatGrandChildItem)
                                                    @php
                                                        $greatGrandChildAccount = $greatGrandChildItem['account'];
                                                        $greatGrandChildLevel = $greatGrandChildItem['level'];
                                                        $greatGrandChildLevelMismatch = $greatGrandChildItem['levelMismatch'] ?? false;
                                                        $greatGrandChildExpectedLevel = $greatGrandChildItem['expectedLevel'] ?? (int)$greatGrandChildAccount->account_level;
                                                    @endphp
                                                    
                                                    <tr class="account-child-{{ $account->id }} account-child-{{ $childAccount->id }} account-child-{{ $grandChildAccount->id }} {{ ($this->isExpanded($account->id) && $this->isExpanded($childAccount->id) && $this->isExpanded($grandChildAccount->id)) ? '' : 'collapsed' }} align-middle" data-account-id="{{ $greatGrandChildAccount->id }}" data-parent-id="{{ $grandChildAccount->id }}" data-grandparent-id="{{ $childAccount->id }}" data-great-grandparent-id="{{ $account->id }}" style="border-left: {{ $greatGrandChildLevel * 3 }}px solid #6c757d;">
                                                        <td class="py-2">
                                                            <div style="padding-left: {{ $greatGrandChildLevel * 20 }}px; display: flex; align-items: center; gap: 8px;">
                                                                <span style="display: inline-block; width: 24px; flex-shrink: 0;"></span>
                                                                <strong class="account-name-cell">{{ $greatGrandChildAccount->account_name }}</strong>
                                                                <span class="account-level-badge ms-2">L{{ $greatGrandChildAccount->account_level }}</span>
                                                                @if($greatGrandChildLevelMismatch)
                                                                    <i class="fas fa-exclamation-triangle text-danger ms-2" 
                                                                       title="Level mismatch! Expected Level {{ $greatGrandChildExpectedLevel }}, but account has Level {{ $greatGrandChildAccount->account_level }}"
                                                                       data-bs-toggle="tooltip"></i>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-{{ $greatGrandChildAccount->account_type === 'income' ? 'success' : 'danger' }}">
                                                                {{ ucfirst($greatGrandChildAccount->account_type) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            {{ $greatGrandChildAccount->parent ? $greatGrandChildAccount->parent->account_name : '-' }}
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-{{ $greatGrandChildAccount->status === 'active' ? 'success' : 'secondary' }}">
                                                                {{ ucfirst($greatGrandChildAccount->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                @can('setup.chart-of-accounts.edit')
                                                                <a href="{{ route('admin.setup.head-of-accounts.edit', $greatGrandChildAccount->id) }}" 
                                                                   class="btn btn-sm btn-outline-primary" 
                                                                   title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                @endcan
                                                                @can('setup.chart-of-accounts.delete')
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger" 
                                                                        onclick="confirmDelete({{ $greatGrandChildAccount->id }}, '{{ addslashes($greatGrandChildAccount->account_name) }}')"
                                                                        title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                                @endcan
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No head of accounts found</h5>
                                        <p>Head of accounts will be displayed here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="row text-center">
                <div class="col-md-3">
                    <span class="text-primary fw-bold">{{ $stats['total_accounts'] }}</span>
                    <span class="text-muted fst-italic ms-2">Total Accounts</span>
                </div>
                <div class="col-md-3">
                    <span class="text-success fw-bold">{{ $stats['income_accounts'] }}</span>
                    <span class="text-muted fst-italic ms-2">Income Accounts</span>
                </div>
                <div class="col-md-3">
                    <span class="text-danger fw-bold">{{ $stats['expense_accounts'] }}</span>
                    <span class="text-muted fst-italic ms-2">Expense Accounts</span>
                </div>
                <div class="col-md-3">
                    <span class="text-info fw-bold">{{ $stats['active_accounts'] }}</span>
                    <span class="text-muted fst-italic ms-2">Active Accounts</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Child Account Modal -->
    @if($showModal)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Child Account</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Parent Account</label>
                        <input type="text" class="form-control" value="{{ $parentAccountName }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('newAccountName') is-invalid @enderror" 
                               wire:model="newAccountName" 
                               placeholder="Enter account name">
                        @error('newAccountName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Type</label>
                        <select class="form-select" wire:model="newAccountType">
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" wire:model="newAccountStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="saveAccount">Save Account</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    <style>
        .account-tree-icon {
            color: #6c757d !important;
            margin-right: 8px;
            font-size: 0.85rem !important;
            cursor: pointer;
            transition: transform 0.2s;
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            width: 16px !important;
            height: 16px !important;
        }
        .account-tree-icon.expanded {
            transform: rotate(90deg);
        }
        tr[class*="account-child-"] {
            display: table-row;
        }
        tr[class*="account-child-"].collapsed {
            display: none !important;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .table tbody tr.account-row {
            transition: background-color 0.2s;
        }
        .table tbody tr.account-row:hover {
            background-color: #e7f3ff !important;
        }
        .account-name-cell {
            font-weight: 500;
        }
        .account-level-badge {
            font-size: 0.75rem;
            padding: 0.15rem 0.4rem;
            background-color: #e9ecef;
            color: #495057;
            border-radius: 0.25rem;
        }
        .account-tree-toggle {
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 20px !important;
            width: 20px !important;
            height: 20px !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            background: transparent !important;
            position: relative !important;
            z-index: 10 !important;
        }
        .account-tree-toggle i {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    </style>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the account "<strong id="deleteAccountName"></strong>"?</p>
                    <p class="text-danger"><small>This action cannot be undone. If this account has children or is used in transactions, it cannot be deleted.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(accountId, accountName) {
            document.getElementById('deleteAccountName').textContent = accountName;
            document.getElementById('deleteForm').action = '{{ route("admin.setup.head-of-accounts.destroy", ":id") }}'.replace(':id', accountId);
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
</div>
