@extends('layouts.app')

@section('title', 'Home')

@push('styles')
<style>
    /* Hero Section Styles */
    .hero-section {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 6rem 0;
        margin-bottom: 4rem;
        border-radius: 0 0 2rem 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(30deg);
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }
    
    .hero-image {
        transform: perspective(1000px) rotateY(-15deg);
        transition: transform 0.5s ease;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    }
    
    .hero-image:hover {
        transform: perspective(1000px) rotateY(0deg);
    }
    
    /* Feature Section Styles */
    .feature-icon {
        font-size: 2.5rem;
        height: 80px;
        width: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.1) 0%, rgba(78, 115, 223, 0.2) 100%);
        color: var(--primary-color);
        margin: 0 auto 1.5rem;
        transition: all 0.3s ease;
    }
    
    .feature-card:hover .feature-icon {
        transform: translateY(-10px);
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.2) 0%, rgba(78, 115, 223, 0.4) 100%);
    }
    
    .feature-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        padding: 2rem;
        height: 100%;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
    }
    
    /* Stats Section */
    .stats-card {
        border: none;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        height: 100%;
        background: #fff;
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
    }
    
    .stats-number {
        font-size: 3rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    
    /* Testimonial Section */
    .testimonial-card {
        border: none;
        border-radius: 1rem;
        padding: 2rem;
        height: 100%;
        position: relative;
    }
    
    .testimonial-card::before {
        content: '"';
        position: absolute;
        top: 1rem;
        left: 2rem;
        font-size: 5rem;
        color: rgba(78, 115, 223, 0.1);
        font-family: serif;
        line-height: 1;
    }
    
    .testimonial-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    /* How It Works Section */
    .step-card {
        border: none;
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
    }
    
    .step-number {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.5rem;
        font-weight: 700;
        margin-right: 1rem;
    }
    
    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.05) 0%, rgba(78, 115, 223, 0.1) 100%);
        border-radius: 1rem;
        padding: 4rem 2rem;
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(34, 74, 190, 0.4);
        color: white;
    }
    
    /* Animations */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">Powerful Role & Permission Management</h1>
                <p class="hero-subtitle">Take control of your application's security with our intuitive and flexible role-based access control system.</p>
                <div class="d-grid gap-3 d-md-flex justify-content-md-start mb-4">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 fw-bold">Get Started</a>
                    @endif
                    <a href="#features" class="btn btn-outline-light btn-lg px-4">Explore Features</a>
                </div>
                <div class="d-flex align-items-center">
                    <div class="d-flex me-4">
                        <div class="me-n3">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle border border-2 border-white" width="40" height="40" alt="User">
                        </div>
                        <div class="me-n3">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" class="rounded-circle border border-2 border-white" width="40" height="40" alt="User">
                        </div>
                        <div class="me-n3">
                            <img src="https://randomuser.me/api/portraits/men/67.jpg" class="rounded-circle border border-2 border-white" width="40" height="40" alt="User">
                        </div>
                        <div>
                            <img src="https://randomuser.me/api/portraits/women/21.jpg" class="rounded-circle border border-2 border-white" width="40" height="40" alt="User">
                        </div>
                    </div>
                    <div class="text-white-50">
                        <small>Trusted by 1000+ developers</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="https://via.placeholder.com/600x400?text=Role+Management" alt="Role Management" class="img-fluid rounded-4 shadow-lg hero-image float-animation mt-4 mt-lg-0">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 mb-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="stats-card shadow-sm">
                    <div class="stats-number">1000+</div>
                    <p class="text-muted mb-0">Active Users</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card shadow-sm">
                    <div class="stats-number">50+</div>
                    <p class="text-muted mb-0">Predefined Roles</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card shadow-sm">
                    <div class="stats-number">99.9%</div>
                    <p class="text-muted mb-0">Uptime</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card shadow-sm">
                    <div class="stats-number">24/7</div>
                    <p class="text-muted mb-0">Support</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5" id="features">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">Features</span>
            <h2 class="fw-bold mb-3">Everything You Need for Access Control</h2>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">Our comprehensive role and permission management system provides all the tools you need to secure your application.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3 class="h4 mb-3">Role-Based Access Control</h3>
                    <p class="text-muted mb-0">Create and manage roles like Super Admin, Admin, Moderator, and User with different access levels.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h3 class="h4 mb-3">Granular Permissions</h3>
                    <p class="text-muted mb-0">Define specific permissions for each action and assign them to roles for fine-grained access control.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="h4 mb-3">Secure Routes & UI</h3>
                    <p class="text-muted mb-0">Protect routes and conditionally render UI components based on user roles and permissions.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3 class="h4 mb-3">User Management</h3>
                    <p class="text-muted mb-0">Easily assign and change roles for users through an intuitive admin interface.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 class="h4 mb-3">Activity Logging</h3>
                    <p class="text-muted mb-0">Track all permission-related actions with detailed logs for security auditing.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="h4 mb-3">Easy Integration</h3>
                    <p class="text-muted mb-0">Seamlessly integrates with existing Laravel applications with minimal configuration.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">How It Works</span>
            <h2 class="fw-bold mb-3">Simple and Effective Role Management</h2>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">Our system is designed to be intuitive and easy to use, with just three simple steps.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="step-number">1</div>
                            <h3 class="h5 mb-0">Create Roles</h3>
                        </div>
                        <p class="text-muted mb-0">Define roles that represent different user types in your application with varying levels of access and permissions.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <img src="https://via.placeholder.com/400x200?text=Create+Roles" class="img-fluid rounded" alt="Create Roles">
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="step-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="step-number">2</div>
                            <h3 class="h5 mb-0">Assign Permissions</h3>
                        </div>
                        <p class="text-muted mb-0">Create specific permissions for actions and features, then assign them to roles to control access rights.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <img src="https://via.placeholder.com/400x200?text=Assign+Permissions" class="img-fluid rounded" alt="Assign Permissions">
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="step-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="step-number">3</div>
                            <h3 class="h5 mb-0">Manage Users</h3>
                        </div>
                        <p class="text-muted mb-0">Assign roles to users and instantly control their access throughout your application with ease.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <img src="https://via.placeholder.com/400x200?text=Manage+Users" class="img-fluid rounded" alt="Manage Users">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">Testimonials</span>
            <h2 class="fw-bold mb-3">What Our Users Say</h2>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">Hear from developers who have transformed their applications with our role management system.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card shadow-sm h-100">
                    <div class="mb-4">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-0">"This role management system has saved me countless hours of development time. The integration was seamless and the flexibility is exactly what I needed."</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="testimonial-avatar me-3">
                        <div>
                            <h5 class="mb-0">John Smith</h5>
                            <p class="text-muted small mb-0">Senior Developer</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card shadow-sm h-100">
                    <div class="mb-4">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-0">"The granular permission system is exactly what our enterprise application needed. We can now provide different access levels to our clients with confidence."</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="testimonial-avatar me-3">
                        <div>
                            <h5 class="mb-0">Sarah Johnson</h5>
                            <p class="text-muted small mb-0">CTO, TechCorp</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card shadow-sm h-100">
                    <div class="mb-4">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-0">"The documentation is excellent and the support team is responsive. I was able to implement complex permission structures in just a few hours."</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="User" class="testimonial-avatar me-3">
                        <div>
                            <h5 class="mb-0">Michael Chen</h5>
                            <p class="text-muted small mb-0">Full Stack Developer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="cta-section text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-4">Ready to Transform Your Application?</h2>
                    <p class="lead mb-4">Join thousands of developers who have already enhanced their Laravel applications with our role and permission management system.</p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-gradient btn-lg px-5 py-3 fw-bold">Get Started Now</a>
                        @endif
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-5 py-3">Sign In</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 