@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="error-code mb-4">404</h1>
                <h2 class="error-title mb-4">Page Not Found</h2>
                <p class="error-description mb-4">
                    Oops! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                </p>
                <div class="error-actions">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .error-page {
        padding: 40px 0;
    }
    .error-code {
        font-size: 120px;
        font-weight: 700;
        color: #f44336;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    }
    .error-title {
        font-size: 32px;
        font-weight: 600;
    }
    .error-description {
        font-size: 18px;
        color: #6c757d;
    }
    .error-actions {
        margin-top: 30px;
    }
</style>
@endpush 