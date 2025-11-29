@props([
    'type' => 'info',
    'dismissible' => true,
    'icon' => true
])

@php
    $alertClass = match($type) {
        'success' => 'alert-success',
        'danger'  => 'alert-danger',
        'warning' => 'alert-warning',
        default   => 'alert-info'
    };
    
    $iconClass = match($type) {
        'success' => 'fa-check-circle',
        'danger'  => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        default   => 'fa-info-circle'
    };
@endphp

<div {{ $attributes->merge(['class' => "alert $alertClass"]) }} role="alert">
    @if($icon)
        <i class="fas {{ $iconClass }} me-2"></i>
    @endif
    
    {{ $slot }}
    
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div> 