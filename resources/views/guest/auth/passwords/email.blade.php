@extends('layouts.app')

@section('title', 'Forgot Password - Formonic Design & Construction Ltd')

@push('styles')
<style>
    body {
        background-image: url("{{ asset('images/principles-of-construction-design-1024x699.jpg') }}");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        height: 100vh;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        overflow: hidden;
        position: relative;
    }
    
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.4) 0%, rgba(42, 82, 152, 0.5) 50%, rgba(30, 60, 114, 0.4) 100%);
        z-index: 1;
        pointer-events: none;
    }
    
    body::after {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, rgba(30, 60, 114, 0.2) 0%, rgba(30, 60, 114, 0.35) 50%, rgba(30, 60, 114, 0.5) 100%);
        z-index: 2;
        pointer-events: none;
    }
    
    .forgot-container {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 2rem;
        width: 100%;
        position: relative;
        z-index: 10;
    }
    
    .forgot-wrapper {
        width: 100%;
        max-width: 450px;
        box-shadow: 
            0 25px 70px rgba(0, 0, 0, 0.5),
            0 0 0 1px rgba(255, 255, 255, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        overflow: hidden;
        background-color: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 20;
        animation: slideInRight 0.6s ease-out;
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .forgot-card {
        border: none;
        border-radius: 0;
        background-color: transparent;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .forgot-header {
        text-align: center;
        padding: 2.5rem 2rem 1.5rem;
    }
    
    .forgot-logo {
        margin-bottom: 1.5rem;
    }
    
    .forgot-logo-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);
        color: white;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .forgot-logo-icon:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
    }
    
    .forgot-title {
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .forgot-subtitle {
        color: #6b7280;
        font-size: 1rem;
    }
    
    .forgot-form-container {
        padding: 0 2.5rem 2.5rem;
        flex: 1;
        overflow-y: auto;
    }
    
    .form-label {
        color: #4b5563;
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
    
    .form-control {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #e2e8f0;
        height: 3rem;
        font-size: 0.95rem;
        background-color: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
    }
    
    .form-control:hover {
        border-color: #cbd5e0;
        background-color: #f8f9fa;
    }
    
    .form-control:focus {
        border-color: #FF6B35;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.15);
        outline: none;
        transform: translateY(-1px);
    }
    
    .input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        z-index: 10;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .input-group:focus-within .input-icon {
        color: #FF6B35;
        transform: translateY(-50%) scale(1.1);
    }
    
    .input-group:hover .input-icon {
        color: #9ca3af;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        height: 3rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #E55A2B 0%, #E0841A 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
    }
    
    .btn-primary:active {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        animation: shake 0.4s cubic-bezier(0.36, 0.07, 0.19, 0.97);
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .invalid-feedback {
        display: block !important;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .alert-success {
        background-color: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #10b981;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        animation: fadeIn 0.3s ease-in;
    }
    
    .forgot-footer {
        text-align: center;
        margin-top: 1.5rem;
    }
    
    .forgot-footer a {
        color: #FF6B35;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .forgot-footer a:hover {
        color: #E55A2B;
        text-decoration: underline;
        transform: translateX(-2px);
    }
    
    @media (max-width: 768px) {
        .forgot-container {
            justify-content: center;
            padding: 1rem;
        }
        
        .forgot-wrapper {
            max-width: 100%;
            border-radius: 20px;
        }
    }
    
    @media (max-width: 576px) {
        .forgot-wrapper {
            max-width: 100%;
            border-radius: 0;
            margin: 0;
            height: 100vh;
        }
        
        .forgot-form-container {
            padding: 0 1.5rem 2rem;
        }
        
        .forgot-header {
            padding: 1.5rem 1.5rem 1rem;
        }
    }
    
    @media (max-height: 700px) {
        .forgot-logo-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }
        
        .forgot-header {
            padding: 1.5rem 2rem 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="forgot-container">
    <div class="forgot-wrapper">
        <div class="forgot-card">
            <div class="forgot-header">
                <div class="forgot-logo">
                    <div class="forgot-logo-icon">
                        <i class="fas fa-lock-open"></i>
                    </div>
                </div>
                <h1 class="forgot-title">Forgot Your Password?</h1>
                <p class="forgot-subtitle">Enter your email address and we'll send you a password reset link</p>
            </div>

            <div class="forgot-form-container">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email address">
                        </div>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary mb-4">
                        <i class="fas fa-paper-plane me-2"></i> Send Password Reset Link
                    </button>
                    
                    <div class="forgot-footer">
                        <p class="mb-0 small">
                            <a href="{{ route('login') }}">
                                <i class="fas fa-arrow-left"></i> Back to Login
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
