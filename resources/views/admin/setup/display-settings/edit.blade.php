@extends('admin.setup.setup-layout')

@section('page-title', 'Edit Public System Settings')
@section('page-description', 'Edit public system settings values (public/private status cannot be changed)')

@section('setup-content')
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-primary">
                    <i class="fas fa-edit me-2"></i> Edit Public System Settings
                </h5>
                <div>
                    <a href="{{ route('admin.setup.display.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to Public Settings
                    </a>
                    <button type="submit" form="systemSettingsForm" class="btn btn-sm btn-primary">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="systemSettingsForm" action="{{ route('admin.setup.display.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Settings by Group -->
                @php
                    $groupedSettings = $systemSettings->groupBy('group');
                @endphp
                
                @foreach($groupedSettings as $group => $settings)
                <div class="card border mb-4">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-uppercase">
                            <i class="fas fa-layer-group me-2"></i>{{ ucfirst($group) }} Settings {{ $settings->count() }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($settings as $setting)
                            <div class="col-md-6 mb-3">
                                <div class="card border-light">
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <label class="form-label fw-bold small mb-0">
                                                <code class="text-primary">{{ $setting->key }}</code>
                                            </label>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">{{ $setting->description ?? 'No description' }}</small>
                                        </div>
                                        
                                        <!-- Value Input -->
                                        <div class="mb-3">
                                            @if($setting->type === 'boolean')
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="setting_{{ $setting->id }}" 
                                                           name="settings[{{ $setting->id }}][value]"
                                                           value="1"
                                                           {{ $setting->value == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="setting_{{ $setting->id }}">
                                                        Enable this setting
                                                    </label>
                                                </div>
                                            @elseif($setting->type === 'integer')
                                                <div class="input-group">
                                                    <input type="number" class="form-control form-control-sm" 
                                                           name="settings[{{ $setting->id }}][value]"
                                                           value="{{ $setting->value ?? 0 }}"
                                                           min="0"
                                                           required>
                                                    <span class="input-group-text">value</span>
                                                </div>
                                            @else
                                                <input type="text" class="form-control form-control-sm" 
                                                       name="settings[{{ $setting->id }}][value]"
                                                       value="{{ $setting->value ?? '' }}"
                                                       required>
                                            @endif
                                        </div>
                                        
                                        <!-- Public Status Display (Read-only) -->
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-globe text-success me-1"></i>
                                                <strong>Public Setting</strong> - This setting is visible to all users
                                            </small>
                                        </div>
                                        
                                        <!-- Hidden fields for other properties -->
                                        <input type="hidden" name="settings[{{ $setting->id }}][key]" value="{{ $setting->key }}">
                                        <input type="hidden" name="settings[{{ $setting->id }}][type]" value="{{ $setting->type }}">
                                        <input type="hidden" name="settings[{{ $setting->id }}][group]" value="{{ $setting->group }}">
                                        <input type="hidden" name="settings[{{ $setting->id }}][description]" value="{{ $setting->description }}">
                                        <input type="hidden" name="settings[{{ $setting->id }}][is_public]" value="1">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                
                <!-- Save Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Save Public Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the form reference
    const form = document.getElementById('systemSettingsForm');
    
    if (form) {
        // Form validation
        form.addEventListener('submit', function(e) {
            // Basic validation - ensure at least one setting is modified
            const inputs = form.querySelectorAll('input[type="text"], input[type="number"], input[type="checkbox"]');
            let hasChanges = false;
            
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    // For checkboxes, check if they're different from their default state
                    const originalValue = input.defaultChecked ? '1' : '0';
                    const currentValue = input.checked ? '1' : '0';
                    if (originalValue !== currentValue) {
                        hasChanges = true;
                    }
                } else {
                    // For text/number inputs, check if they're different from their default value
                    if (input.value !== input.defaultValue) {
                        hasChanges = true;
                    }
                }
            });
            
            if (!hasChanges) {
                e.preventDefault();
                alert('Please make at least one change before saving.');
                return false;
            }
        });
    }
});
</script>
@endsection
