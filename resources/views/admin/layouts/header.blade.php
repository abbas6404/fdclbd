<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg admin-navbar">
    <div class="container-fluid">
        <!-- Mobile: Hamburger Menu Button -->
        <button class="navbar-toggler border-0 d-lg-none" type="button" id="sidebar-toggle" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Desktop: Page Title -->
        <div class="ms-3 d-none d-lg-block">
            <h5 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h5>
        </div>
        
        <!-- Desktop: Page-Specific Button -->
        <div class="flex-grow-1 text-center d-none d-lg-block" id="header-action-button">
            @stack('header_button')
        </div>
        
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ms-auto align-items-center">
            <!-- User Profile / Account -->
            <li class="nav-item dropdown profile-dropdown">
                <a id="navbarDropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="account-icon-wrapper">
                        <i class="fas fa-user-circle"></i>
                    </div>
                </a>

                <!-- Backdrop for mobile -->
                <div class="profile-dropdown-backdrop"></div>

                <div class="dropdown-menu dropdown-menu-end profile-dropdown-menu" aria-labelledby="navbarDropdown">
                    <div class="profile-header text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                        <p class="mb-0 text-muted">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="p-1">
                        <a class="dropdown-item rounded-3" href="{{ route('admin.profile.index') }}">
                            <i class="fas fa-user me-2"></i> My Profile
                        </a>
                        <a class="dropdown-item rounded-3" href="{{ route('admin.profile.password') }}">
                            <i class="fas fa-key me-2"></i> Change Password
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item rounded-3 text-danger" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav> 