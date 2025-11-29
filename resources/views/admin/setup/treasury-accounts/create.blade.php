@extends('admin.setup.setup-layout')

@section('page-title', 'Create Treasury Account')
@section('page-description', 'Add a new treasury account (Cash or Bank)')

@section('setup-content')
<div class="card shadow">
    <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-plus me-2"></i>Create Treasury Account
        </h6>
        <a href="{{ route('admin.setup.treasury-accounts.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>
    
    <div class="card-body p-3">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.setup.treasury-accounts.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="account_name" class="form-label fw-bold small">
                            Account Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control form-control-sm @error('account_name') is-invalid @enderror" 
                               id="account_name" 
                               name="account_name" 
                               value="{{ old('account_name') }}" 
                               placeholder="e.g., Main Cash, SBI Account"
                               required>
                        @error('account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="account_type" class="form-label fw-bold small">
                            Account Type <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm @error('account_type') is-invalid @enderror" 
                                id="account_type" 
                                name="account_type" 
                                required>
                            <option value="">Select Type</option>
                            <option value="cash" {{ old('account_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ old('account_type') == 'bank' ? 'selected' : '' }}>Bank</option>
                        </select>
                        @error('account_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Bank-specific fields (shown only when account_type is 'bank') -->
            <div id="bank-fields" style="display: none;">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="bank_name" class="form-label fw-bold small">
                                Bank Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-sm @error('bank_name') is-invalid @enderror" 
                                   id="bank_name" 
                                   name="bank_name" 
                                   value="{{ old('bank_name') }}" 
                                   placeholder="e.g., State Bank of India">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="account_number" class="form-label fw-bold small">
                                Account Number
                            </label>
                            <input type="text" 
                                   class="form-control form-control-sm @error('account_number') is-invalid @enderror" 
                                   id="account_number" 
                                   name="account_number" 
                                   value="{{ old('account_number') }}" 
                                   placeholder="e.g., 123456789012">
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="branch_name" class="form-label fw-bold small">
                                Branch Name
                            </label>
                            <input type="text" 
                                   class="form-control form-control-sm @error('branch_name') is-invalid @enderror" 
                                   id="branch_name" 
                                   name="branch_name" 
                                   value="{{ old('branch_name') }}" 
                                   placeholder="e.g., Main Branch">
                            @error('branch_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="opening_balance" class="form-label fw-bold small">
                            Opening Balance
                        </label>
                        <input type="number" 
                               class="form-control form-control-sm @error('opening_balance') is-invalid @enderror" 
                               id="opening_balance" 
                               name="opening_balance" 
                               value="{{ old('opening_balance', 0) }}" 
                               step="0.01"
                               min="0"
                               placeholder="0.00">
                        <small class="form-text text-muted">Enter the opening balance in currency units (e.g., 1000.00)</small>
                        @error('opening_balance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold small">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.setup.treasury-accounts.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times me-1"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i>Create Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Show/hide bank fields based on account type selection
    document.addEventListener('DOMContentLoaded', function() {
        const accountTypeSelect = document.getElementById('account_type');
        const bankFields = document.getElementById('bank-fields');
        const bankNameInput = document.getElementById('bank_name');

        function toggleBankFields() {
            if (accountTypeSelect.value === 'bank') {
                bankFields.style.display = 'block';
                bankNameInput.setAttribute('required', 'required');
            } else {
                bankFields.style.display = 'none';
                bankNameInput.removeAttribute('required');
            }
        }

        // Initial state
        toggleBankFields();

        // Listen for changes
        accountTypeSelect.addEventListener('change', toggleBankFields);

        // Also check on page load if there's an old value
        if (accountTypeSelect.value === 'bank') {
            toggleBankFields();
        }
    });
</script>
@endsection

