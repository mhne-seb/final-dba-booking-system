<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoCare Vulcanizing Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-slate-50 antialiased">

    <header class="fixed w-full z-50 bg-slate-900/80 backdrop-blur-md border-b border-slate-800">
        <nav class="container mx-auto px-4 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center group">
                <img 
                    src="{{ asset('autocare_logo.png') }}" 
                    alt="AutoCare Logo" 
                    class="h-12 w-auto object-contain transition-transform group-hover:scale-110"
                >
            </a>

            <div class="hidden md:flex space-x-8 text-slate-300 text-sm">
                <a href="/#about" class="hover:text-white transition">About</a>
                <a href="/#services" class="hover:text-white transition">Services</a>
                <a href="/#contact" class="hover:text-white transition">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="/login" class="text-slate-300 hover:text-white text-sm font-medium transition px-2">Log in</a>
                <button onclick="toggleBookingModal()" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 text-sm">
                    Book Now
                </button>
            </div>
        </nav>
        <style>
            .form-container {
                max-height: 80vh; 
                overflow-y: auto; 
                padding: 16px; 
                background-color: #1e293b; 
                border: 1px solid black; 
                border-radius: 8px; 
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            }
            .form-container input[type="text"],
            .form-container input[type="email"],
            .form-container input[type="date"],
            .form-container input[type="time"],
            .form-container input[type="number"],
            .form-container select,
            .form-container textarea {
                background-color: #2d3748; /* Softer dark background */
                color: #e2e8f0; /* Light text color */
                border: 1px solid #4a5568; /* Subtle border */
                border-radius: 8px; /* Rounded corners */
                padding: 12px; /* Comfortable padding */
                font-size: 16px; /* Slightly larger font */
                transition: all 0.3s ease; /* Smooth transition for hover effects */
            }

            .form-container input[type="text"]:focus,
            .form-container input[type="email"]:focus,
            .form-container input[type="date"]:focus,
            .form-container input[type="time"]:focus,
            .form-container input[type="number"]:focus,
            .form-container select:focus,
            .form-container textarea:focus {
                border-color: #63b3ed; /* Blue border on focus */
                box-shadow: 0 0 8px rgba(99, 179, 237, 0.5); /* Subtle glow effect */
                outline: none; /* Remove default outline */
            }

            .form-container label {
                color: #a0aec0; /* Softer label color */
                font-weight: 500; /* Medium font weight */
            }

            .form-container textarea {
                resize: none; /* Prevent resizing */
            }

            .form-container button[type="submit"] {
                background-color: #3182ce; /* Blue background */
                color: #ffffff; /* White text */
                border: none;
                border-radius: 8px;
                padding: 12px 16px;
                font-size: 16px;
                font-weight: bold;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .form-container button[type="submit"]:hover {
                background-color: #2b6cb0; /* Darker blue on hover */
                transform: scale(1.05); /* Slight zoom effect */
            }

        </style>
    </header>

    <section class="relative min-h-screen flex items-center justify-center pt-16">
        <div class="absolute inset-0 bg-cover bg-center bg-fixed" style="background-image: url('hero-bg.jpg');">
            <div class="absolute inset-0 bg-slate-900/90 mix-blend-multiply"></div>
        </div>
        
        <div class="relative z-10 container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                Professional Auto Care &<br />
                <span class="text-sky-400">Vulcanizing Services</span>
            </h1>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Book your service appointment online and experience hassle-free auto care. 
                Quality service, trusted by thousands of vehicle owners.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="toggleBookingModal()" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-8 py-3 text-lg font-medium text-white shadow transition-all hover:scale-105 hover:bg-blue-700">
                    Book a Service
                </button>
                <a href="#services" class="inline-flex items-center justify-center rounded-md border border-white/20 bg-white/10 px-8 py-3 text-lg font-medium text-white transition-all hover:scale-105 hover:bg-white/20">
                    Our Services
                </a>
            </div>
        </div>
    </section>

    <section id="about" class="py-20 bg-slate-900">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">About AutoCare</h2>
                <p class="text-lg text-slate-300">
                    AutoCare Vulcanizing Shop is your trusted partner for all tire and wheel services. 
                    With years of experience and a commitment to excellence.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-4">Why Choose Us?</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach(['Online booking system', 'Real-time service tracking', 'Expert technicians', 'Competitive pricing', 'Customer satisfaction guaranteed', 'Modern equipment'] as $feature)
                            <div class="flex items-center gap-2 group">
                                <i data-lucide="check-circle-2" class="w-5 h-5 text-blue-500"></i>
                                <span class="text-slate-300 group-hover:text-white transition-colors">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-8 text-center transition-all hover:-translate-y-1">
                    <div class="mb-6">
                        <div class="text-5xl font-bold text-blue-500 mb-2">1000+</div>
                        <p class="text-slate-400">Happy Customers</p>
                    </div>
                    <div class="mb-6">
                        <div class="text-5xl font-bold text-blue-500 mb-2">5+</div>
                        <p class="text-slate-400">Years of Experience</p>
                    </div>
                    <div class="mb-6">
                        <div class="text-5xl font-bold text-blue-500 mb-2">100%</div>
                        <p class="text-slate-400">Satisfaction Rate</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="py-20 bg-slate-950">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Our Services</h2>
                <p class="text-lg text-slate-400 max-w-2xl mx-auto">Comprehensive range of tire and wheel services.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                $services = [
                    ['icon' => 'car', 'title' => 'Tire Services', 'desc' => 'Complete tire repair, replacement, and vulcanizing'],
                    ['icon' => 'wrench', 'title' => 'Wheel Alignment', 'desc' => 'Precision wheel alignment and balancing'],
                    ['icon' => 'shield', 'title' => 'Quality Parts', 'desc' => 'We use only high-quality materials and parts'],
                    ['icon' => 'clock', 'title' => 'Quick Service', 'desc' => 'Fast turnaround times to get you back on the road'],
                ];
                @endphp

                @foreach($services as $service)
                <div class="bg-slate-900 border border-slate-800 p-6 rounded-xl group hover:-translate-y-2 hover:border-blue-500/30 transition-all">
                    <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-600 transition-all">
                        <i data-lucide="{{ $service['icon'] }}" class="w-8 h-8 text-blue-500 group-hover:text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2 text-center group-hover:text-blue-500">{{ $service['title'] }}</h3>
                    <p class="text-slate-400 text-center text-sm">{{ $service['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="contact" class="py-20 bg-slate-900">
        <div class="container mx-auto px-4 text-center">
            <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-12">
                <div class="bg-slate-800 p-6 rounded-xl text-center border border-slate-700 hover:border-blue-500/50 transition-all">
                    <i data-lucide="phone" class="w-6 h-6 text-blue-500 mx-auto mb-4"></i>
                    <h3 class="font-semibold text-white mb-2">Phone</h3>
                    <p class="text-slate-300">+63 912 345 6789</p>
                </div>
                <div class="bg-slate-800 p-6 rounded-xl text-center border border-slate-700 hover:border-blue-500/50 transition-all">
                    <i data-lucide="mail" class="w-6 h-6 text-blue-500 mx-auto mb-4"></i>
                    <h3 class="font-semibold text-white mb-2">Email</h3>
                    <p class="text-slate-300">info@autocare.com</p>
                </div>
                <div class="bg-slate-800 p-6 rounded-xl text-center border border-slate-700 hover:border-blue-500/50 transition-all">
                    <i data-lucide="map-pin" class="w-6 h-6 text-blue-500 mx-auto mb-4"></i>
                    <h3 class="font-semibold text-white mb-2">Location</h3>
                    <p class="text-slate-300">123 Main Street, City</p>
                </div>
            </div>
            <button onclick="toggleBookingModal()" class="inline-block bg-blue-600 text-white px-10 py-4 rounded-lg font-bold text-lg hover:scale-105 transition-transform shadow-xl shadow-blue-500/20">
                Book Your Service Now
            </button>
        </div>
    </section>

    <div id="bookingModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Book Your Service</h3>
                <button onclick="toggleBookingModal()" class="text-slate-400 hover:text-white"><i data-lucide="x"></i></button>
            </div>
            
            <div class="form-container">
    <form action="{{ route('landing.store') }}" method="POST" class="p-8 space-y-8">
        @csrf

        <div>
            <h3 class="text-lg font-semibold text-blue-500 mb-4 flex items-center gap-2">
                <span class="w-1 h-6 bg-blue-500 rounded-full"></span> Personal Information
            </h3>
            <div class="grid gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Full Name *</label>
                    <input type="text" name="fullName" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 focus:ring-2 focus:ring-blue-500 outline-none text-white" placeholder="Enter your full name">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Contact Number *</label>
                        <input type="text" name="contactNumber" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white" placeholder="09123456789">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Email (Optional)</label>
                        <input type="email" name="email" class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white" placeholder="your@email.com">
                    </div>
                </div>
            </div>
        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-blue-500 mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-blue-500 rounded-full"></span> Vehicle Information
                            </h3>
                            <div class="grid gap-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Vehicle Type *</label>
                                        <select name="vehicleType" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white outline-none">
                                            <option value="">Select type</option>
                                            @foreach(['Sedan', 'SUV', 'Pickup Truck', 'Van', 'Motorcycle', 'Other'] as $type)
                                                <option value="{{ $type }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Brand *</label>
                                        <input type="text" name="vehicleBrand" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white" placeholder="e.g., Toyota">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Model *</label>
                                        <input type="text" name="vehicleModel" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white" placeholder="e.g., Vios">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Plate Number *</label>
                                        <input type="text" name="plateNumber" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white" placeholder="ABC 1234">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-blue-500 mb-4 flex items-center gap-2">
                                <span class="w-1 h-6 bg-blue-500 rounded-full"></span> Service Details
                            </h3>
                            <div class="grid gap-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Preferred Date *</label>
                                        <input type="date" name="preferredDate" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white [color-scheme:dark]">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Preferred Time *</label>
                                        <input type="time" name="preferredTime" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white [color-scheme:dark]">
                                    </div>
                                </div>
                                <div class="grid gap-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Service Type *</label>
                                        <select name="serviceType" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white outline-none">
                                            <option value="">Select service</option>
                                            @foreach(['Tire Vulcanizing', 'Tire Replacement', 'Wheel Alignment', 'Wheel Balancing', 'Flat Tire Repair', 'Tire Rotation', 'Other'] as $service)
                                                <option value="{{ $service }}">{{ $service }}</option>
                                            @endforeach
                                        </select>
                                    </div>
            
                                </div>
                            
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Describe Your Concern</label>
                                    <textarea name="concern" rows="3" class="w-full bg-slate-800 border-slate-700 rounded-md p-4 text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Please describe the issue..."></textarea>
                                </div>
                    
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition transform active:scale-95 shadow-lg shadow-blue-500/20">
                            Confirm Appointment
                        </button>
                </div>
            </form>
        </div>
    </div>
        <div id="successModal" class="fixed inset-0 z-[101] hidden items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-6 text-center">
                <h3 class="text-xl font-bold text-white mb-4">Booking Successful!</h3>
                <p class="text-slate-400 mb-6">Your appointment has been successfully booked. We look forward to serving you!</p>
                <button onclick="closeSuccessModal()" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                    Close
                </button>
            </div>
        </div>
    </div>
    <footer class="bg-slate-950 border-t border-slate-900 pt-16 pb-8 relative z-10">
        <div class="container mx-auto px-4 text-center">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12 text-left">
                <div>
                    <h3 class="text-white font-bold text-xl mb-4">AutoCare</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Your trusted vulcanizing shop. Quality service for your safety on the road.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-slate-400 text-sm">
                        <li><a href="/#about" class="hover:text-blue-500 transition">About Us</a></li>
                        <li><a href="/#services" class="hover:text-blue-500 transition">Our Services</a></li>
                        <li><button onclick="toggleBookingModal()" class="hover:text-blue-500 transition">Book Appointment</button></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Operating Hours</h4>
                    <ul class="space-y-2 text-slate-400 text-sm">
                        <li class="flex justify-between"><span>Mon - Sat:</span> <span>8:00 AM - 6:00 PM</span></li>
                        <li class="flex justify-between text-blue-500"><span>Sunday:</span> <span>Closed</span></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-slate-900">
                <p class="text-slate-500 text-xs tracking-widest uppercase">
                    © 2024 AutoCare Vulcanizing Shop. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        // New Logic: Toggle function for modal
        function toggleBookingModal() {
            const modal = document.getElementById('bookingModal');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden'; // Stop scrolling
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto'; // Enable scrolling
            }
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('bookingModal');
            if (event.target == modal) {
                toggleBookingModal();
            }
        }

    </script>
</body>

</html>

