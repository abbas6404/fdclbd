@extends('layouts.app')

@section('title', 'About Us')

@section('page_header')
<div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="fw-bold mb-3">About Us</h1>
        <p class="lead mb-0">Learn more about our mission and the team behind this project.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-md-end mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">About</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
<!-- Our Story Section -->
<div class="card shadow-sm rounded-custom mb-5">
    <div class="card-body p-4 p-md-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://via.placeholder.com/600x400?text=Our+Story" alt="Our Story" class="img-fluid rounded-custom">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Our Story</h2>
                <p class="lead text-primary mb-4">Building a better way to manage user permissions</p>
                <p>We started with a simple idea: make role and permission management in Laravel applications easier and more intuitive. What began as an internal tool has evolved into a comprehensive solution that helps developers save time and build more secure applications.</p>
                <p>Our team of experienced developers has worked tirelessly to create a system that is both powerful and flexible, meeting the needs of projects of all sizes.</p>
                <div class="d-flex align-items-center mt-4">
                    <div class="me-4">
                        <h4 class="fw-bold text-primary mb-0">2018</h4>
                        <p class="text-muted mb-0">Founded</p>
                    </div>
                    <div class="me-4">
                        <h4 class="fw-bold text-primary mb-0">1000+</h4>
                        <p class="text-muted mb-0">Projects</p>
                    </div>
                    <div>
                        <h4 class="fw-bold text-primary mb-0">50+</h4>
                        <p class="text-muted mb-0">Team Members</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Our Mission Section -->
<div class="card shadow-sm rounded-custom bg-gradient-primary text-white mb-5">
    <div class="card-body p-4 p-md-5">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold mb-4">Our Mission</h2>
                <p class="lead mb-4">We're on a mission to simplify role and permission management in web applications, making it accessible to developers of all skill levels.</p>
                <p>By providing an intuitive interface and powerful backend, we help developers create more secure applications while saving time and reducing complexity. Our goal is to become the standard solution for access control in Laravel applications worldwide.</p>
            </div>
        </div>
    </div>
</div>

<!-- Our Team Section -->
<div class="card shadow-sm rounded-custom mb-5">
    <div class="card-body p-4 p-md-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Meet Our Team</h2>
            <p class="lead text-muted">The talented people behind our success</p>
        </div>
        
        <div class="row g-4">
            <!-- Team Member 1 -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <img src="https://via.placeholder.com/300x300?text=Team+Member" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-1">John Doe</h5>
                        <p class="text-primary mb-3">Founder & CEO</p>
                        <p class="text-muted small">10+ years of experience in web development and software engineering.</p>
                        <div class="d-flex justify-content-center mt-3">
                            <a href="#" class="text-secondary me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-secondary me-2"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-secondary"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Team Member 2 -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <img src="https://via.placeholder.com/300x300?text=Team+Member" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-1">Jane Smith</h5>
                        <p class="text-primary mb-3">Lead Developer</p>
                        <p class="text-muted small">Expert in Laravel and backend development with 8 years of experience.</p>
                        <div class="d-flex justify-content-center mt-3">
                            <a href="#" class="text-secondary me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-secondary me-2"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-secondary"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Team Member 3 -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <img src="https://via.placeholder.com/300x300?text=Team+Member" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-1">Michael Johnson</h5>
                        <p class="text-primary mb-3">UI/UX Designer</p>
                        <p class="text-muted small">Creative designer with a passion for creating intuitive user experiences.</p>
                        <div class="d-flex justify-content-center mt-3">
                            <a href="#" class="text-secondary me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-secondary me-2"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-secondary"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Team Member 4 -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <img src="https://via.placeholder.com/300x300?text=Team+Member" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-1">Emily Davis</h5>
                        <p class="text-primary mb-3">Customer Success</p>
                        <p class="text-muted small">Dedicated to ensuring our customers get the most out of our products.</p>
                        <div class="d-flex justify-content-center mt-3">
                            <a href="#" class="text-secondary me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-secondary me-2"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-secondary"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="card shadow-sm rounded-custom bg-light">
    <div class="card-body p-4 p-md-5 text-center">
        <h2 class="fw-bold mb-4">Ready to get started?</h2>
        <p class="lead mb-4">Join thousands of developers who are already using our solution.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">Sign Up Now</a>
            <a href="{{ route('contact') }}" class="btn btn-outline-secondary btn-lg px-5">Contact Us</a>
        </div>
    </div>
</div>
@endsection 