<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AutoCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased bg-slate-950">

    <header class="fixed w-full z-50 bg-slate-900/80 backdrop-blur-md border-b border-slate-800">
        <nav class="container mx-auto px-4 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 group">
                <img src="{{ asset('autocare_logo.png') }}" alt="AutoCare Logo" class="h-12 w-auto transition-transform group-hover:scale-110">

            </a>

            <div class="hidden md:flex space-x-8 text-slate-300 text-sm">
                <a href="/#about" class="hover:text-white transition">About</a>
                <a href="/#services" class="hover:text-white transition">Services</a>
                <a href="/#contact" class="hover:text-white transition">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="/book" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 text-sm">
                    Book Now
                </a>
            </div>
        </nav>
    </header>

    <div 
      class="min-h-screen relative flex flex-col items-center justify-center p-4 pt-32 pb-20 bg-cover bg-center bg-fixed"
      style="background-image: url('{{ asset('hero-bg.jpg') }}')"
      x-data="{ showPassword: false }"
    >
      <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-[2px] z-0"></div>

      <div class="w-full max-w-md bg-slate-900/90 border border-slate-800 rounded-xl shadow-xl backdrop-blur-md transition-all duration-300 hover:border-blue-500/20 relative z-10 overflow-hidden">
        
        <div class="p-8 text-center pb-2">
          <a href="/" class="flex items-center justify-center mb-6">
            <img 
              src="{{ asset('autocare_logo.png') }}" 
              alt="AutoCare Logo" 
              class="h-24 w-auto object-contain transition-transform duration-500 hover:scale-110 drop-shadow-lg" 
            />
          </a>
          <h1 class="text-3xl font-bold text-white tracking-tight">Welcome Back</h1>
          <p class="text-slate-300 text-lg mt-2">
            Sign in to access the AutoCare Dashboard
          </p>
        </div>

        <div class="p-8 pt-4">
          @if ($errors->any())
              <div class="mb-4 p-3 rounded bg-red-500/20 border border-red-500 text-red-200 text-sm">
                  {{ $errors->first() }}
              </div>
          @endif

          <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
              <label for="email" class="text-slate-200 block text-sm font-medium">Email</label>
              <input
                id="email"
                name="email"
                type="email"
                placeholder="Enter your email"
                value="{{ old('email') }}"
                required
                class="w-full px-4 h-11 bg-slate-800/80 border border-slate-700 rounded-md text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
              />
            </div>

            <div class="space-y-2">
              <label for="password" class="text-slate-200 block text-sm font-medium">Password</label>
              <div class="relative">
                <input
                  id="password"
                  name="password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Enter your password"
                  required
                  class="w-full px-4 h-11 bg-slate-800/80 border border-slate-700 rounded-md text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-10"
                />
                <button
                  type="button"
                  class="absolute right-0 top-0 h-full px-3 text-slate-400 hover:text-white transition-colors"
                  @click="showPassword = !showPassword"
                >
                  <i x-show="!showPassword" data-lucide="eye" class="w-4 h-4"></i>
                  <i x-show="showPassword" data-lucide="eye-off" class="w-4 h-4"></i>
                </button>
              </div>
            </div>

            <button 
              type="submit" 
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-lg h-12 rounded-md transition-all duration-300 hover:scale-[1.02] shadow-lg shadow-blue-500/20"
            >
              Sign In
            </button>
          </form>

          <div class="mt-8 p-4 bg-slate-800/60 rounded-lg border border-slate-700/50 backdrop-blur-sm">
            <p class="text-sm text-slate-300 text-center">
              <strong class="text-blue-400">Demo Credentials:</strong><br />
              Email: <span class="text-white font-mono text-xs">admin@autocare.com</span><br />
              Password: <span class="text-white font-mono text-xs">admin123</span>
            </p>
          </div>

          <p class="mt-6 text-center text-sm">
            <a href="/" class="text-slate-300 hover:text-blue-400 transition-colors duration-300 inline-flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Home
            </a>
          </p>
        </div>
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
                <p class="text-slate-500 text-xs">
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