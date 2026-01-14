<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autocare Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap 5 via Vite -->
    @vite(['resources/sass/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body {
            background-color: #0b1120 !important;
            color: #e2e8f0 !important;
        }
        
        .sidebar {
            width: 280px;
            background-color: #0f172a !important;
            border-right: 1px solid #475569 !important;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }
        
        .nav-link-custom {
            color: #94a3b8 !important;
            padding: 12px 16px !important;
            border-radius: 8px !important;
            margin-bottom: 4px !important;
            text-decoration: none !important;
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            transition: all 0.3s ease !important;
        }
        
        .nav-link-custom:hover {
            background-color: rgba(30, 41, 59, 0.6) !important;
            color: white !important;
        }
        
        .nav-link-custom.active {
            background-color: #008ecc !important;
            color: white !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 12px rgba(0, 142, 204, 0.3) !important;
        }
        
        .sidebar-scroll {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }
        
        .sidebar-scroll::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }
        
        .modal-backdrop-custom {
            background: rgba(2, 6, 23, 0.65) !important;
            backdrop-filter: blur(3px) !important;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="d-flex">
    <!-- Sidebar -->
    <aside class="sidebar d-flex flex-column">
        <div class="p-5 text-center">
            @if(file_exists(public_path('autocare_logo.png')))
                <img src="{{ asset('autocare_logo.png') }}" alt="AutoCare Logo" class="img-fluid mb-3" style="height: 48px;">
            @else
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px;">
                    <span class="text-white fw-bold fs-4">AC</span>
                </div>
            @endif
            <span class="text-primary small fw-bold text-uppercase tracking-wide">Autocare System</span>
        </div>
        
        <hr class="border-secondary mx-4 my-0">
        
        <nav class="flex-grow-1 px-3 py-4 overflow-auto sidebar-scroll">
            <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->is('dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-grid"></i> Dashboard
            </a>
            <a href="{{ route('customers.index') }}" class="nav-link-custom {{ request()->is('customers*') ? 'active' : '' }}">
                <i data-lucide="users"></i> Customer Information
            </a>
            <a href="{{ route('services') }}" class="nav-link-custom {{ request()->is('services*') ? 'active' : '' }}">
                <i data-lucide="clipboard-list"></i> Service Requests
            </a>
            <a href="{{ route('bookings') }}" class="nav-link-custom {{ request()->is('bookings*') ? 'active' : '' }}">
                <i data-lucide="calendar"></i> Booking & Scheduling
            </a>
            <a href="{{ route('employees') }}" class="nav-link-custom {{ request()->is('employees*') ? 'active' : '' }}">
                <i data-lucide="briefcase"></i> Employees & Shop
            </a>
            <a href="{{ route('history') }}" class="nav-link-custom {{ request()->is('history*') ? 'active' : '' }}">
                <i data-lucide="file-text"></i> History & Reports
            </a>
        </nav>
        
        <div class="p-4 border-top border-secondary bg-dark">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <span class="text-white fw-bold">A</span>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold text-white text-truncate">Admin User</div>
                    <div class="small text-muted text-uppercase">System Administrator</div>
                </div>
            </div>
            
            <button type="button" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center" 
                    data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i data-lucide="log-out" class="me-2"></i> Logout
            </button>
            
            <!-- Hidden logout form -->
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content flex-grow-1">
        @yield('content')
    </main>
    
    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border border-secondary">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title text-white">Confirm Logout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Are you sure you want to sign out?</p>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('logoutForm').submit();">
                        Yes, Log me out
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide && lucide.createIcons) {
                lucide.createIcons();
            }
            
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
        
        // Close modal on backdrop click
        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                var modal = bootstrap.Modal.getInstance(this);
                modal.hide();
            }
        });
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var modal = bootstrap.Modal.getInstance(document.getElementById('logoutModal'));
                if (modal) {
                    modal.hide();
                }
            }
        });
    </script>
</body>
</html>