<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <h6 class="card-title mb-0 text-primary fw-bold">
                        <i class="fas fa-book me-2"></i> Account Entry
                    </h6>
                    @if($voucher_type != 'contra')
                    <div class="toggle-button-wrapper">
                        <input type="checkbox" 
                               class="toggle-checkbox" 
                               id="toggleProjectForm" 
                               wire:model.live="show_project_in_items"
                               style="display: none;">
                        <label class="btn btn-sm toggle-btn {{ $show_project_in_items ? 'btn-primary' : 'btn-outline-primary' }}" 
                               for="toggleProjectForm"
                               style="cursor: pointer;">
                            Project
                        </label>
                    </div>
                    @endif
                    @if(($voucher_type == 'debit' || $voucher_type == 'credit') && $voucher_type != 'contra')
                    <div class="toggle-button-wrapper">
                        <input type="checkbox" 
                               class="toggle-checkbox" 
                               id="togglePaymentMethodForm" 
                               wire:model.live="show_payment_method_in_items"
                               style="display: none;">
                        <label class="btn btn-sm toggle-btn {{ $show_payment_method_in_items ? 'btn-primary' : 'btn-outline-primary' }}" 
                               for="togglePaymentMethodForm"
                               style="cursor: pointer;">
                            Payment Method
                        </label>
                    </div>
                    @endif
                </div>
                <div class="btn-group" role="group">
                    <input type="radio" 
                           class="btn-check" 
                           name="voucher_type" 
                           id="voucher_debit" 
                           value="debit"
                           wire:model.live="voucher_type"
                           autocomplete="off">
                    <label class="btn btn-sm {{ $voucher_type == 'debit' ? 'btn-primary' : 'btn-outline-primary' }}" for="voucher_debit">
                        Debit
                    </label>

                    <input type="radio" 
                           class="btn-check" 
                           name="voucher_type" 
                           id="voucher_credit" 
                           value="credit"
                           wire:model.live="voucher_type"
                           autocomplete="off">
                    <label class="btn btn-sm {{ $voucher_type == 'credit' ? 'btn-primary' : 'btn-outline-primary' }}" for="voucher_credit">
                        Credit
                    </label>

                    <input type="radio" 
                           class="btn-check" 
                           name="voucher_type" 
                           id="voucher_journal" 
                           value="journal"
                           wire:model.live="voucher_type"
                           autocomplete="off">
                    <label class="btn btn-sm {{ $voucher_type == 'journal' ? 'btn-primary' : 'btn-outline-primary' }}" for="voucher_journal">
                        Journal
                    </label>

                    <input type="radio" 
                           class="btn-check" 
                           name="voucher_type" 
                           id="voucher_contra" 
                           value="contra"
                           wire:model.live="voucher_type"
                           autocomplete="off">
                    <label class="btn btn-sm {{ $voucher_type == 'contra' ? 'btn-primary' : 'btn-outline-primary' }}" for="voucher_contra" title="Contra: Transfers between accounts (e.g., Cash to Bank)">
                        Contra
                    </label>
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Account Entry Form -->
            <div class="row mb-4">
                <!-- Full Width Column -->
                <div class="col-12 px-0" wire:click.away="hideAllDropdowns">
                    <!-- Account Entry Details Card -->
                    <div class="card border">
                        <div class="card-body py-1">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <label for="entry-date" class="col-sm-4 col-form-label">
                                            <i class="fas fa-calendar-alt me-1 text-muted"></i>Date<span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="date" 
                                                   id="entry-date"
                                                   class="form-control form-control-sm @error('entry_date') is-invalid @enderror" 
                                                   wire:model="entry_date"
                                                   aria-label="Entry date"
                                                   aria-describedby="@if($errors->has('entry_date')) entry-date-error @endif"
                                                   aria-required="true">
                                            @error('entry_date') 
                                                <div id="entry-date-error" class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div> 
                                            @enderror
                                        </div>
                                    </div>
                                    @if(($voucher_type == 'debit' || $voucher_type == 'credit') && !$show_payment_method_in_items)
                                    <div class="row mb-3">
                                        <label for="payment-method-select" class="col-sm-4 col-form-label">
                                            <i class="fas fa-credit-card me-1 text-muted"></i>Payment Method
                                        </label>
                                        <div class="col-sm-8">
                                            <select id="payment-method-select"
                                                    class="form-control form-control-sm @error('treasury_account_id') is-invalid @enderror" 
                                                    wire:model.live="treasury_account_id"
                                                    aria-label="Select payment method"
                                                    aria-describedby="@if($errors->has('treasury_account_id')) payment-method-error @endif">
                                                <option value="">Select Payment Method</option>
                                                @foreach($treasury_accounts as $treasury)
                                                <option value="{{ $treasury['id'] }}">
                                                    {{ $treasury['account_name'] }} 
                                                    @if($treasury['account_type'] == 'bank')
                                                        ({{ $treasury['bank_name'] ?? 'Bank' }})
                                                    @else
                                                        (Cash)
                                                    @endif
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('treasury_account_id') 
                                                <div id="payment-method-error" class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div> 
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                    @if(!$show_project_in_items && $voucher_type != 'contra')
                                    <div class="row">
                                        <label for="project-search-input" class="col-sm-4 col-form-label">
                                            <i class="fas fa-building me-1 text-muted"></i>Project
                                        </label>
                                        <div class="col-sm-8 position-relative" wire:click.away="hideProjectDropdown">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-white border-end-0" aria-hidden="true">
                                                    <i class="fas fa-building text-muted"></i>
                                                </span>
                                                <input type="text" 
                                                       id="project-search-input"
                                                       class="form-control form-control-sm border-start-0 {{ $selected_project_id ? 'bg-success bg-opacity-10 border-success' : '' }}" 
                                                       wire:model.live.debounce.300ms="project_search" 
                                                       wire:click="showRecentProjects"
                                                       wire:focus="showRecentProjects"
                                                       placeholder="Search project..." 
                                                       autocomplete="off"
                                                       aria-label="Search project"
                                                       aria-autocomplete="list"
                                                       aria-expanded="{{ $show_project_dropdown ? 'true' : 'false' }}">
                                                @if($selected_project_id)
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            wire:click="clearProject" 
                                                            title="Clear Project"
                                                            aria-label="Clear selected project">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @if($show_project_dropdown && count($project_results) > 0)
                                                <div class="list-group position-absolute mt-1 bg-white border rounded shadow" 
                                                     style="max-height: 300px; overflow-y: auto; z-index: 9999 !important; width: 100%; min-width: 400px; top: 100%; left: 0; position: absolute !important;"
                                                     wire:ignore.self
                                                     role="listbox"
                                                     aria-label="Project search results">
                                                    @foreach($project_results as $result)
                                                        <button type="button" 
                                                                class="list-group-item list-group-item-action border-0" 
                                                                wire:click="selectProject({{ $result['id'] }})"
                                                                style="cursor: pointer;"
                                                                role="option"
                                                                aria-label="Select project {{ $result['project_name'] ?? 'N/A' }}">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <strong class="me-2 text-truncate" style="max-width: 60%;">{{ $result['project_name'] ?? 'N/A' }}</strong>
                                                                <small class="text-muted text-truncate" style="max-width: 40%;">{{ Str::limit($result['address'] ?? '', 40) }}</small>
                                                            </div>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="entry-remark" class="col-sm-4 col-form-label">
                                            <i class="fas fa-comment-alt me-1 text-muted"></i>Remark
                                        </label>
                                        <div class="col-sm-8">
                                            <textarea id="entry-remark"
                                                      class="form-control form-control-sm" 
                                                      rows="3" 
                                                      wire:model="remark" 
                                                      placeholder="Enter any remarks..."
                                                      aria-label="Enter remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Head Search Card -->
                    <div class="card border mt-3" style="overflow: visible !important;">
                        <div class="card-header bg-light py-1 d-flex justify-content-between align-items-center gap-2 position-relative" style="overflow: visible !important; z-index: 1000;">
                            <h6 class="mb-0"><i class="fas fa-search me-1"></i> 
                                @if($voucher_type == 'debit')
                                    Search Debit Account Head
                                @elseif($voucher_type == 'credit')
                                    Search Credit Account Head
                                @elseif($voucher_type == 'contra')
                                    Treasury Head
                                @else
                                    Search Account Head
                                @endif
                            </h6>
                            <div class="position-relative" style="width: 50%; overflow: visible !important; z-index: 1001;">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           id="account-search"
                                           class="form-control form-control-sm border-start-0" 
                                           wire:model.live.debounce.300ms="account_search" 
                                           wire:click="showRecentAccounts"
                                           wire:focus="showRecentAccounts"
                                           placeholder="Search and select account head to add..." 
                                           autocomplete="off"
                                           aria-label="Search account head"
                                           aria-autocomplete="list"
                                           aria-expanded="{{ $show_account_dropdown ? 'true' : 'false' }}"
                                           aria-describedby="account-search-help">
                                </div>
                                <small id="account-search-help" class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle me-1"></i>Type to search accounts
                                </small>
                                @if($show_account_dropdown && count($account_results) > 0)
                                    <div class="list-group position-absolute mt-1 bg-white border rounded shadow" 
                                         style="max-height: 300px; overflow-y: auto; z-index: 10000 !important; width: 200%; min-width: 500px; top: 100%; left: 0; position: absolute !important;"
                                         wire:ignore.self
                                         role="listbox"
                                         aria-label="Account search results">
                                        @foreach($account_results as $index => $result)
                                            <button type="button" 
                                                    class="list-group-item list-group-item-action border-0" 
                                                    wire:click="addItem({{ $result['id'] }})"
                                                    style="cursor: pointer;"
                                                    role="option"
                                                    aria-label="Add account {{ $result['account_name'] ?? 'N/A' }}"
                                                    @if($index === 0) aria-selected="true" @endif>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <strong class="me-2">{{ $result['account_name'] ?? 'N/A' }}</strong>
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if(isset($result['is_treasury']) && $result['is_treasury'])
                                                            <span class="badge {{ $result['account_type'] == 'cash' ? 'bg-warning' : 'bg-info' }}">
                                                                {{ ucfirst($result['account_type'] ?? 'N/A') }}
                                                            </span>
                                                            <span class="text-muted small">Treasury</span>
                                                            @if($voucher_type == 'contra')
                                                                <span class="text-muted small">Balance: {{ number_format($result['current_balance'] ?? 0, 0) }}</span>
                                                            @endif
                                                        @else
                                                            <span class="badge {{ $result['account_type'] == 'income' ? 'bg-success' : 'bg-danger' }}">
                                                                {{ ucfirst($result['account_type'] ?? 'N/A') }}
                                                            </span>
                                                            <span class="text-info small">L{{ $result['account_level'] ?? 'N/A' }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @error('contra_balance')
                                <div class="text-danger small mt-1 w-100">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="card-body p-0" style="overflow: visible !important;">
                            <div class="table-responsive" wire:key="table-{{ $treasury_account_id }}-{{ $voucher_type }}-{{ $show_project_in_items }}-{{ $show_payment_method_in_items }}" style="overflow-x: auto !important; overflow-y: visible !important;">
                                <table class="table table-sm table-hover mb-0" style="table-layout: fixed; width: 100%; overflow: visible !important;">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            @php
                                                $treasury = $treasury_account_id ? \App\Models\TreasuryAccount::find($treasury_account_id) : null;
                                                $isBank = $treasury && $treasury->account_type === 'bank';
                                                $hasBankColumns = ($voucher_type == 'debit' || $voucher_type == 'credit') && $isBank && !$show_payment_method_in_items;
                                            @endphp
                                            <th style="width: 5%; white-space: nowrap; color: #000; font-weight: bold; text-align: center;">S.No</th>
                                            <th style="width: 15%; white-space: nowrap; color: #000; font-weight: bold;">HEAD OF ACCOUNT</th>
                                            <th style="width: 15%; white-space: nowrap; color: #000; font-weight: bold;">DESCRIPTION</th>
                                            @if($voucher_type != 'contra')
                                            <th style="width: 12%; white-space: nowrap; color: #000; font-weight: bold;">PURCHASE ORDER</th>
                                            @endif
                                            @if($show_project_in_items && $voucher_type != 'contra')
                                            <th style="width: 12%; white-space: nowrap; color: #000; font-weight: bold;">PROJECT</th>
                                            @endif
                                            @if($show_payment_method_in_items && ($voucher_type == 'debit' || $voucher_type == 'credit'))
                                            <th style="width: 12%; white-space: nowrap; color: #000; font-weight: bold;">PAYMENT METHOD</th>
                                            @endif
                                            @if($show_payment_method_in_items && ($voucher_type == 'debit' || $voucher_type == 'credit'))
                                            <th style="width: 10%; white-space: nowrap; color: #000; font-weight: bold;">BANK NAME</th>
                                            <th style="width: 8%; white-space: nowrap; color: #000; font-weight: bold;">CHECK NO</th>
                                            @elseif($hasBankColumns && !$show_payment_method_in_items)
                                            <th style="width: 10%; white-space: nowrap; color: #000; font-weight: bold;">BANK NAME</th>
                                            <th style="width: 8%; white-space: nowrap; color: #000; font-weight: bold;">CHECK NO</th>
                                            @endif
                                            @if($voucher_type == 'journal' || $voucher_type == 'contra')
                                            <th style="width: 10%; white-space: nowrap; color: #000; font-weight: bold; text-align: right;">DEBIT</th>
                                            <th style="width: 10%; white-space: nowrap; color: #000; font-weight: bold; text-align: right;">CREDIT</th>
                                            @else
                                            <th style="width: 10%; white-space: nowrap; color: #000; font-weight: bold; text-align: right;">
                                                @if($voucher_type == 'debit')
                                                    DEBIT
                                                @elseif($voucher_type == 'credit')
                                                    CREDIT
                                                @endif
                                            </th>
                                            @endif
                                            <th style="width: 8%; white-space: nowrap; color: #000; font-weight: bold; text-align: center;">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($items) > 0)
                                            @foreach($items as $index => $item)
                                            <tr>
                                                <td class="py-0 text-center" style="color: #000;">
                                                    <strong>{{ $loop->iteration }}</strong>
                                                </td>
                                                <td class="py-0">
                                                    <strong>{{ $item['account_name'] ?? 'N/A' }}</strong>
                                                </td>

                                                <td class="py-0">
                                                    <input type="text" 
                                                        class="form-control form-control-sm" 
                                                        wire:model="items.{{ $index }}.description"
                                                        placeholder="Description">
                                                </td>
                                                
                                                @if($voucher_type != 'contra')
                                                <!-- Purchase Order Column -->
                                                <td class="py-0 position-relative" wire:click.away="hidePurchaseOrderDropdownItem({{ $index }})">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" 
                                                               class="form-control form-control-sm {{ !empty($item['purchase_order_id']) ? 'bg-success bg-opacity-10 border-success' : '' }}" 
                                                               wire:model.live.debounce.300ms="items.{{ $index }}.purchase_order_search" 
                                                               wire:click.stop="showPurchaseOrderDropdown({{ $index }})"
                                                               wire:focus.stop="showPurchaseOrderDropdown({{ $index }})"
                                                               placeholder="Search PO..."
                                                               onclick="event.stopPropagation();" 
                                                               autocomplete="off"
                                                               style="font-size: 0.75rem;">
                                                    </div>
                                                    @if(!empty($item['show_po_dropdown']) && count($item['purchase_order_results'] ?? []) > 0)
                                                        <div class="list-group position-absolute mt-1 bg-white border rounded shadow" 
                                                             style="max-height: 200px; overflow-y: auto; z-index: 9999 !important; width: 200%; min-width: 350px; top: 100%; left: 0; position: absolute !important;"
                                                             wire:ignore.self>
                                                            @foreach($item['purchase_order_results'] as $po)
                                                                <button type="button" 
                                                                        class="list-group-item list-group-item-action border-0" 
                                                                        wire:click="selectItemPurchaseOrder({{ $po['id'] }}, {{ $index }})"
                                                                        style="cursor: pointer; font-size: 0.75rem;">
                                                                    <div>
                                                                        <strong class="d-block">{{ $po['purchase_order_number'] ?? 'N/A' }}</strong>
                                                                        <small class="text-muted">{{ $po['supplier_name'] ?? 'N/A' }} - {{ $po['purchase_order_date'] ?? 'N/A' }}</small>
                                                                    </div>
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                @endif
                                                
                                                @if($show_project_in_items && $voucher_type != 'contra')
                                                <!-- Project Column -->
                                                <td class="py-0 position-relative" wire:click.away="hideProjectDropdownItem({{ $index }})">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" 
                                                               class="form-control form-control-sm {{ !empty($item['project_id']) ? 'bg-success bg-opacity-10 border-success' : '' }}" 
                                                               wire:model.live.debounce.300ms="items.{{ $index }}.project_search" 
                                                               wire:click.stop="showProjectDropdown({{ $index }})"
                                                               wire:focus.stop="showProjectDropdown({{ $index }})"
                                                               placeholder="Search project..." 
                                                               autocomplete="off"
                                                               style="font-size: 0.75rem;"
                                                               onclick="event.stopPropagation();">
                                                    </div>
                                                    @if(!empty($item['show_project_dropdown']) && count($item['project_results'] ?? []) > 0)
                                                        <div class="list-group position-absolute mt-1 bg-white border rounded shadow" 
                                                             style="max-height: 200px; overflow-y: auto; z-index: 9999 !important; width: 200%; min-width: 350px; top: 100%; left: 0; position: absolute !important;"
                                                             wire:ignore.self
                                                             wire:click.stop>
                                                            @foreach($item['project_results'] as $project)
                                                                <button type="button" 
                                                                        class="list-group-item list-group-item-action border-0" 
                                                                        wire:click.stop="selectItemProject({{ $project['id'] }}, {{ $index }})"
                                                                        style="cursor: pointer; font-size: 0.75rem;">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <strong class="me-2 text-truncate" style="max-width: 60%;">{{ $project['name'] ?? 'N/A' }}</strong>
                                                                        <small class="text-muted text-truncate" style="max-width: 40%;">{{ Str::limit($project['address'] ?? '', 40) }}</small>
                                                                    </div>
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                @endif
                                                
                                                @if($show_payment_method_in_items && ($voucher_type == 'debit' || $voucher_type == 'credit'))
                                                <!-- Payment Method Column -->
                                                <td class="py-0 position-relative" wire:click.away="hideTreasuryDropdownItem({{ $index }})" wire:key="treasury-td-{{ $index }}">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" 
                                                               class="form-control form-control-sm {{ !empty($item['treasury_account_id_item']) ? 'bg-success bg-opacity-10 border-success' : '' }}" 
                                                               wire:model.live.debounce.300ms="items.{{ $index }}.treasury_account_search" 
                                                               wire:click.stop="showTreasuryDropdown({{ $index }})"
                                                               wire:focus.stop="showTreasuryDropdown({{ $index }})"
                                                               placeholder="Search payment method..." 
                                                               autocomplete="off"
                                                               style="font-size: 0.75rem;"
                                                               onclick="event.stopPropagation();">
                                                    </div>
                                                    @if(!empty($item['show_treasury_dropdown']) && count($item['treasury_account_results'] ?? []) > 0)
                                                        <div class="list-group position-absolute mt-1 bg-white border rounded shadow" 
                                                             style="max-height: 200px; overflow-y: auto; z-index: 9999 !important; width: 200%; min-width: 350px; top: 100%; left: 0; position: absolute !important;"
                                                             wire:ignore.self>
                                                            @foreach($item['treasury_account_results'] as $treasury)
                                                                <button type="button" 
                                                                        class="list-group-item list-group-item-action border-0" 
                                                                        wire:click="selectItemTreasuryAccount({{ $treasury['id'] }}, {{ $index }})"
                                                                        style="cursor: pointer; font-size: 0.75rem;">
                                                                    <div>
                                                                        <strong class="d-block">{{ $treasury['account_name'] ?? 'N/A' }}</strong>
                                                                        <small class="text-muted">{{ $treasury['account_type'] == 'bank' ? ($treasury['bank_name'] ?? 'Bank') : 'Cash' }}</small>
                                                                    </div>
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                @php
                                                    $itemTreasury = !empty($item['treasury_account_id_item']) ? \App\Models\TreasuryAccount::find($item['treasury_account_id_item']) : null;
                                                    $itemIsBank = $itemTreasury && $itemTreasury->account_type === 'bank';
                                                @endphp
                                                @if($itemIsBank)
                                                <!-- Bank Name Column -->
                                                <td class="py-0">
                                                    <input type="text" 
                                                           class="form-control form-control-sm" 
                                                           wire:model="items.{{ $index }}.bank_name"
                                                           placeholder="Bank Name"
                                                           style="font-size: 0.75rem;">
                                                </td>
                                                <!-- Check Number Column -->
                                                <td class="py-0">
                                                    <input type="text" 
                                                           class="form-control form-control-sm" 
                                                           wire:model="items.{{ $index }}.check_number"
                                                           placeholder="Check No"
                                                           style="font-size: 0.75rem;">
                                                </td>
                                                @else
                                                <td class="py-0"></td>
                                                <td class="py-0"></td>
                                                @endif
                                                @endif

                                                @if($voucher_type == 'journal')
                                                    <!-- Debit Column -->
                                                    <td class="py-0">
                                                        @if(($item['account_type'] ?? '') === 'income')
                                                            <div class="text-center text-muted fst-italic" style="padding: 0.375rem 0; background-color: #f8f9fa;">
                                                                Income head
                                                            </div>
                                                            <input type="hidden" wire:model.live="items.{{ $index }}.debit_amount" value="0">
                                                        @else
                                                            <input type="number" 
                                                                class="form-control form-control-sm text-end" 
                                                                wire:model.live="items.{{ $index }}.debit_amount"
                                                                placeholder="0.00" 
                                                                min="0" 
                                                                step="0.01">
                                                        @endif
                                                    </td>

                                                    <!-- Credit Column -->
                                                    <td class="py-0">
                                                        @if(($item['account_type'] ?? '') === 'expense')
                                                            <div class="text-center text-muted fst-italic" style="padding: 0.375rem 0; background-color: #f8f9fa;">
                                                                Expense head
                                                            </div>
                                                            <input type="hidden" wire:model.live="items.{{ $index }}.credit_amount" value="0">
                                                        @else
                                                            <input type="number" 
                                                                class="form-control form-control-sm text-end" 
                                                                wire:model.live="items.{{ $index }}.credit_amount"
                                                                placeholder="0.00" 
                                                                min="0" 
                                                                step="0.01">
                                                        @endif
                                                    </td>

                                                @elseif($voucher_type == 'contra')
                                                    <td class="py-0">
                                                        <input type="number" 
                                                            class="form-control form-control-sm text-end" 
                                                            wire:model.live="items.{{ $index }}.debit_amount"
                                                            placeholder="Debit Amount"
                                                            min="0" 
                                                            step="0.01">
                                                    </td>
                                                    <td class="py-0">
                                                        <input type="number" 
                                                            class="form-control form-control-sm text-end" 
                                                            wire:model.live="items.{{ $index }}.credit_amount"
                                                            placeholder="Credit Amount"
                                                            min="0" 
                                                            step="0.01">
                                                    </td>

                                                @else
                                                    <td class="py-0">
                                                        <input type="number" 
                                                            class="form-control form-control-sm text-end" 
                                                            wire:model.live="items.{{ $index }}.amount"
                                                            placeholder="0.00" 
                                                            min="0" 
                                                            step="0.01">
                                                    </td>
                                                @endif
                                                
                                                @if($hasBankColumns && !$show_payment_method_in_items)
                                                <!-- Bank Name Column -->
                                                <td class="py-0">
                                                    <input type="text" 
                                                           class="form-control form-control-sm" 
                                                           wire:model="items.{{ $index }}.bank_name"
                                                           placeholder="Bank Name"
                                                           style="font-size: 0.75rem;">
                                                </td>
                                                <!-- Check Number Column -->
                                                <td class="py-0">
                                                    <input type="text" 
                                                           class="form-control form-control-sm" 
                                                           wire:model="items.{{ $index }}.check_number"
                                                           placeholder="Check No"
                                                           style="font-size: 0.75rem;">
                                                </td>
                                                @endif

                                                <td class="py-0 text-center">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            wire:click="removeItem({{ $index }})"
                                                            style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            @php
                                                // Base columns: S.No, Head, Desc, Action = 4
                                                $colspan = 4;
                                                
                                                if ($voucher_type == 'contra') {
                                                    // Contra: S.No, Head, Desc, Debit, Credit, Action = 6
                                                    $colspan = 6;
                                                } elseif ($voucher_type == 'journal') {
                                                    // Journal: S.No, Head, Desc, Debit, Credit, Action = 6
                                                    $colspan = 6;
                                                } else {
                                                    // Debit/Credit: S.No, Head, Desc, Purchase Order, Amount, Action = 6
                                                    $colspan = 6;
                                                    if ($show_project_in_items) {
                                                        $colspan += 1; // Project
                                                    }
                                                    if ($show_payment_method_in_items) {
                                                        $colspan += 3; // Payment Method, Bank Name, Check No
                                                    }
                                                }
                                                
                                                if ($hasBankColumns && !$show_payment_method_in_items) {
                                                    $colspan += 2; // Bank Name, Check No
                                                }
                                            @endphp
                                            <tr>
                                                <td colspan="{{ $colspan }}" 
                                                    class="text-center text-muted py-4">
                                                    @if($voucher_type == 'contra')
                                                        <p class="mb-0"><strong>Contra Entry:</strong> Add items in pairs - one Debit (From Account) and one Credit (To Account) with matching amounts.</p>
                                                    @else
                                                        <p class="mb-0">No items selected. Search and select account heads from the results.</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            @php
                                                $colspan = 3; // S.No, Head of Account, Description
                                                if ($voucher_type == 'contra') {
                                                    // Contra: S.No, Head, Desc (already 3)
                                                } else {
                                                    $colspan += 1; // Purchase Order
                                                    if ($show_project_in_items) {
                                                        $colspan += 1; // Project
                                                    }
                                                    if ($show_payment_method_in_items) {
                                                        $colspan += 3; // Payment Method, Bank Name, Check No
                                                    }
                                                }
                                                if ($hasBankColumns && !$show_payment_method_in_items) {
                                                    $colspan += 2; // Bank Name, Check No
                                                }
                                            @endphp
                                            <td colspan="{{ $colspan }}" style="text-align: right; font-weight: bold; color: #000;">
                                                Total Items: {{ count($items) }}
                                            </td>
                                            @if($voucher_type == 'journal' || $voucher_type == 'contra')
                                            <td style="text-align: right; font-weight: bold; color: #000;">
                                                {{ number_format($total_debit, 0) }}
                                            </td>
                                            <td style="text-align: right; font-weight: bold; color: #000;">
                                                {{ number_format($total_credit, 0) }}
                                            </td>
                                            @else
                                            <td style="text-align: right; font-weight: bold; color: #000;">
                                                @if($voucher_type == 'debit')
                                                    {{ number_format($total_debit, 0) }}
                                                @elseif($voucher_type == 'credit')
                                                    {{ number_format($total_credit, 0) }}
                                                @endif
                                            </td>
                                            @endif
                                            @if($hasBankColumns)
                                                <td></td>
                                                <td></td>
                                            @endif
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- Save Button -->
                            <div class="card-footer bg-light py-2">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" 
                                            class="btn btn-sm btn-primary" 
                                            wire:click="saveEntry"
                                            wire:loading.attr="disabled"
                                            {{ count($items) == 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-save me-1"></i> 
                                        <span wire:loading.remove>Save Entry</span>
                                        <span wire:loading>Saving...</span>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-warning" 
                                            wire:click="resetForm">
                                        <i class="fas fa-redo me-1"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    /* Allow dropdowns to escape card boundaries */
    .container-fluid,
    .container-fluid > .card,
    .container-fluid > .card > .card-body,
    .card,
    .card-body,
    .card-header {
        overflow: visible !important;
    }

    /* Card header must allow overflow for dropdowns */
    .card-header.position-relative {
        overflow: visible !important;
        z-index: 1000 !important;
    }

    /* Ensure table allows dropdown overflow */
    .table-responsive {
        overflow-x: auto !important;
        overflow-y: visible !important;
    }

    .table,
    .table td,
    .table th,
    .table tbody,
    .table tbody tr {
        overflow: visible !important;
    }

    /* Position relative for dropdowns */
    .position-relative {
        overflow: visible !important;
    }

    /* Ensure dropdowns are on top */
    .list-group.position-absolute {
        z-index: 9999 !important;
        position: absolute !important;
    }

    /* Card header dropdowns need higher z-index */
    .card-header .list-group.position-absolute {
        z-index: 10000 !important;
        position: absolute !important;
    }

    /* Table cell dropdowns */
    .table td .list-group.position-absolute {
        z-index: 9999 !important;
        position: absolute !important;
    }

    /* Table cells with position-relative must allow overflow */
    .table td.position-relative {
        overflow: visible !important;
        position: relative !important;
    }

    /* Ensure rows and columns allow overflow */
    .row,
    [class*="col-"] {
        overflow: visible !important;
    }

    /* Input group must allow overflow */
    .input-group {
        overflow: visible !important;
    }

    /* Ensure card header flex container allows overflow */
    .card-header.d-flex {
        overflow: visible !important;
    }

    /* Specific fix for account head search card */
    .card.border.mt-3 {
        overflow: visible !important;
    }

    .card.border.mt-3 .card-header {
        overflow: visible !important;
    }

    .card.border.mt-3 .card-body {
        overflow: visible !important;
    }
    </style>
</div>
