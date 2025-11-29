@extends('admin.setup.setup-layout')

@section('page-title', 'Edit Head of Account')
@section('page-description', 'Update head of account information')

@section('setup-content')
<div class="card shadow">
    <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-edit me-2"></i>Edit Head of Account
        </h6>
        <a href="{{ route('admin.setup.head-of-accounts.index') }}" class="btn btn-outline-secondary btn-sm">
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

        <form action="{{ route('admin.setup.head-of-accounts.update', $account->id) }}" method="POST">
            @csrf
            @method('PUT')
            
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
                               value="{{ old('account_name', $account->account_name) }}" 
                               placeholder="e.g., Construction Materials"
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
                            <option value="income" {{ old('account_type', $account->account_type) == 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ old('account_type', $account->account_type) == 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                        @error('account_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="parent_id" class="form-label fw-bold small">
                            Parent Account
                        </label>
                        <select class="form-select form-select-sm @error('parent_id') is-invalid @enderror" 
                                id="parent_id" 
                                name="parent_id">
                            <option value="">None (Top Level)</option>
                            @foreach($parentAccounts as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $account->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {!! str_repeat('&nbsp;&nbsp;', (int)$parent->account_level - 1) !!}{{ $parent->account_name }} ({{ ucfirst($parent->account_type) }}) - L{{ $parent->account_level }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Select a parent account if this is a sub-account</small>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="account_level" class="form-label fw-bold small">
                            Account Level
                        </label>
                        <select class="form-select form-select-sm @error('account_level') is-invalid @enderror" 
                                id="account_level" 
                                name="account_level">
                            <option value="1" {{ old('account_level', $account->account_level) == '1' ? 'selected' : '' }}>Level 1 (L1)</option>
                            <option value="2" {{ old('account_level', $account->account_level) == '2' ? 'selected' : '' }}>Level 2 (L2)</option>
                            <option value="3" {{ old('account_level', $account->account_level) == '3' ? 'selected' : '' }}>Level 3 (L3)</option>
                            <option value="4" {{ old('account_level', $account->account_level) == '4' ? 'selected' : '' }}>Level 4 (L4)</option>
                        </select>
                        <small class="form-text text-muted">Select the account level (or leave parent to auto-calculate)</small>
                        @error('account_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold small">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-sm @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="active" {{ old('status', $account->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $account->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.setup.head-of-accounts.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times me-1"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i>Update Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

