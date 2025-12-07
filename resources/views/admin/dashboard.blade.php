@extends('admin.layouts.app')

@section('title', 'Dashboard - Formonic Design & Construction Ltd')

@section('content')
@permission('system.dashboard')
<div class="px-2 px-md-3">
    <div class="row g-0">
        <div class="col-12">
            <div class="card shadow border-0 position-relative overflow-hidden rounded" style="height: calc(100vh - 140px); min-height: 400px; background-image: url('{{ asset('images/bg-login.jpg') }}'); background-size: cover; background-position: center;">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(30, 60, 114, 0.4) 0%, rgba(42, 82, 152, 0.5) 50%, rgba(30, 60, 114, 0.4) 100%);"></div>
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center p-3 p-md-4" style="z-index: 10;">
                    <h1 class="text-white text-center fw-bold m-0" style="font-size: clamp(1.75rem, 7vw, 4.5rem); letter-spacing: 1px; text-shadow: 2px 2px 12px rgba(0, 0, 0, 0.6); line-height: 1.2;">
                        <span class="d-block">Formonic</span>
                        <span class="d-block">Design and</span>
                        <span class="d-block">Construction</span>
                        <span class="d-block fw-semibold" style="font-size: clamp(1.25rem, 4vw, 3rem);">Ltd.</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-lock fa-3x text-danger mb-3"></i>
                    <h4 class="text-danger">Access Denied</h4>
                    <p class="text-muted">You don't have permission to access the dashboard.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endpermission
@endsection

@push('styles')

@endpush

@push('scripts')

@endpush 