<header class="fixed top-0 left-0 right-0 z-50 bg-black/20 backdrop-blur-md border-b border-white/10 transition-all duration-300">
    <div class="container mx-auto px-4 h-16 flex items-center justify-between">
        
        <a href="{{ route('home') }}" class="flex items-center gap-2 transition-transform duration-300 hover:scale-105">
            <img 
                src="{{ asset('images/autocare_logo.png') }}" 
                alt="AutoCare Logo" 
                class="h-16 w-auto object-contain" 
            />
        </a>
        
        <nav class="hidden md:flex items-center gap-8">
            @foreach(['About', 'Services', 'Contact'] as $item)
                <a 
                    href="#{{ strtolower($item) }}" 
                    class="text-white/90 font-medium hover:text-blue-500 transition-all duration-300 hover:-translate-y-0.5"
                >
                    {{ $item }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('book.show') }}" 
               class="text-white hover:text-blue-500 hover:bg-white/10 px-4 py-2 rounded-md font-medium transition-transform duration-300 hover:scale-105">
                Book Now
            </a>
            
            <a href="{{ route('login') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-transform duration-300 hover:scale-105 shadow-md">
                Login
            </a>
        </div>
    </div>
</header>