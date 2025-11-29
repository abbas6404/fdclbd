@extends('admin.layouts.app')

@section('title', 'Reports Dashboard')

@php
    // Helper function to get menu item attributes
    function getMenuItemAttributes($menu, $selectedMenu, $activeClass) {
        $attributes = 'class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 ' . $activeClass . '"';
        
        // Check if menu has children
        $hasChildren = $menu->children()->where('status', 'active')->count() > 0;
        
        if ($hasChildren) {
            $attributes .= ' title="Left click: Navigate | Right click: 📅 Date Range"';
        } else {
            $attributes .= ' title="Click: 📅 Date Range"';
        }
        
        return $attributes;
    }
    
    // Helper function to get menu icon
    function getMenuIcon($menu, $defaultIcon, $defaultColor) {
        if ($menu->children()->where('status', 'active')->count() > 0) {
            return '<i class="fas fa-chevron-right text-muted small"></i>';
        } else {
            return '<i class="fas fa-external-link-alt text-success small"></i>';
        }
    }
@endphp

@section('content')
<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reports Dashboard</h1>
        <div class="d-flex">
            <span class="badge bg-primary fs-6">
                <i class="fas fa-chart-bar me-2"></i>
                Reports System
            </span>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Reports Navigation -->
    <div class="row">
        <!-- Column 1 - Root Menus (No Routes) -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-folder-open me-2 text-primary"></i>
                        Level 1
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if($rootMenus->count() > 0)
                            @foreach($rootMenus as $rootMenu)
                            @php
                                $isActive = $selectedMenu && ($selectedMenu->id == $rootMenu->id || 
                                    ($selectedMenu->parent && $selectedMenu->parent->id == $rootMenu->id) || 
                                    ($selectedMenu->parent && $selectedMenu->parent->parent && $selectedMenu->parent->parent->id == $rootMenu->id));
                                $activeClass = $isActive ? 'active bg-primary text-white' : '';
                                $iconClass = $rootMenu->name == 'Income Report' ? 'chart-line' : ($rootMenu->name == 'Expense Report' ? 'chart-bar' : ($rootMenu->name == 'Sales Report' ? 'shopping-cart' : 'dollar-sign'));
                                $iconColor = $isActive ? 'white' : ($rootMenu->name == 'Income Report' ? 'success' : ($rootMenu->name == 'Expense Report' ? 'danger' : ($rootMenu->name == 'Sales Report' ? 'info' : 'warning')));
                                $hasChildren = $rootMenu->children()->where('status', 'active')->count() > 0;
                            @endphp
                            <li {!! getMenuItemAttributes($rootMenu, $selectedMenu, $activeClass) !!}>
                                @if($hasChildren)
                                    <a href="{{ route('admin.reports.index', ['menu_id' => $rootMenu->id]) }}" 
                                       class="text-decoration-none w-100 d-flex justify-content-between align-items-center"
                                       oncontextmenu="event.preventDefault(); openDateRangeModal('{{ $rootMenu->route }}'); return false;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-{{ $iconClass }} me-2 text-{{ $iconColor }}"></i>
                                            <span class="fw-semibold small">{{ $rootMenu->name }}</span>
                                        </div>
                                        {!! getMenuIcon($rootMenu, 'chevron-right', 'muted') !!}
                                    </a>
                                @else
                                    @if($rootMenu->route && !empty($rootMenu->route))
                                        <a href="{{ route('admin.reports.index', ['menu_id' => $rootMenu->id]) }}" 
                                           class="text-decoration-none w-100 d-flex justify-content-between align-items-center"
                                           onclick="event.preventDefault(); openDateRangeModal('{{ $rootMenu->route }}'); return false;"
                                           oncontextmenu="event.preventDefault(); openDateRangeModal('{{ $rootMenu->route }}'); return false;">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-{{ $iconClass }} me-2 text-{{ $iconColor }}"></i>
                                                <span class="fw-semibold small">{{ $rootMenu->name }}</span>
                                            </div>
                                            {!! getMenuIcon($rootMenu, 'chevron-right', 'muted') !!}
                                        </a>
                                    @else
                                        <a href="{{ route('admin.reports.index', ['menu_id' => $rootMenu->id]) }}" 
                                           class="text-decoration-none w-100 d-flex justify-content-between align-items-center"
                                           onclick="event.preventDefault(); showNoReportMessage(); return false;">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-{{ $iconClass }} me-2 text-{{ $iconColor }}"></i>
                                                <span class="fw-semibold small">{{ $rootMenu->name }}</span>
                                            </div>
                                            <i class="fas fa-chevron-right text-muted small"></i>
                                        </a>
                                    @endif
                                @endif
                            </li>
                            @endforeach
                        @else
                            <li class="text-center text-muted py-4">
                                <i class="fas fa-exclamation-triangle fa-2x mb-3 text-warning"></i>
                                <p class="mb-0 small">No categories available</p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Column 2 - Sub Menus (Level 1 Children) -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-list me-2 text-info"></i>
                        Level 2
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if($subMenus->count() > 0)
                            @foreach($subMenus as $subMenu)
                            @php
                                $isActive = $selectedMenu && ($selectedMenu->id == $subMenu->id || ($selectedMenu->parent && $selectedMenu->parent->id == $subMenu->id));
                                $activeClass = $isActive ? 'active bg-info text-white' : '';
                                $iconColor = $isActive ? 'white' : 'info';
                                $hasChildren = $subMenu->children()->where('status', 'active')->count() > 0;
                            @endphp
                            <li {!! getMenuItemAttributes($subMenu, $selectedMenu, $activeClass) !!}>
                                @if($hasChildren)
                                    <a href="{{ route('admin.reports.index', ['menu_id' => $subMenu->id]) }}" 
                                       class="text-decoration-none w-100 d-flex justify-content-between align-items-center"
                                       oncontextmenu="event.preventDefault(); openDateRangeModal('{{ $subMenu->route }}'); return false;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-cog me-2 text-{{ $iconColor }}"></i>
                                            <span class="fw-semibold small">{{ $subMenu->name }}</span>
                                        </div>
                                        {!! getMenuIcon($subMenu, 'chevron-right', 'muted') !!}
                                    </a>
                                @else
                                    @if($subMenu->route && !empty($subMenu->route))
                                        <a href="{{ route('admin.reports.index', ['menu_id' => $subMenu->id]) }}" 
                                           class="text-decoration-none w-100 d-flex justify-content-between align-items-center"
                                           onclick="event.preventDefault(); openDateRangeModal('{{ $subMenu->route }}'); return false;"
                                           oncontextmenu="event.preventDefault(); openDateRangeModal('{{ $subMenu->route }}'); return false;">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-cog me-2 text-{{ $iconColor }}"></i>
                                                <span class="fw-semibold small">{{ $subMenu->name }}</span>
                                            </div>
                                            {!! getMenuIcon($subMenu, 'chevron-right', 'muted') !!}
                                        </a>
                                    @else
                                        <a href="{{ route('admin.reports.index', ['menu_id' => $subMenu->id]) }}" 
                                           class="text-decoration-none w-100 d-flex justify-content-between align-items-center"
                                           onclick="event.preventDefault(); showNoReportMessage(); return false;">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-cog me-2 text-{{ $iconColor }}"></i>
                                                <span class="fw-semibold small">{{ $subMenu->name }}</span>
                                            </div>
                                            <i class="fas fa-chevron-right text-muted small"></i>
                                        </a>
                                    @endif
                                @endif
                            </li>
                            @endforeach
                        @else
                            <li class="text-center text-muted py-4">
                                <i class="fas fa-hand-point-left fa-2x mb-3"></i>
                                <p class="mb-0 small">Select a category to view options</p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Column 3 - Sub Sub Menus (Level 2 Children) -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-list-alt me-2 text-warning"></i>
                        Level 3
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if($subSubMenus->count() > 0)
                            @foreach($subSubMenus as $subSubMenu)
                            @php
                                $isActive = $selectedMenu && $selectedMenu->id == $subSubMenu->id;
                                $activeClass = $isActive ? 'active bg-warning text-dark' : '';
                                $iconColor = $isActive ? 'dark' : 'warning';
                                $hasChildren = $subSubMenu->children()->where('status', 'active')->count() > 0;
                            @endphp
                            <li {!! getMenuItemAttributes($subSubMenu, $selectedMenu, $activeClass) !!}>
                                @if($hasChildren)
                                    <a href="{{ route('admin.reports.index', ['menu_id' => $subSubMenu->id]) }}" 
                                       class="text-decoration-none w-100 d-flex justify-content-between align-items-center"
                                       oncontextmenu="event.preventDefault(); openDateRangeModal('{{ $subSubMenu->route }}'); return false;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-circle me-2 text-{{ $iconColor }}"></i>
                                            <span class="fw-semibold small">{{ $subSubMenu->name }}</span>
                                        </div>
                                        {!! getMenuIcon($subSubMenu, 'chevron-right', 'muted') !!}
                                    </a>
                                @else
                                    @if($subSubMenu->route && !empty($subSubMenu->route))
                                        <a href="{{ route('admin.reports.index', ['menu_id' => $subSubMenu->id]) }}" 
                                           class="text-decoration-none w-100 d-flex justify-content-between align-items-center"
                                           onclick="event.preventDefault(); openDateRangeModal('{{ $subSubMenu->route }}'); return false;"
                                           oncontextmenu="event.preventDefault(); openDateRangeModal('{{ $subSubMenu->route }}'); return false;">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-circle me-2 text-{{ $iconColor }}"></i>
                                                <span class="fw-semibold small">{{ $subSubMenu->name }}</span>
                                            </div>
                                            {!! getMenuIcon($subSubMenu, 'chevron-right', 'muted') !!}
                                        </a>
                                    @else
                                        <a href="{{ route('admin.reports.index', ['menu_id' => $subSubMenu->id]) }}" 
                                           class="text-decoration-none w-100 d-flex justify-content-between align-items-center"
                                           onclick="event.preventDefault(); showNoReportMessage(); return false;">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-circle me-2 text-{{ $iconColor }}"></i>
                                                <span class="fw-semibold small">{{ $subSubMenu->name }}</span>
                                            </div>
                                            <i class="fas fa-chevron-right text-muted small"></i>
                                        </a>
                                    @endif
                                @endif
                            </li>
                            @endforeach
                        @else
                            <li class="text-center text-muted py-4">
                                <i class="fas fa-hand-point-left fa-2x mb-3"></i>
                                <p class="mb-0 small">Select a sub category to view options</p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Column 4 - Deep Menus (Level 3+ Children) -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-list-ul me-2 text-danger"></i>
                        Level 4
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if($deepMenus->count() > 0)
                            @foreach($deepMenus as $deepMenu)
                            @php
                                $isActive = $selectedMenu && $selectedMenu->id == $deepMenu->id;
                                $activeClass = $isActive ? 'active bg-danger text-white' : '';
                                $iconColor = $isActive ? 'white' : 'danger';
                            @endphp
                            <li {!! getMenuItemAttributes($deepMenu, $selectedMenu, $activeClass) !!}>
                                @if($deepMenu->route && !empty($deepMenu->route))
                                    <a href="{{ route($deepMenu->route) }}" 
                                       class="text-decoration-none w-100 d-flex justify-content-between align-items-center"
                                       onclick="event.preventDefault(); openDateRangeModal('{{ $deepMenu->route }}'); return false;"
                                       oncontextmenu="event.preventDefault(); openDateRangeModal('{{ $deepMenu->route }}'); return false;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star me-2 text-{{ $iconColor }}"></i>
                                            <span class="fw-semibold small">{{ $deepMenu->name }}</span>
                                        </div>
                                        {!! getMenuIcon($deepMenu, 'arrow-right', 'muted') !!}
                                    </a>
                                @else
                                    <a href="{{ route('admin.reports.index', ['menu_id' => $deepMenu->id]) }}" 
                                       class="text-decoration-none w-100 d-flex justify-content-between align-items-center"
                                       onclick="event.preventDefault(); showNoReportMessage(); return false;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star me-2 text-{{ $iconColor }}"></i>
                                            <span class="fw-semibold small">{{ $deepMenu->name }}</span>
                                        </div>
                                        <i class="fas fa-chevron-right text-muted small"></i>
                                    </a>
                                @endif
                            </li>
                            @endforeach
                        @else
                            <li class="text-center text-muted py-4">
                                <i class="fas fa-hand-point-left fa-2x mb-3"></i>
                                <p class="mb-0 small">Select a sub sub category to view options</p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Include Modals -->
@include('admin.reports.date-range-modal')

<!-- Custom CSS for menu system -->
<style>
/* Active menu item styling - consolidated */
.list-group-item.active { background-color: #e3f2fd !important; color: #1976d2 !important; }
.list-group-item[oncontextmenu]:hover { background-color: #f8f9fa !important; cursor: context-menu; }
.list-group-item[oncontextmenu]:hover .fas { transform: scale(1.1); transition: transform 0.2s ease; }
</style>

<!-- Add some JavaScript for better menu interaction -->
<script>
// Show message for menus without routes
function showNoReportMessage() {
    alert('No report generated, check next level');
}

// Enhanced right-click handler for report routes with date range
function openDateRangeModal(routeName) {
    if (!routeName || routeName.trim() === '') {
        showNoReportMessage();
        return;
    }
    
    // Open the date range modal
    const modal = new bootstrap.Modal(document.getElementById('dateRangeModal'));
    document.getElementById('dateRangeForm').setAttribute('data-route', routeName);
    
    // Set default dates (today for both start and end)
    const today = new Date();
    const todayString = today.toISOString().split('T')[0];
    
    document.getElementById('startDate').value = todayString;
    document.getElementById('endDate').value = todayString;
    
    modal.show();
}

// Generate report with date range
function generateReportWithDateRange() {
    const form = document.getElementById('dateRangeForm');
    const routeName = form.getAttribute('data-route');
    
    // Get dates from hidden fields
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // Validate dates
    if (!startDate || !endDate) {
        alert('Please select both start and end dates.');
        return;
    }
    
    if (new Date(startDate) > new Date(endDate)) {
        alert('Start date cannot be after end date.');
        return;
    }
    
    // Close modal first
    const modal = bootstrap.Modal.getInstance(document.getElementById('dateRangeModal'));
    modal.hide();
    
    // Open print dialog directly
    setTimeout(() => {
        openPrintDialog(startDate, endDate);
    }, 300);
}

// Open print dialog directly in same page
function openPrintDialog(startDate, endDate) {
    const form = document.getElementById('dateRangeForm');
    const routeName = form.getAttribute('data-route');
    
    if (!routeName || routeName.trim() === '') {
        alert('No route available for this report');
        return;
    }
    
    // Build the print page URL with date parameters
    const baseUrl = '{{ url("/") }}';
    
    // Convert route name to URL path
    // e.g., 'admin.reports.income.details' -> '/admin/reports/income/details-print'
    let routePath = routeName.replace('admin.', '/admin/').replace(/\./g, '/');
    
    // Ensure it ends with -print
    if (!routePath.endsWith('-print')) {
        routePath += '-print';
    }
    
    // Build URL with parameters
    let printUrl = `${baseUrl}${routePath}?start_date=${startDate}&end_date=${endDate}`;
    
    // Create a hidden iframe to load the print view
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = 'none';
    iframe.style.position = 'absolute';
    iframe.style.left = '-9999px';
    
    // Add iframe to body
    document.body.appendChild(iframe);
    
    // Load the print URL in iframe
    iframe.src = printUrl;
    
    // Listen for iframe load and trigger print once
    iframe.onload = function() {
        try {
            // Access the iframe's window and trigger print
            const iframeWindow = iframe.contentWindow;
            if (iframeWindow) {
                // Small delay to ensure content is fully loaded
                setTimeout(() => {
                    iframeWindow.print();
                }, 300);
            }
        } catch (e) {
            console.error('Error triggering print:', e);
            // Fallback: open in new window if iframe fails
            window.open(printUrl, '_blank');
        }
    };
    
    // Clean up iframe after print dialog is closed (or after timeout)
    setTimeout(() => {
        if (document.body.contains(iframe)) {
            document.body.removeChild(iframe);
        }
    }, 10000);
}

// Add visual indicators and hover effects for menu items
document.addEventListener('DOMContentLoaded', function() {
    // Add special styling for menu items with routes (date range support)
    const menuItemsWithRoutes = document.querySelectorAll('[oncontextmenu*="openDateRangeModal"]');
    menuItemsWithRoutes.forEach(item => {
        item.style.position = 'relative';
        
        // Only add calendar icon if the menu item has a route (not empty)
        const onclickAttr = item.getAttribute('onclick');
        if (onclickAttr && onclickAttr.includes('openDateRangeModal') && !onclickAttr.includes("''")) {
            // Add a small calendar icon indicator
            const indicator = document.createElement('i');
            indicator.className = 'fas fa-calendar-alt text-success small position-absolute';
            indicator.style.top = '5px';
            indicator.style.right = '25px';
            indicator.style.fontSize = '0.7em';
            indicator.title = 'Right-click for date range selection';
            item.appendChild(indicator);
        }
    });
    
    // Add hover effects for all menu items
    const allMenuItems = document.querySelectorAll('.list-group-item');
    allMenuItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.backgroundColor = 'rgba(13, 110, 253, 0.1)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.backgroundColor = '';
            }
        });
    });
});
</script>

@endsection
