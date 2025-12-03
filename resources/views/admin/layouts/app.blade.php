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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom styles -->
    <link href="{{ asset('css/admin-layout.css') }}" rel="stylesheet">
    

        
        @livewireStyles
        
        @yield('styles')
        @stack('styles')

        <!--
            The following CSS is optimized for 18-inch monitor displays.
            Adjust values as needed for other screen sizes.
        -->
        <style>
            /* Hide number input spinner arrows globally */
            input[type="number"]::-webkit-outer-spin-button,
            input[type="number"]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            input[type="number"] {
                -moz-appearance: textfield;
            }
            
            /* Responsive .form-control-sm for all sizes, but tweak for large screens */
            @media (max-width: 1200px) {
                .form-control-sm {
                    padding: 0.1rem 0.5rem !important;
                    font-size: 0.8rem !important;
                    height: auto !important;
                }
            }

            /* Livewire Component Styles */
            .table th {
                background-color:rgba(79, 173, 255, 0.76);
                color: white;
                border: none;
            }

            .table-hover tbody tr:hover {
                background-color: rgba(0, 123, 255, 0.1);
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            .badge {
                font-size: 0.8em;
                padding: 0.5em 0.75em;
            }

            .btn-sm {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }

            /* Enhanced Filter Styles */
            .card-header {
                border: none;
                border-radius: 8px 8px 0 0 !important;
            }

            .card-body {
                border-radius: 0 0 8px 8px !important;
            }

            .form-label {
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
                color: #495057;
            }

            .form-control, .form-select {
                border-radius: 6px;
                border: 1px solid #ced4da;
                transition: all 0.3s ease;
            }

            .form-control:focus, .form-select:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            }

            .input-group-text {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: 1px solid #667eea;
                border-radius: 6px 0 0 6px;
            }

            .quick-filter {
                border-radius: 20px;
                padding: 0.375rem 1rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .quick-filter:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            }

            .btn-sm {
                border-radius: 6px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .btn-sm:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0,0,0,0.15);
            }


            .form-group {
                position: relative;
            }

            /* Badge Enhancements */
            .badge {
                border-radius: 12px;
                padding: 0.5rem 0.75rem;
                font-weight: 500;
                font-size: 0.8rem;
            }

                    /* Alert Enhancements */
        .alert {
            border-radius: 8px;
            border: none;
        }

        /* Live Search Styles */
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 6px 6px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            max-height: 300px;
            overflow-y: auto;
            margin-top: -1px;
        }

        .search-suggestions .suggestion-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
        }

        .search-suggestions .suggestion-item:hover {
            background-color: #e3f2fd !important;
            transform: translateX(2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-suggestions .suggestion-item:last-child {
            border-bottom: none;
            border-radius: 0 0 6px 6px;
        }

        .search-suggestions .suggestion-text {
            font-weight: 600;
            color: #1976d2;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
        }

        .search-suggestions .suggestion-meta {
            font-size: 0.85rem;
            color: #666;
            margin-left: 24px;
            margin-top: 2px;
        }

        /* Ensure the form group has proper positioning context */
        .form-group.position-relative {
            position: relative !important;
        }

        /* Make sure search suggestions are above other elements */
        .search-suggestions {
            position: absolute !important;
            z-index: 9999 !important;
        }

            /* Responsive Design */
            @media (max-width: 768px) {
                .quick-filter {
                    font-size: 0.8rem;
                    padding: 0.25rem 0.75rem;
                }
                
                .form-label {
                    font-size: 0.85rem;
                }
                
                .search-suggestions {
                    max-height: 200px;
                }
            }
        </style>
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle-btn" id="sidebar-toggle-btn">
            <i class="fas fa-angle-left"></i>
        </button>

        <!-- Content Container -->
        <div class="content-container">
            <!-- Sidebar Overlay for mobile -->
            <div class="sidebar-overlay"></div>

            <!-- Top Navbar -->
            @include('admin.layouts.header')

            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>

            <!-- Footer -->
            @include('admin.layouts.footer')
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Scripts -->
    @livewireScripts
    
    <!-- Custom JavaScript -->
    <script src="{{ asset('js/admin-layout.js') }}?v=3.0"></script>
    
    <!-- Disable Autocomplete Globally -->
    <script>
        // Disable autocomplete for all input fields
        document.addEventListener('DOMContentLoaded', function() {
            // Set autocomplete off for all inputs
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(function(input) {
                if (!input.hasAttribute('autocomplete')) {
                    input.setAttribute('autocomplete', 'off');
                }
            });
            
            // Also disable for dynamically added inputs (Livewire)
            if (typeof Livewire !== 'undefined') {
                Livewire.hook('morph.updated', ({ el, component }) => {
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
        // Global alert function
        function showAlert(type, message) {
            if (type === 'success') {
                Swal.fire({
                    icon: type,
                    html: message,
                    position: 'center',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    customClass: {
                        icon: 'swal2-icon-large',
                        popup: 'swal2-popup-with-icon'
                    },
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            } else {
                Swal.fire({
                    icon: type,
                    html: message,
                    position: 'center',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4361ee',
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    customClass: {
                        icon: 'swal2-icon-large',
                        popup: 'swal2-popup-with-icon'
                    }
                });
            }
        }

        // Global Livewire Alert Handler
        document.addEventListener('livewire:init', () => {
            console.log('Livewire initialized, setting up alert handler...');
            
            Livewire.on('show-alert', (data) => {
                console.log('Livewire alert received:', data);
                
                // Fix: Extract data properly from array
                let alertData;
                if (Array.isArray(data)) {
                    alertData = data[0]; // Get first element if it's an array
                } else {
                    alertData = data; // Use as is if it's an object
                }
                
                const { type, message } = alertData;
                console.log('Extracted data:', { type, message });
                
                if (type === 'success') {
                    console.log('Showing success alert with message:', message);
                    Swal.fire({
                        icon: type,
                        html: message,
                        position: 'center',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        customClass: {
                            icon: 'swal2-icon-large',
                            popup: 'swal2-popup-with-icon'
                        },
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                } else {
                    console.log('Showing alert with message:', message);
                    Swal.fire({
                        icon: type,
                        html: message,
                        position: 'center',
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#4361ee',
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        customClass: {
                            icon: 'swal2-icon-large',
                            popup: 'swal2-popup-with-icon'
                        }
                    });
                }
            });
        });

        // Session-based notifications
        document.addEventListener('DOMContentLoaded', function() {
            // Check for session messages
            @if(session('success'))
                showAlert('success', '{{ session('success') }}');
            @endif

            @if(session('error'))
                showAlert('error', '{{ session('error') }}');
            @endif

            @if(session('warning'))
                showAlert('warning', '{{ session('warning') }}');
            @endif

            @if(session('info'))
                showAlert('info', '{{ session('info') }}');
            @endif
        });

        // Global helper functions
        window.globalSuccess = function(message) {
            showAlert('success', message);
        };

        window.globalError = function(message) {
            showAlert('error', message);
        };

        window.globalWarning = function(message) {
            showAlert('warning', message);
        };

        window.globalInfo = function(message) {
            showAlert('info', message);
        };

        // Global Print System
        window.globalPrint = function(templateUrl, options = {}) {
            console.log('Global print called:', templateUrl, options);
            const defaultOptions = { method: 'iframe', autoPrint: true, cleanup: true, timeout: 1000 };
            const settings = { ...defaultOptions, ...options };
            
            console.log('Print settings:', settings);
            
            if (settings.method === 'iframe') {
                console.log('Creating iframe for printing...');
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = templateUrl;
                
                iframe.onload = function() {
                    console.log('Iframe loaded, triggering print...');
                    if (settings.autoPrint) {
                        // Add a small delay to ensure the page is fully rendered
                        setTimeout(() => {
                            iframe.contentWindow.print();
                        }, 300);
                    }
                    
                    if (settings.cleanup) {
                        setTimeout(() => {
                            console.log('Cleaning up iframe...');
                            document.body.removeChild(iframe);
                        }, settings.timeout);
                    }
                };
                
                iframe.onerror = function(error) {
                    console.error('Iframe error:', error);
                };
                
                document.body.appendChild(iframe);
                console.log('Iframe added to document body');
            } else {
                console.log('Opening in new window...');
                window.open(templateUrl, '_blank');
            }
        };

        // Global Print Helper Functions
        window.printPaymentInvoice = function(invoiceId) {
            console.log('Print payment invoice function called for invoice:', invoiceId);
        };

        window.printPaymentSchedule = function(scheduleId) {
            console.log('Print payment schedule function called for schedule:', scheduleId);
        };

        window.printCustomTemplate = function(templateName, invoiceId) {
            console.log('Print function called for template:', templateName, 'invoice:', invoiceId);
        };

        // Livewire Print Event Handlers
        document.addEventListener('livewire:init', () => {
            Livewire.on('print-payment-invoice', (data) => {
                console.log('Print payment invoice event received:', data);
                if (data.invoice_id) {
                    printPaymentInvoice(data.invoice_id);
                }
            });

            Livewire.on('print-payment-schedule', (data) => {
                console.log('Print payment schedule event received:', data);
                if (data.schedule_id) {
                    printPaymentSchedule(data.schedule_id);
                }
            });

            Livewire.on('print-custom', (data) => {
                console.log('Print custom template event received:', data);
                if (data.template && data.invoice_id) {
                    printCustomTemplate(data.template, data.invoice_id);
                }
            });

            Livewire.on('print-with-options', (data) => {
                console.log('Print with options event received:', data);
                if (data.templateUrl) {
                    globalPrint(data.templateUrl, data.options || {});
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>

</html>
