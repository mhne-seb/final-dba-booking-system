<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service - AutoCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="antialiased text-slate-200 bg-slate-950">

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
                <a href="/login" class="text-slate-300 hover:text-white text-sm font-medium transition">Log in</a>
                <a href="/book" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 text-sm">
                    Book Now
                </a>
            </div>
        </nav>
    </header>

    <div class="min-h-screen relative py-8 px-4 pt-32 bg-cover bg-center bg-fixed" style="background-image: url('hero-bg.jpg');">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-[2px] z-0"></div>

        <div class="container mx-auto max-w-2xl relative z-10">
            
            @if(session('submitted'))
                <div class="w-full max-w-md mx-auto text-center bg-slate-900/90 border border-slate-800 rounded-xl p-8 shadow-2xl backdrop-blur-md">
                    <div class="w-20 h-20 bg-blue-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="check-circle-2" class="w-10 h-10 text-blue-500"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-2">Booking Submitted!</h2>
                    <p class="text-slate-400 mb-8">
                        Thank you for choosing AutoCare. We'll review your booking and contact you shortly.
                    </p>
                    <div class="space-y-4">
                        <a href="/" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md transition-transform hover:scale-105 text-center">
                            Back to Home
                        </a>
                        <a href="{{ route('book.show') }}" class="block w-full border border-slate-700 text-slate-300 hover:bg-slate-800 py-3 rounded-md transition text-center">
                            Book Another Service
                        </a>
                    </div>
                </div>
            @else
                <a href="/" class="inline-flex items-center gap-2 text-slate-300 hover:text-white mb-6 transition-colors bg-slate-900/50 px-3 py-1 rounded-full backdrop-blur-md border border-slate-800/50">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Home
                </a>

                <div class="bg-slate-900/90 border border-slate-800 rounded-xl shadow-xl backdrop-blur-md overflow-hidden">
                    <div class="p-8 text-center border-b border-slate-800">
                        <img src="{{ asset('autocare_logo.png') }}" alt="Logo" class="h-24 mx-auto mb-6 drop-shadow-lg">
                        <h1 class="text-3xl font-bold text-white tracking-tight">Book a Service</h1>
                        <p class="text-slate-400 mt-2">Fill out the form below and we'll get back to you shortly</p>
                    </div>

                    <form action="{{ route('book.store') }}" method="POST" class="p-8 space-y-8">
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Service Type *</label>
                                        <select name="serviceType" required class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white outline-none">
                                            <option value="">Select service</option>
                                            @foreach(['Tire Vulcanizing', 'Tire Replacement', 'Wheel Alignment', 'Wheel Balancing', 'Flat Tire Repair', 'Tire Rotation', 'Other'] as $service)
                                                <option value="{{ $service }}">{{ $service }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Service Price (PHP)</label>
                                        <input type="number" name="servicePrice" min="0" step="0.01" class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Status</label>
                                    <select name="status" class="w-full bg-slate-800 border-slate-700 rounded-md h-11 px-4 text-white outline-none">
                                        <option value="pending">Pending</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Describe Your Concern</label>
                                    <textarea name="concern" rows="3" class="w-full bg-slate-800 border-slate-700 rounded-md p-4 text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Please describe the issue..."></textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Additional Notes (Optional)</label>
                                    <textarea name="notes" rows="3" class="w-full bg-slate-800 border-slate-700 rounded-md p-4 text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Any additional notes..."></textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg h-12 rounded-md transition-transform hover:scale-[1.02] shadow-lg shadow-blue-500/20">
                            Submit Booking
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <footer class="bg-slate-950 border-t border-slate-900 pt-16 pb-8 relative z-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
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
                        <li><a href="/book" class="hover:text-blue-500 transition">Book Appointment</a></li>
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

            <div class="pt-8 border-t border-slate-900 text-center">
                <p class="text-slate-500 text-xs tracking-widest uppercase">
                    © 2024 AutoCare Vulcanizing Shop. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>