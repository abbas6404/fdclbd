<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white py-1">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-book me-2"></i> Account Entry
                </h6>
            </div>
        </div>
        <div class="card-body py-3">
            <!-- Account Entry Form -->
            <div class="row mb-4">
                <!-- Left Column -->
                <div class="col-md-7 px-0">
                    <!-- Account Entry Details Card -->
                    <div class="card border">
                        <div class="card-header bg-light py-1 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-file-alt me-1"></i> Voucher Type</h6>
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
                        <div class="card-body py-1">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Date<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="date" 
                                                   id="entry-date"
                                                   class="form-control form-control-sm @error('entry_date') is-invalid @enderror" 
                                                   wire:model="entry_date">
                                            @error('entry_date') 
                                                <span class="text-danger small">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                    </div>
                                    @if($voucher_type == 'debit' || $voucher_type == 'credit')
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Payment Method</label>
                                        <div class="col-sm-8">
                                            <select class="form-control form-control-sm @error('treasury_account_id') is-invalid @enderror" 
                                                    wire:model="treasury_account_id">
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
                                                <span class="text-danger small">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Remark</label>
                                        <div class="col-sm-8">
                                            <textarea id="entry-remark"
                                                      class="form-control form-control-sm" 
                                                      rows="3" 
                                                      wire:model="remark" 
                                                      placeholder="Enter any remarks..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Head Search Card -->
                    <div class="card border mt-3">
                        <div class="card-header bg-light py-1">
                            <div class="row align-items-center">
                                <div class="col-md-6">
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
                                </div>
                                <div class="col-md-6">
                                    <input type="text" 
                                           id="account-search" 
                                           class="form-control form-control-sm" 
                                           wire:model.live.debounce.300ms="account_search" 
                                           wire:click="showRecentAccounts"
                                           placeholder="Search and select account head to add..." 
                                           autocomplete="off">
                                    @error('contra_balance')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th style="white-space: nowrap; color: #000; font-weight: bold; width: 30%;">HEAD OF ACCOUNT</th>
                                            <th style="white-space: nowrap; color: #000; font-weight: bold; width: 35%;">DESCRIPTION</th>
                                            @if($voucher_type == 'journal' || $voucher_type == 'contra')
                                            <th style="white-space: nowrap; color: #000; font-weight: bold; width: 15%;">DEBIT</th>
                                            <th style="white-space: nowrap; color: #000; font-weight: bold; width: 15%;">CREDIT</th>
                                            @else
                                            <th style="white-space: nowrap; color: #000; font-weight: bold; width: 15%;">
                                                @if($voucher_type == 'debit')
                                                    DEBIT
                                                @elseif($voucher_type == 'credit')
                                                    CREDIT
                                                @endif
                                            </th>
                                            @endif
                                            <th style="white-space: nowrap; width: 50px; color: #000; font-weight: bold; width: 5%;">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($items) > 0)
                                            @foreach($items as $index => $item)
                                            <tr>
                                                <td class="py-0" style="overflow: hidden; text-overflow: ellipsis; color: #000;">
                                                    <strong>{{ $item['account_name'] ?? 'N/A' }}</strong>
                                                </td>

                                                <td class="py-0" style="color: #000;">
                                                    <input type="text" 
                                                        class="form-control form-control-sm" 
                                                        wire:model="items.{{ $index }}.description"
                                                        placeholder="Description"
                                                        style="min-width: 150px; color: #000;">
                                                </td>

                                                @if($voucher_type == 'journal')
                                                    <!-- Debit Column -->
                                                    <td class="py-0" style="color: #000;">
                                                        @if(($item['account_type'] ?? '') === 'income')
                                                            <div class="text-center text-muted fst-italic" style="min-width: 120px; padding: 0.375rem 0; background-color: #f8f9fa;">
                                                                Income head
                                                            </div>
                                                            <input type="hidden" wire:model.live="items.{{ $index }}.debit_amount" value="0">
                                                        @else
                                                            <input type="number" 
                                                                class="form-control form-control-sm text-end" 
                                                                wire:model.live="items.{{ $index }}.debit_amount"
                                                                placeholder="0.00" 
                                                                min="0" 
                                                                step="0.01"
                                                                style="min-width: 120px; color: #000;">
                                                        @endif
                                                    </td>

                                                    <!-- Credit Column -->
                                                    <td class="py-0" style="color: #000;">
                                                        @if(($item['account_type'] ?? '') === 'expense')
                                                            <div class="text-center text-muted fst-italic" style="min-width: 120px; padding: 0.375rem 0; background-color: #f8f9fa;">
                                                                Expense head
                                                            </div>
                                                            <input type="hidden" wire:model.live="items.{{ $index }}.credit_amount" value="0">
                                                        @else
                                                            <input type="number" 
                                                                class="form-control form-control-sm text-end" 
                                                                wire:model.live="items.{{ $index }}.credit_amount"
                                                                placeholder="0.00" 
                                                                min="0" 
                                                                step="0.01"
                                                                style="min-width: 120px; color: #000;">
                                                        @endif
                                                    </td>

                                                @elseif($voucher_type == 'contra')
                                                    <td class="py-0" style="color: #000;">
                                                        <input type="number" 
                                                            class="form-control form-control-sm text-end" 
                                                            wire:model.live="items.{{ $index }}.debit_amount"
                                                            placeholder="Debit Amount"
                                                            min="0" 
                                                            step="0.01"
                                                            style="min-width: 120px; color: #000;">
                                                    </td>
                                                    <td class="py-0" style="color: #000;">
                                                        <input type="number" 
                                                            class="form-control form-control-sm text-end" 
                                                            wire:model.live="items.{{ $index }}.credit_amount"
                                                            placeholder="Credit Amount"
                                                            min="0" 
                                                            step="0.01"
                                                            style="min-width: 120px; color: #000;">
                                                    </td>

                                                @else
                                                    <td class="py-0" style="color: #000;">
                                                        <input type="number" 
                                                            class="form-control form-control-sm text-end" 
                                                            wire:model.live="items.{{ $index }}.amount"
                                                            placeholder="0.00" 
                                                            min="0" 
                                                            step="0.01"
                                                            style="min-width: 120px; color: #000;">
                                                    </td>
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
                                            <tr>
                                                <td colspan="{{ $voucher_type == 'journal' || $voucher_type == 'contra' ? 6 : 5 }}" 
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
                                            <td colspan="2" style="text-align: right; font-weight: bold; color: #000;">
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

                <!-- Right Column -->
                <div class="col-md-5">
                    <!-- Search Results Area -->
                    <div class="card border mb-3" id="search-results-container">
                        <div class="card-header bg-primary text-white py-1">
                            <h6 class="mb-0">
                                <i class="fas fa-search me-1"></i> Recent Accounts
                            </h6>
                        </div>
                        <div class="card-body p-0" style="height: 400px; overflow-y: auto; max-height: 400px;" id="search-results-body">
                            @if(count($account_results) > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th style="white-space: nowrap;">ACCOUNT NAME</th>
                                                <th style="white-space: nowrap;">ACCOUNT TYPE</th>
                                                <th style="white-space: nowrap;">LEVEL</th>
                                                @if($voucher_type == 'contra')
                                                <th style="white-space: nowrap;">BALANCE</th>
                                                @else
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($account_results as $index => $result)
                                            <tr wire:click="addItem({{ $result['id'] }})" 
                                                style="cursor: pointer;"
                                                class="search-item">
                                                <td class="py-0 arrow-indicator" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $result['account_name'] ?? 'N/A' }}">
                                                    <span class="arrow-icon">▶</span>
                                                    <strong class="small">{{ $result['account_name'] ?? 'N/A' }}</strong>
                                                </td>
                                                <td class="py-0" style="white-space: nowrap;">
                                                    @if(isset($result['is_treasury']) && $result['is_treasury'])
                                                        <span class="{{ $result['account_type'] == 'cash' ? 'text-warning' : 'text-info' }}">
                                                            {{ ucfirst($result['account_type'] ?? 'N/A') }}
                                                        </span>
                                                    @else
                                                        <span class="{{ $result['account_type'] == 'income' ? 'text-success' : 'text-danger' }}">
                                                            {{ ucfirst($result['account_type'] ?? 'N/A') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-0" style="white-space: nowrap;">
                                                    @if(isset($result['is_treasury']) && $result['is_treasury'])
                                                        <span class="text-secondary">Treasury</span>
                                                    @else
                                                        <span class="text-info">L{{ $result['account_level'] ?? 'N/A' }}</span>
                                                    @endif
                                                </td>
                                                @if($voucher_type == 'contra')
                                                <td class="py-0 text-end" style="white-space: nowrap;">
                                                    <strong>{{ number_format($result['current_balance'] ?? 0, 0) }}</strong>
                                                </td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-3 text-center text-muted">
                                    <i class="fas fa-search fa-2x mb-2"></i>
                                    <p>No accounts found. Start searching to see results.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    .extra-small-text {
        font-size: 0.75rem;
    }
    .search-item:hover {
        background-color: #f8f9fa;
    }
    .table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color:rgb(185, 213, 241);
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
    </style>
</div>

