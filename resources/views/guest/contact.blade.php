@extends('layouts.app')

@section('title', 'Contact Us')

@section('page_header')
<div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="fw-bold mb-3">Contact Us</h1>
        <p class="lead mb-0">Get in touch with our team. We'd love to hear from you!</p>
    </div>
    <div class="col-md-6 text-md-end">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-md-end mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Contact</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
<div class="row g-4">
    <!-- Contact Form Card -->
    <div class="col-lg-8">
        <div class="card shadow-sm rounded-custom mb-4 mb-lg-0">
            <div class="card-body p-4 p-md-5">
                <h2 class="fw-bold mb-4">Send Us a Message</h2>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Your Name" value="{{ old('name') }}" required>
                                <label for="name">Your Name</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required>
                                <label for="email">Your Email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" placeholder="Subject" value="{{ old('subject') }}" required>
                                <label for="subject">Subject</label>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" placeholder="Your Message" style="height: 200px" required>{{ old('message') }}</textarea>
                                <label for="message">Your Message</label>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i> Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Contact Info Card -->
    <div class="col-lg-4">
        <div class="card shadow-sm rounded-custom bg-gradient-primary text-white mb-4">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-4">Contact Information</h3>
                
                <div class="d-flex mb-4">
                    <div class="contact-icon me-3">
                        <i class="fas fa-map-marker-alt fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Our Location</h5>
                        <p class="mb-0">123 Street Name<br>City, ST 12345<br>United States</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="contact-icon me-3">
                        <i class="fas fa-phone fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Phone Number</h5>
                        <p class="mb-0">(123) 456-7890</p>
                        <p class="mb-0">(987) 654-3210</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="contact-icon me-3">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Email Address</h5>
                        <p class="mb-0">info@example.com</p>
                        <p class="mb-0">support@example.com</p>
                    </div>
                </div>
                
                <div class="d-flex">
                    <div class="contact-icon me-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Working Hours</h5>
                        <p class="mb-0">Monday - Friday: 9:00 AM - 5:00 PM</p>
                        <p class="mb-0">Saturday - Sunday: Closed</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm rounded-custom">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-4">Connect With Us</h3>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-primary btn-lg rounded-circle">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-lg rounded-circle">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-lg rounded-circle">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-lg rounded-circle">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Section -->
<div class="card shadow-sm rounded-custom mt-5">
    <div class="card-body p-0">
        <div class="ratio ratio-21x9">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387193.30594994064!2d-74.25986652425023!3d40.697149422113014!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2s!4v1645125437207!5m2!1sen!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</div>

<!-- FAQs Section -->
<div class="card shadow-sm rounded-custom mt-5">
    <div class="card-body p-4 p-md-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Frequently Asked Questions</h2>
            <p class="lead text-muted">Find answers to common questions about our services</p>
        </div>
        
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item border-0 mb-3 shadow-sm">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        How do I get started with your service?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Getting started is easy! Simply create an account by clicking the "Register" button in the top navigation, and follow the setup instructions. If you need any assistance, our support team is always here to help.
                    </div>
                </div>
            </div>
            
            <div class="accordion-item border-0 mb-3 shadow-sm">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Do you offer custom solutions for specific needs?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, we offer custom solutions tailored to your specific requirements. Please contact our team with details about your project, and we'll work with you to create a customized solution that meets your needs.
                    </div>
                </div>
            </div>
            
            <div class="accordion-item border-0 shadow-sm">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        What kind of support do you provide?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        We provide comprehensive support including documentation, video tutorials, email support, and live chat assistance. Our support team is available during business hours to help you with any questions or issues you may encounter.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .contact-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: rgba(78, 115, 223, 0.1);
        color: var(--primary-color);
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(78, 115, 223, 0.1);
    }
</style>
@endpush 