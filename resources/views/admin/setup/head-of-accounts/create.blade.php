@extends('admin.setup.setup-layout')

@section('page-title', 'Create Head of Account')
@section('page-description', 'Add a new head of account to the chart')

@section('setup-content')
<div class="card shadow">
    <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-plus me-2"></i>Create Head of Account
        </h6>
        <a href="{{ route('admin.setup.head-of-accounts.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>
    
    <div class="card-body p-3">
        <form action="{{ route('admin.setup.head-of-accounts.store') }}" method="POST">
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
                            <option value="income" {{ old('account_type') == 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ old('account_type') == 'expense' ? 'selected' : '' }}>Expense</option>
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
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
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

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="show_in_requisition" 
                                   name="show_in_requisition" 
                                   value="1"
                                   {{ old('show_in_requisition') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold small" for="show_in_requisition">
                                Show in Requisition
                            </label>
                            <small class="form-text text-muted d-block">Check this to display this account in requisition dropdown</small>
                        </div>
                        @error('show_in_requisition')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.setup.head-of-accounts.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times me-1"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i>Create Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

