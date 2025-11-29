@extends('layouts.app')

@section('title', 'Verify Email - Formonic Design & Construction Ltd')

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
    
    .verify-container {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 2rem;
        width: 100%;
        position: relative;
        z-index: 10;
    }
    
    .verify-wrapper {
        width: 100%;
        max-width: 500px;
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
    
    .verify-card {
        border: none;
        border-radius: 0;
        background-color: transparent;
        padding: 2.5rem;
    }
    
    .verify-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .verify-logo {
        margin-bottom: 1.5rem;
    }
    
    .verify-logo-icon {
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
    
    .verify-logo-icon:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
    }
    
    .verify-title {
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .verify-subtitle {
        color: #6b7280;
        font-size: 1rem;
    }
    
    .verify-message {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        text-align: center;
        line-height: 1.6;
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
    
    .btn-link {
        color: #FF6B35;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: none;
        border: none;
        padding: 0;
        font-size: inherit;
        cursor: pointer;
    }
    
    .btn-link:hover {
        color: #E55A2B;
        text-decoration: underline;
    }
    
    .verify-footer {
        text-align: center;
        margin-top: 2rem;
    }
    
    .verify-footer a {
        color: #FF6B35;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .verify-footer a:hover {
        color: #E55A2B;
        text-decoration: underline;
        transform: translateX(-2px);
    }
    
    @media (max-width: 768px) {
        .verify-container {
            justify-content: center;
            padding: 1rem;
        }
        
        .verify-wrapper {
            max-width: 100%;
            border-radius: 20px;
        }
    }
    
    @media (max-width: 576px) {
        .verify-wrapper {
            max-width: 100%;
            border-radius: 0;
            margin: 0;
            height: 100vh;
        }
        
        .verify-card {
            padding: 1.5rem;
        }
    }
    
    @media (max-height: 700px) {
        .verify-logo-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }
        
        .verify-header {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="verify-container">
    <div class="verify-wrapper">
        <div class="verify-card">
            <div class="verify-header">
                <div class="verify-logo">
                    <div class="verify-logo-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                <h1 class="verify-title">Verify Your Email</h1>
                <p class="verify-subtitle">Please verify your email address</p>
            </div>

            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            <p class="verify-message">
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
            </p>

            <form class="d-inline text-center" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn-link">
                    {{ __('click here to request another') }}
                </button>.
            </form>
            
            <div class="verify-footer">
                <p class="mb-0 small">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i> Back to Login
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
