@extends('admin.layouts.app')

@section('title', 'Dashboard - Formonic Design & Construction Ltd')

@section('content')
@permission('system.dashboard')
<div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        <!-- Department Chart -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4" style="height: 80vh; background-image: url('{{ asset('images/principles-of-construction-design-1024x699.jpg') }}'); background-size: cover; background-position: center; position: relative;">
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(30, 60, 114, 0.3) 0%, rgba(42, 82, 152, 0.4) 50%, rgba(30, 60, 114, 0.3) 100%);"></div>
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; z-index: 10;">
                    <h1 class="text-white" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 3.5rem; font-weight: 700; letter-spacing: 2px; text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5); text-align: center;">
                        Formonic Design and Construction Ltd.
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