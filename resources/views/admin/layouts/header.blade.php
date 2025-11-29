<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg admin-navbar">
    <div class="container-fluid">
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0 me-3" type="button" id="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
        
       
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <i class="fas fa-ellipsis-v"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Page Title -->
            <div class="ms-3 d-none d-lg-block">
                <h5 class="mb-0 fw-bold">@yield('title')</h5>
            </div>
            
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto align-items-center">
                
             
                
                <!-- Notifications -->
                <li class="nav-item dropdown me-2">
                    <a id="notificationsDropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="notification-icon">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-0 overflow-hidden" aria-labelledby="notificationsDropdown" style="width: 320px;">
                        <div class="p-3 border-bottom bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">Notifications</h6>
                                <a href="#" class="text-white small opacity-75">Mark all as read</a>
                            </div>
                        </div>
                        <div class="p-2">
                            <a href="#" class="dropdown-item p-2 rounded-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3 p-2 rounded-circle text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(45deg, var(--primary-color), var(--primary-light)); width: 45px; height: 45px;">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-medium">New user registered</p>
                                        <div class="text-muted small d-flex align-items-center">
                                            <i class="far fa-clock me-1"></i> 5 minutes ago
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item p-2 rounded-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3 p-2 rounded-circle text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(45deg, var(--success-color), var(--info-color)); width: 45px; height: 45px;">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-medium">Permission updated</p>
                                        <div class="text-muted small d-flex align-items-center">
                                            <i class="far fa-clock me-1"></i> 1 hour ago
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item p-2 rounded-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3 p-2 rounded-circle text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(45deg, var(--warning-color), var(--danger-color)); width: 45px; height: 45px;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-medium">System alert</p>
                                        <div class="text-muted small d-flex align-items-center">
                                            <i class="far fa-clock me-1"></i> Yesterday
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <a href="#" class="dropdown-item text-center border-top py-3 fw-medium text-primary">
                            View all notifications <i class="fas fa-chevron-right ms-1 small"></i>
                        </a>
                    </div>
                </li>
                
                <!-- User Profile -->
                <li class="nav-item dropdown profile-dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="d-none d-md-flex flex-column ms-1 me-1">
                                <span class="fw-medium text-dark" style="font-size: 14px; line-height: 1.2">{{ Auth::user()->name }}</span>
                                <small class="text-muted" style="font-size: 12px; line-height: 1">
                                    @if(Auth::user()->roles->isNotEmpty())
                                        {{ Auth::user()->roles->first()->name }}
                                    @else
                                        User
                                    @endif
                                </small>
                            </div>
                            <i class="fas fa-chevron-down d-none d-md-block ms-1 text-muted" style="font-size: 12px;"></i>
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <div class="profile-header text-center">
                            <div class="avatar-lg mx-auto mb-3">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                            <p class="mb-0 text-muted">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="p-1">
                            <a class="dropdown-item rounded-3" href="{{ route('admin.profile.index') }}">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                            <a class="dropdown-item rounded-3" href="{{ route('admin.profile.password') }}">
                                <i class="fas fa-cog"></i> Account Settings
                            </a>
                            <a class="dropdown-item rounded-3" href="#">
                                <i class="fas fa-list"></i> Activity Log
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item rounded-3" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav> 