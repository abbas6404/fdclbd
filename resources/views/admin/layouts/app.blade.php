<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin @yield('title', 'Dashboard')</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom styles -->
    <link href="{{ asset('css/admin-layout.css') }}" rel="stylesheet">
        
        @livewireStyles
        @yield('styles')
        @stack('styles')
</head>

<body>
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="skip-to-main">Skip to main content</a>
    
    <div class="admin-layout">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Content Container -->
        <div class="content-container">
            <!-- Sidebar Overlay for mobile -->
            <div class="sidebar-overlay"></div>

            <!-- Top Navbar -->
            @include('admin.layouts.header')

            <!-- Main Content -->
            <main id="main-content" class="main-content" role="main">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('admin.layouts.footer')
        </div>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Scripts -->
    @livewireScripts
    <script src="{{ asset('js/admin-layout.js') }}"></script>
    
    <!-- Disable Autocomplete Globally -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(function(input) {
                if (!input.hasAttribute('autocomplete')) {
                    input.setAttribute('autocomplete', 'off');
                }
            });
            
            if (typeof Livewire !== 'undefined') {
                Livewire.hook('morph.updated', ({ el }) => {
                    const newInputs = el.querySelectorAll('input, textarea, select');
                    newInputs.forEach(function(input) {
                        if (!input.hasAttribute('autocomplete')) {
                            input.setAttribute('autocomplete', 'off');
                        }
                    });
                });
            }
        });
    </script>

    <!-- Global Notification System -->
    <script>
        function showAlert(type, message) {
            const isSuccess = type === 'success';
                Swal.fire({
                    icon: type,
                    html: message,
                    position: 'center',
                showConfirmButton: !isSuccess,
                timer: isSuccess ? 5000 : null,
                timerProgressBar: isSuccess,
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                confirmButtonColor: '#4361ee',
                    customClass: {
                        icon: 'swal2-icon-large',
                        popup: 'swal2-popup-with-icon'
                    },
                didOpen: isSuccess ? (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                } : undefined
                });
        }

        // Livewire Alert Handler
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-alert', (data) => {
                const alertData = Array.isArray(data) ? data[0] : data;
                if (alertData && alertData.type && alertData.message) {
                    showAlert(alertData.type, alertData.message);
                }
            });
        });

        // Session-based notifications (Laravel flash messages → SweetAlert2)
        @foreach(['success', 'error', 'warning', 'info'] as $type)
            @if(session($type))
                showAlert('{{ $type }}', '{{ session($type) }}');
            @endif
        @endforeach

        // Global helper functions
        ['Success', 'Error', 'Warning', 'Info'].forEach(type => {
            window['global' + type] = (message) => showAlert(type.toLowerCase(), message);
        });

        // Global Print System
        window.globalPrint = function(templateUrl, options = {}) {
            const settings = {
                method: 'iframe',
                autoPrint: true,
                cleanup: true,
                timeout: 1000,
                ...options
            };
            
            if (settings.method === 'iframe') {
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = templateUrl;
                
                iframe.onload = function() {
                    if (settings.autoPrint) {
                        setTimeout(() => iframe.contentWindow.print(), 300);
                    }
                    if (settings.cleanup) {
                        setTimeout(() => document.body.removeChild(iframe), settings.timeout);
                    }
                };
                
                document.body.appendChild(iframe);
            } else {
                window.open(templateUrl, '_blank');
            }
        };
    </script>
    
    @yield('scripts')
</body>

</html>
