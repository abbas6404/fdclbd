@permission('system.dashboard')
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand p-0" style="background: linear-gradient(135deg,rgba(97, 255, 97, 0.14) 20%,rgba(200, 255, 205, 0.25) 100%); border-bottom-left-radius: 18px; border-bottom-right-radius: 18px;">
        <div class="sidebar-logo d-flex align-items-center justify-content-center" style="height: 120px;">
            @if(file_exists(public_path('images/logo.png')))
                <img class="fw-bold px-2" src="{{ asset('images/logo.png') }}" alt="FDCL BD Logo" style="height: 120px; width: auto; margin:auto; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.20));">
            @else
                <span class="fw-bold px-2 fs-3 text-white" style="text-shadow: 1px 1px 6px #26c6da;">FDCL BD</span>
            @endif
        </div>
    </div>
  
    <div class="sidebar-heading">MANAGEMENT</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"> 
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        @role('Super Admin|Admin')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.admin-dashboard') ? 'active' : '' }}" href="{{ route('admin.admin-dashboard') }}"> 
                <i class="fas fa-crown"></i>
                <span>Admin Dashboard</span>
            </a>
        </li>
        @endrole

       
        <!-- Projects Dropdown -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" 
               href="#projectsSubmenu" 
               role="button" 
               aria-expanded="{{ request()->routeIs('admin.projects.*') ? 'true' : 'false' }}" 
               aria-controls="projectsSubmenu">
                <i class="fas fa-building"></i>
                <span>Projects</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.projects.*') ? 'show' : '' }}" id="projectsSubmenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.projects.index') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
                            <i class="fas fa-list"></i>
                            <span>Project List</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.projects.create') ? 'active' : '' }}" href="{{ route('admin.projects.create') }}">
                            <i class="fas fa-plus"></i>
                            <span>Add Project</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Project Flat Dropdown -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.flat.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" 
               href="#projectFlatSubmenu" 
               role="button" 
               aria-expanded="{{ request()->routeIs('admin.flat.*') ? 'true' : 'false' }}" 
               aria-controls="projectFlatSubmenu">
                <i class="fas fa-home"></i>
                <span>Flats</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.flat.*') ? 'show' : '' }}" id="projectFlatSubmenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.flat.index') ? 'active' : '' }}" href="{{ route('admin.flat.index') }}">
                            <i class="fas fa-list"></i>
                            <span>Flat List</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.flat.create') ? 'active' : '' }}" href="{{ route('admin.flat.create') }}">
                            <i class="fas fa-plus"></i>
                            <span>Add Flat</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Flat Sales -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.flat-sales.*') ? 'active' : '' }}" href="{{ route('admin.flat-sales.index') }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Flat Sales</span>
            </a>
        </li>

        <!-- Payment Schedule -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.payment-schedules.*') ? 'active' : '' }}" href="{{ route('admin.payment-schedules.index') }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Payment Schedule</span>
            </a>
        </li>

        <!-- Payment Receive -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.payment-receive.*') ? 'active' : '' }}" href="{{ route('admin.payment-receive.index') }}">
                <i class="fas fa-money-bill-wave"></i>
                <span>Payment Receive</span>
            </a>
        </li>

        <!-- Cheque Management -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.cheque-management.*') ? 'active' : '' }}" href="{{ route('admin.cheque-management.index') }}">
                <i class="fas fa-money-check-alt"></i>
                <span>Cheque Management</span>
            </a>
        </li>

        <!-- Purchase Requisition Dropdown -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.requisitions.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" 
               href="#requisitionsSubmenu" 
               role="button" 
               aria-expanded="{{ request()->routeIs('admin.requisitions.*') ? 'true' : 'false' }}" 
               aria-controls="requisitionsSubmenu">
                <i class="fas fa-shopping-bag"></i>
                <span>Purchase Requisition</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.requisitions.*') ? 'show' : '' }}" id="requisitionsSubmenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.requisitions.index') ? 'active' : '' }}" href="{{ route('admin.requisitions.index') }}">
                            <i class="fas fa-plus"></i>
                            <span>Create Requisition</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.requisitions.confirm') ? 'active' : '' }}" href="{{ route('admin.requisitions.confirm') }}">
                            <i class="fas fa-check-circle"></i>
                            <span>Confirm Requisition</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Account Entry -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}" href="{{ route('admin.accounts.index') }}">
                <i class="fas fa-book"></i>
                <span>Account Entry</span>
            </a>
        </li>

        <!-- Customers Dropdown -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" 
               href="#customersSubmenu" 
               role="button" 
               aria-expanded="{{ request()->routeIs('admin.customers.*') ? 'true' : 'false' }}" 
               aria-controls="customersSubmenu">
                <i class="fas fa-users"></i>
                <span>Customers</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.customers.*') ? 'show' : '' }}" id="customersSubmenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.customers.index') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                            <i class="fas fa-list"></i>
                            <span>Customer List</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.customers.create') ? 'active' : '' }}" href="{{ route('admin.customers.create') }}">
                            <i class="fas fa-plus"></i>
                            <span>Add Customer</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Sales Agents Dropdown -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.sales-agents.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" 
               href="#salesAgentsSubmenu" 
               role="button" 
               aria-expanded="{{ request()->routeIs('admin.sales-agents.*') ? 'true' : 'false' }}" 
               aria-controls="salesAgentsSubmenu">
                <i class="fas fa-user-tie"></i>
                <span>Sales Agents</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.sales-agents.*') ? 'show' : '' }}" id="salesAgentsSubmenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.sales-agents.index') ? 'active' : '' }}" href="{{ route('admin.sales-agents.index') }}">
                            <i class="fas fa-list"></i>
                            <span>Agent List</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.sales-agents.create') ? 'active' : '' }}" href="{{ route('admin.sales-agents.create') }}">
                            <i class="fas fa-plus"></i>
                            <span>Add Agent</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Suppliers Dropdown -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.supplier.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" 
               href="#suppliersSubmenu" 
               role="button" 
               aria-expanded="{{ request()->routeIs('admin.supplier.*') ? 'true' : 'false' }}" 
               aria-controls="suppliersSubmenu">
                <i class="fas fa-truck"></i>
                <span>Suppliers</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.supplier.*') ? 'show' : '' }}" id="suppliersSubmenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.supplier.index') ? 'active' : '' }}" href="{{ route('admin.supplier.index') }}">
                            <i class="fas fa-list"></i>
                            <span>Supplier List</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.supplier.create') ? 'active' : '' }}" href="{{ route('admin.supplier.create') }}">
                            <i class="fas fa-plus"></i>
                            <span>Add Supplier</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Contractors Dropdown -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.contractors.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" 
               href="#contractorsSubmenu" 
               role="button" 
               aria-expanded="{{ request()->routeIs('admin.contractors.*') ? 'true' : 'false' }}" 
               aria-controls="contractorsSubmenu">
                <i class="fas fa-hard-hat"></i>
                <span>Contractors</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.contractors.*') ? 'show' : '' }}" id="contractorsSubmenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.contractors.index') ? 'active' : '' }}" href="{{ route('admin.contractors.index') }}">
                            <i class="fas fa-list"></i>
                            <span>Contractor List</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.contractors.create') ? 'active' : '' }}" href="{{ route('admin.contractors.create') }}">
                            <i class="fas fa-plus"></i>
                            <span>Add Contractor</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        
        



        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.admin-users.*') ? 'active' : '' }}" href="{{ route('admin.admin-users.index') }}">
                <i class="fas fa-user-shield"></i>
                <span>Admin Users</span>
            </a>
        </li>

         <!-- Reports -->
         <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
        </li>

        @permission('setup.dashboard')
        <!-- Setup -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.setup.*') ? 'active' : '' }}" href="{{ route('admin.setup.index') }}">
                <i class="fas fa-cogs"></i>
                <span>Setup</span>
            </a>
        </li>
        @endpermission

        
    </ul>



    <div class="sidebar-footer">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
@else
<!-- Access Denied Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo">
            <div class="logo-circle">
                <i class="fas fa-lock"></i>
            </div>
            <span class="fw-bold px-2">Access Denied</span>
        </div>
    </div>
    
    <div class="sidebar-body text-center py-5">
        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
        <h6 class="text-warning">Permission Required</h6>
        <p class="text-muted small">You need system.dashboard permission to access this area.</p>
        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Back to Login</a>
    </div>
</div>
@endpermission 