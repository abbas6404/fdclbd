@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">System Settings</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Settings Tabs -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                        <i class="fas fa-cog me-1"></i> General
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                        <i class="fas fa-shield-alt me-1"></i> Security
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab" aria-controls="email" aria-selected="false">
                        <i class="fas fa-envelope me-1"></i> Email
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="settingsTabsContent">
                <!-- General Settings Tab -->
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <form method="POST" action="{{ route('admin.settings.update.general') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">Site Name</label>
                                    <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="site_name" name="site_name" value="{{ old('site_name', $generalSettings['site_name']) }}" required>
                                    @error('site_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">Contact Email</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $generalSettings['contact_email']) }}" required>
                                    @error('contact_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="site_description" class="form-label">Site Description</label>
                                    <textarea class="form-control @error('site_description') is-invalid @enderror" id="site_description" name="site_description" rows="3">{{ old('site_description', $generalSettings['site_description']) }}</textarea>
                                    @error('site_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_logo" class="form-label">Site Logo</label>
                                    <input type="file" class="form-control @error('site_logo') is-invalid @enderror" id="site_logo" name="site_logo">
                                    @error('site_logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-text">Recommended size: 200x50 pixels. Max file size: 2MB.</div>
                                </div>
                                
                                @if(config('app.logo'))
                                <div class="mb-3">
                                    <label class="form-label">Current Logo</label>
                                    <div class="border rounded p-2">
                                        <img src="{{ Storage::url(config('app.logo')) }}" alt="Site Logo" class="img-fluid" style="max-height: 100px;">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save General Settings
                        </button>
                    </form>
                </div>
                
                <!-- Security Settings Tab -->
                <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                    <form method="POST" action="{{ route('admin.settings.update.security') }}">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enable_registration" name="enable_registration" value="1" {{ $securitySettings['enable_registration'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_registration">Enable User Registration</label>
                                    <div class="form-text">Allow new users to register on the site.</div>
                                </div>
                                
                                <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enable_social_login" name="enable_social_login" value="1" {{ $securitySettings['enable_social_login'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="enable_social_login">Enable Social Login</label>
                                    <div class="form-text">Allow users to login with social media accounts.</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="default_user_role" class="form-label">Default User Role</label>
                                    <select class="form-select @error('default_user_role') is-invalid @enderror" id="default_user_role" name="default_user_role" required>
                                        @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                            <option value="{{ $role->id }}" {{ $securitySettings['default_user_role'] == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('default_user_role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-text">The default role assigned to newly registered users.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="login_attempts" class="form-label">Max Login Attempts</label>
                                    <input type="number" class="form-control @error('login_attempts') is-invalid @enderror" id="login_attempts" name="login_attempts" value="{{ old('login_attempts', $securitySettings['login_attempts']) }}" min="3" max="10" required>
                                    @error('login_attempts')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-text">Maximum number of failed login attempts before temporary lockout.</div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Security Settings
                        </button>
                    </form>
                </div>
                
                <!-- Email Settings Tab -->
                <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                    <form method="POST" action="{{ route('admin.settings.update.email') }}" class="mb-4">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mail_driver" class="form-label">Mail Driver</label>
                                    <select class="form-select @error('mail_driver') is-invalid @enderror" id="mail_driver" name="mail_driver" required>
                                        <option value="smtp" {{ $emailSettings['mail_driver'] == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                        <option value="sendmail" {{ $emailSettings['mail_driver'] == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                        <option value="mailgun" {{ $emailSettings['mail_driver'] == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                        <option value="ses" {{ $emailSettings['mail_driver'] == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                        <option value="postmark" {{ $emailSettings['mail_driver'] == 'postmark' ? 'selected' : '' }}>Postmark</option>
                                    </select>
                                    @error('mail_driver')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_host" class="form-label">SMTP Host</label>
                                    <input type="text" class="form-control @error('mail_host') is-invalid @enderror" id="mail_host" name="mail_host" value="{{ old('mail_host', $emailSettings['mail_host']) }}">
                                    @error('mail_host')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_port" class="form-label">SMTP Port</label>
                                    <input type="number" class="form-control @error('mail_port') is-invalid @enderror" id="mail_port" name="mail_port" value="{{ old('mail_port', $emailSettings['mail_port']) }}">
                                    @error('mail_port')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_encryption" class="form-label">Encryption</label>
                                    <select class="form-select @error('mail_encryption') is-invalid @enderror" id="mail_encryption" name="mail_encryption">
                                        <option value="">None</option>
                                        <option value="tls" {{ $emailSettings['mail_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ $emailSettings['mail_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    </select>
                                    @error('mail_encryption')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mail_username" class="form-label">SMTP Username</label>
                                    <input type="text" class="form-control @error('mail_username') is-invalid @enderror" id="mail_username" name="mail_username" value="{{ old('mail_username', $emailSettings['mail_username']) }}">
                                    @error('mail_username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_password" class="form-label">SMTP Password</label>
                                    <input type="password" class="form-control @error('mail_password') is-invalid @enderror" id="mail_password" name="mail_password" placeholder="Leave blank to keep current password">
                                    @error('mail_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-text">Leave blank to keep the current password.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_from_address" class="form-label">From Address</label>
                                    <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror" id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', $emailSettings['mail_from_address']) }}" required>
                                    @error('mail_from_address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mail_from_name" class="form-label">From Name</label>
                                    <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror" id="mail_from_name" name="mail_from_name" value="{{ old('mail_from_name', $emailSettings['mail_from_name']) }}" required>
                                    @error('mail_from_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Email Settings
                        </button>
                    </form>
                    
                    <hr>
                    
                    <!-- Test Email Form -->
                    <h5 class="mb-3">Send Test Email</h5>
                    <form method="POST" action="{{ route('admin.settings.email.test') }}" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="email" class="form-control @error('test_email') is-invalid @enderror" id="test_email" name="test_email" placeholder="Enter email address" required>
                                <button type="submit" class="btn btn-outline-primary">Send Test Email</button>
                            </div>
                            @error('test_email')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('css/admin-layout.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the hash from the URL
        const hash = window.location.hash;
        
        // If there's a hash and it corresponds to a tab, activate that tab
        if (hash) {
            const tab = document.querySelector(`button[data-bs-target="${hash}"]`);
            if (tab) {
                const tabInstance = new bootstrap.Tab(tab);
                tabInstance.show();
            }
        }
        
        // Update URL hash when tab is changed
        const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabEls.forEach(tabEl => {
            tabEl.addEventListener('shown.bs.tab', function(event) {
                const target = event.target.getAttribute('data-bs-target');
                window.location.hash = target;
            });
        });
        
        // Show/hide SMTP fields based on mail driver selection
        const mailDriverSelect = document.getElementById('mail_driver');
        const smtpFields = document.querySelectorAll('#mail_host, #mail_port, #mail_username, #mail_password, #mail_encryption');
        const smtpFieldsContainer = document.querySelectorAll('#mail_host, #mail_port, #mail_username, #mail_password, #mail_encryption')
            .forEach(field => field.closest('.mb-3'));
        
        function toggleSmtpFields() {
            const isSmtp = mailDriverSelect.value === 'smtp';
            smtpFields.forEach(field => {
                field.required = isSmtp;
                field.closest('.mb-3').style.display = isSmtp ? 'block' : 'none';
            });
        }
        
        if (mailDriverSelect) {
            mailDriverSelect.addEventListener('change', toggleSmtpFields);
            toggleSmtpFields(); // Run once on page load
        }
    });
</script>
@endpush 