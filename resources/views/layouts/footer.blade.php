<footer class="py-5 mt-auto">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-shield-alt text-primary me-2 fa-2x"></i>
                    <h5 class="mb-0 fw-bold">{{ config('app.name', 'Laravel') }}</h5>
                </div>
                <p class="text-muted">A powerful role and permission management system for Laravel applications. Easily control access to your application features.</p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="text-primary"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="text-primary"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-primary"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    <a href="#" class="text-primary"><i class="fab fa-github fa-lg"></i></a>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3 mb-md-0">
                <h6 class="fw-bold mb-4">Quick Links</h6>
                <ul class="list-unstyled">
                    @auth
                        <li class="mb-2"><a href="{{ route('dashboard') }}" class="text-decoration-none text-secondary">Dashboard</a></li>
                    @else
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-decoration-none text-secondary">Login</a></li>
                    @endauth
                </ul>
            </div>
            <div class="col-md-2 col-sm-6 mb-3 mb-md-0">
                <h6 class="fw-bold mb-4">Resources</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Documentation</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Blog</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Support</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">FAQ</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold mb-4">Contact Us</h6>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex">
                        <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
                        <span>123 Street Name, City, ST 12345</span>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="fas fa-envelope text-primary me-3 mt-1"></i>
                        <span>info@example.com</span>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="fas fa-phone text-primary me-3 mt-1"></i>
                        <span>(123) 456-7890</span>
                    </li>
                </ul>
                <div class="mt-4">
                    <h6 class="fw-bold mb-3">Subscribe to our newsletter</h6>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your email">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 text-muted">
                    <a href="#" class="text-decoration-none text-secondary me-3">Privacy Policy</a>
                    <a href="#" class="text-decoration-none text-secondary me-3">Terms of Service</a>
                    <a href="#" class="text-decoration-none text-secondary">Cookies Policy</a>
                </p>
            </div>
        </div>
    </div>
</footer> 