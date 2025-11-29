@extends('admin.layouts.app')

@section('title', '403 - Access Denied')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mt-5">
                <div class="error-page">
                    <div class="error-code">
                        <h1 class="display-1 text-danger">403</h1>
                    </div>
                    <div class="error-message">
                        <h2 class="h4 text-gray-800 mb-4">Access Denied</h2>
                        <p class="text-muted mb-4">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            User does not have the right permissions.
                        </p>
                        <p class="text-muted mb-4">
                            You don't have permission to access this page. Please contact your administrator if you believe this is an error.
                        </p>
                    </div>
                    <div class="error-actions">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary me-2">
                            <i class="fas fa-home me-1"></i> Go to Dashboard
                        </a>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    padding: 2rem;
}

.error-code h1 {
    font-size: 6rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.error-message h2 {
    color: #2d3748;
    margin-bottom: 1rem;
}

.error-actions {
    margin-top: 2rem;
}

.error-actions .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}
</style>
@endsection
