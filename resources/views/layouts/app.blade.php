<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autocare Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { background-color: #0b1120; }
        
        /* Sidebar Link Styles */
        .nav-link { 
            color: #94a3b8; 
            padding: 12px 16px; 
            border-radius: 10px; 
            transition: all 0.3s ease; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            font-size: 11px; /* Slightly bigger for readability */
            margin-bottom: 4px;
        }

        .nav-link:hover { 
            background-color: rgba(30, 41, 59, 0.6); 
            color: white; 
        }

        /* Active Page State */
        .active-page { 
            background-color: #008ecc !important; 
            color: white !important; 
            font-weight: 600; 
            box-shadow: 0 4px 12px rgba(0, 142, 204, 0.3);
        }

        .nav-link svg { width: 18px; height: 18px; }

        /* Hide scrollbar for sidebar but allow scrolling */
        .sidebar-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .sidebar-scroll::-webkit-scrollbar {
            display: none;
        }

        /* Simple modal styling */
        .modal-backdrop {
            background: rgba(2,6,23,0.65);
            backdrop-filter: blur(3px);
        }
    </style>
</head>
<body class="antialiased flex text-slate-200">

    <aside class="w-72 h-screen bg-[#0f172a] border-r border-slate-800 flex flex-col fixed inset-y-0 z-50">
        
        <div class="p-8 flex flex-col items-center">
            @if(file_exists(public_path('autocare_logo.png')))
                <img src="{{ asset('autocare_logo.png') }}" alt="AutoCare Logo" class="h-12 w-auto object-contain">
            @else
                <div class="h-12 w-12 bg-[#008ecc] rounded-lg flex items-center justify-center text-white font-bold text-2xl">AC</div>
            @endif
            <span class="text-[#008ecc] text-[10px] font-bold tracking-[0.2em] mt-3 uppercase">Autocare System</span>
        </div>

        <hr class="border-slate-800/50 mb-4 mx-6">

        <nav class="flex-1 px-4 overflow-y-auto sidebar-scroll">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active-page' : '' }}">
                <i data-lucide="layout-grid"></i> Dashboard
            </a>
            
            <a href="{{ route('customers.index') }}" class="nav-link {{ request()->is('customers*') ? 'active-page' : '' }}">
                <i data-lucide="users"></i> Customer Information
            </a>
            
            <a href="{{ route('services') }}" class="nav-link {{ request()->is('services*') ? 'active-page' : '' }}">
                <i data-lucide="clipboard-list"></i> Service Requests
            </a>
            
            <a href="{{ route('bookings') }}" class="nav-link {{ request()->is('bookings*') ? 'active-page' : '' }}">
                <i data-lucide="calendar"></i> Booking & Scheduling
            </a>
            
            <a href="{{ route('employees') }}" class="nav-link {{ request()->is('employees*') ? 'active-page' : '' }}">
                <i data-lucide="briefcase"></i> Employees & Shop
            </a>
            
            <a href="{{ route('history') }}" class="nav-link {{ request()->is('history*') ? 'active-page' : '' }}">
                <i data-lucide="file-text"></i> History & Reports
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800 bg-[#0d121f]">
            <div class="flex items-center gap-3 p-2 mb-4">
                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-[#008ecc] to-blue-400 flex items-center justify-center text-white text-lg font-bold shadow-lg">
                    A
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">Admin User</p>
                    <p class="text-[10px] text-slate-500 truncate uppercase tracking-wider font-semibold">System Administrator</p>
                </div>
            </div>
            
            <!-- Logout: open confirmation modal instead of direct submit -->
            <button id="logoutBtn" type="button" class="w-full nav-link text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-all border border-transparent hover:border-red-500/20">
                <i data-lucide="log-out"></i> Logout
            </button>

            <!-- Hidden fallback form (used by modal confirm) -->
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-72 min-h-screen">
        @yield('content')
    </main>

    <!-- Logout confirmation modal -->
    <div id="logoutModal" class="hidden fixed inset-0 z-60 flex items-center justify-center modal-backdrop p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-lg max-w-md w-full p-6 shadow-2xl">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Confirm Logout</h3>
                    <p class="text-slate-400 text-sm mt-1">Are you sure you want to sign out?</p>
                </div>
                <button id="logoutModalClose" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button id="cancelLogout" class="px-4 py-2 text-slate-300 border border-slate-700 rounded hover:bg-slate-800">Cancel</button>
                <button id="confirmLogout" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Yes, Log me out</button>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        const logoutBtn = document.getElementById('logoutBtn');
        const logoutModal = document.getElementById('logoutModal');
        const logoutModalClose = document.getElementById('logoutModalClose');
        const cancelLogout = document.getElementById('cancelLogout');
        const confirmLogout = document.getElementById('confirmLogout');
        const logoutForm = document.getElementById('logoutForm');

        function openLogoutModal() {
            logoutModal.classList.remove('hidden');
            document.body.classList.add('modal-active');
            // Move focus to modal for accessibility
            confirmLogout.focus();
        }

        function closeLogoutModal() {
            logoutModal.classList.add('hidden');
            document.body.classList.remove('modal-active');
            logoutBtn.focus();
        }

        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openLogoutModal();
        });

        logoutModalClose.addEventListener('click', (e) => {
            e.preventDefault();
            closeLogoutModal();
        });

        cancelLogout.addEventListener('click', (e) => {
            e.preventDefault();
            closeLogoutModal();
        });

        // Confirm: submit the hidden logout form (POST with CSRF)
        confirmLogout.addEventListener('click', (e) => {
            e.preventDefault();
            // Optional: disable button to prevent double-submit
            confirmLogout.disabled = true;
            // Submit the hidden form
            logoutForm.submit();
        });

        // Close modal on backdrop click
        logoutModal.addEventListener('click', (e) => {
            if (e.target === logoutModal) closeLogoutModal();
        });

        // Close modal on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !logoutModal.classList.contains('hidden')) {
                closeLogoutModal();
            }
        });
    </script>
</body>
</html>