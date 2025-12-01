@extends('layouts.app')

@section('title', 'Login - Formonic Design & Construction Ltd')

@push('styles')
<style>
    body {
        background-image: url("{{ asset('images/bg-login.jpg') }}");
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
    
    .login-container {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 2rem;
        width: 100%;
        position: relative;
        z-index: 10;
    }
    
    .login-wrapper {
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
    
    .login-card {
        border: none;
        border-radius: 0;
        background-color: #ffffff;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .login-header {
        text-align: center;
        padding: 2rem 2rem 1rem;
    }
    
    .login-logo {
        margin-bottom: 1.5rem;
    }
    
    .login-logo-icon {
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
    }
    
    .login-title {
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .login-subtitle {
        color: #6b7280;
        font-size: 1rem;
    }
    
    .login-form-container {
        padding: 0 3rem 2rem;
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
    
    .form-check-input {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .form-check-input:hover {
        border-color: #FF6B35;
        transform: scale(1.1);
    }
    
    .form-check-input:checked {
        background-color: #FF6B35;
        border-color: #FF6B35;
        transform: scale(1.05);
    }
    
    /* Enhanced error styling */
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
    
    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
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
    
    .invalid-feedback i {
        margin-right: 0.5rem;
    }
    
    /* Input focus states */
    .form-control:focus {
        border-color: #FF6B35;
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    }
    
    /* Button loading state */
    .btn-primary:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }
    
    .btn-primary.loading {
        position: relative;
        color: transparent;
    }
    
    .btn-primary.loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .login-footer {
        text-align: center;
        margin-top: 1.5rem;
    }
    
    .login-footer a {
        color: #FF6B35;
        font-weight: 500;
        text-decoration: none;
    }
    
    .login-footer a:hover {
        text-decoration: underline;
    }
    
    .forgot-password {
        text-align: right;
        font-size: 0.9rem;
    }
    
    .forgot-password a {
        color: #FF6B35;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-block;
    }
    
    .forgot-password a:hover {
        color: #E55A2B;
        text-decoration: underline;
        transform: translateX(2px);
    }
    
    .remember-me {
        display: flex;
        align-items: center;
    }
    
    .remember-me label {
        font-size: 0.9rem;
        color: #6b7280;
        margin-left: 0.5rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
    }
    
    .remember-me:hover label {
        color: #4b5563;
    }
    
    .form-check-input {
        width: 1rem;
        height: 1rem;
        margin-top: 0.25rem;
        border: 1px solid #cbd5e0;
        cursor: pointer;
    }
    
    @media (max-width: 768px) {
        .login-container {
            justify-content: center;
            padding: 1rem;
        }
        
        .login-wrapper {
            max-width: 100%;
            border-radius: 20px;
        }
    }
    
    @media (max-width: 576px) {
        .login-wrapper {
            max-width: 100%;
            border-radius: 0;
            margin: 0;
            height: 100vh;
        }
        
        .login-form-container {
            padding: 0 1.5rem 2rem;
        }
        
        .login-header {
            padding: 1.5rem 1.5rem 1rem;
        }
    }
    
    @media (max-height: 700px) {
        .login-logo-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }
        
        .login-header {
            padding: 1.5rem 2rem 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo text-center ">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="FDCL BD Logo" style="height:100px; width:auto;">
                    @else
                        <div class="login-logo-icon">
                            <i class="fas fa-building"></i>
                        </div>
                    @endif
                </div>
                <h1 class="login-title">Welcome Back</h1>
                <p class="login-subtitle">Sign in to Formonic Design & Construction Ltd</p>
            </div>
            
            <div class="login-form-container">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="login" class="form-label">Email / Phone / User Code</label>
                                <div class="input-group">
                                    <i class="fas fa-user input-icon"></i>
                                    <input id="login" type="text" class="form-control @error('login') is-invalid @enderror @error('email') is-invalid @enderror @error('phone') is-invalid @enderror" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus placeholder="Enter your Email, Phone, or User Code">
                                </div>
                                
                                @error('login')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror

                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                                
                                @error('phone')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                </div>

                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="remember-me">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember">Remember Me</label>
                                </div>
                                
                                <div class="forgot-password">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary" id="loginBtn">
                                <i class="fas fa-sign-in-alt me-2"></i> 
                                <span class="btn-text">Sign In to Dashboard</span>
                            </button>
                        </form>
                    </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 for better notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show SweetAlert for validation errors
    @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Login Error',
                html: `
                    <div class="text-start">
                        @foreach($errors->all() as $error)
                            <div class="mb-2">
                                <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                {{ $error }}
                            </div>
                        @endforeach
                    </div>
                `,
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#FF6B35'
            });
    @endif

    // Form validation feedback
    const form = document.querySelector('form');
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    const loginBtn = document.getElementById('loginBtn');

    form.addEventListener('submit', function(e) {
        let hasErrors = false;
        
        // Clear previous error states
        loginInput.classList.remove('is-invalid');
        passwordInput.classList.remove('is-invalid');
        
        // Validate login field
        if (!loginInput.value.trim()) {
            loginInput.classList.add('is-invalid');
            hasErrors = true;
        }
        
        // Validate password field
        if (!passwordInput.value.trim()) {
            passwordInput.classList.add('is-invalid');
            hasErrors = true;
        }
        
        if (hasErrors) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Please Complete All Fields',
                text: 'Please fill in all required fields before submitting.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#FF6B35'
            });
        }
    });

    // Real-time validation feedback
    loginInput.addEventListener('blur', function() {
        if (!this.value.trim()) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    passwordInput.addEventListener('blur', function() {
        if (!this.value.trim()) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Clear error state on input
    loginInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });

    passwordInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });

    // Handle button loading state
    form.addEventListener('submit', function() {
        loginBtn.classList.add('loading');
        loginBtn.disabled = true;
        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Logging In...';
    });

    // Reset button state if form validation fails
    loginInput.addEventListener('input', function() {
        if (loginBtn.disabled) {
            loginBtn.classList.remove('loading');
            loginBtn.disabled = false;
            loginBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i><span class="btn-text">Sign In to Dashboard</span>';
        }
    });

    passwordInput.addEventListener('input', function() {
        if (loginBtn.disabled) {
            loginBtn.classList.remove('loading');
            loginBtn.disabled = false;
            loginBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i><span class="btn-text">Sign In to Dashboard</span>';
        }
    });
});
</script>
@endsection
