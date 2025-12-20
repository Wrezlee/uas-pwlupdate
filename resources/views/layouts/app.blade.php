<!DOCTYPE html>
<html lang="id" dir="ltr">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title') @yield('title') - @endif Rumah Gas & Galon</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Preload Fonts -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.3.0/dist/css/coreui.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@coreui/icons@3.0.1/css/all.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    
    <!-- CoreUI CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.3.0/dist/css/coreui.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@coreui/icons@3.0.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --sidebar-bg: linear-gradient(180deg, #2eb85c 0%, #1f8a47 100%);
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --sidebar-active: rgba(255, 255, 255, 0.2);
            --primary-color: #2eb85c;
            --secondary-color: #1f8a47;
            --transition-speed: 0.3s;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        /* Sidebar Styling */
        .sidebar {
            background: var(--sidebar-bg);
            transition: all var(--transition-speed) ease;
        }
        
        .sidebar-brand {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand-full {
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }
        
        .sidebar-brand-narrow {
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            transition: all 0.2s ease;
            border-radius: 0.375rem;
            margin: 0.125rem 0.5rem;
            padding: 0.75rem 1rem;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background-color: var(--sidebar-hover);
            transform: translateX(3px);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background-color: var(--sidebar-active);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-title {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1.5rem 1rem 0.5rem;
        }
        
        .sidebar .nav-group-items {
            padding-left: 0.5rem;
        }
        
        /* Badge Styling */
        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            font-weight: 600;
        }
        
        /* Main Content */
        .wrapper {
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .header {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            z-index: 1020;
        }
        
        .breadcrumb {
            margin-bottom: 0;
            background: transparent;
            padding: 0;
        }
        
        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        /* Footer */
        .footer {
            background: white;
            border-top: 1px solid #dee2e6;
            padding: 1rem 0;
            font-size: 0.875rem;
        }
        
        /* Loading Indicator */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1030;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
            
            .sidebar-brand-full {
                font-size: 1rem;
            }
            
            .header {
                position: sticky;
                top: 0;
            }
        }
        
        /* Print Styles */
        @media print {
            .sidebar, .header, .footer, .breadcrumb {
                display: none !important;
            }
            
            .body {
                margin: 0;
                padding: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
        <div class="sidebar-brand d-none d-md-flex">
            <div class="sidebar-brand-full px-3 text-white fw-bold">
                <i class="fas fa-fire me-2"></i> Rumah Gas & Galon
            </div>
            <div class="sidebar-brand-narrow text-white fw-bold">R&G</div>
        </div>

        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">
                    <i class="nav-icon fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-title">Transaksi</li>

            <!-- Pesanan -->
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('pesanan.*') ? 'active' : '' }}"
                   href="{{ route('pesanan.index') }}">
                    <i class="nav-icon fas fa-shopping-cart me-2"></i> Pesanan
                    @php
                        $pendingOrders = \App\Models\Pesanan::where('status', 'pending')->count();
                    @endphp
                    @if($pendingOrders > 0)
                        <span class="badge bg-warning ms-auto">{{ $pendingOrders }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-title">Inventory</li>

            <!-- Barang -->
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('barang.*') ? 'active' : '' }}"
                   href="{{ route('barang.index') }}">
                    <i class="nav-icon fas fa-box me-2"></i> Barang
                </a>
            </li>

            <!-- Kelola Stok -->
            <li class="nav-group {{ Request::routeIs('stok.*') ? 'show' : '' }}">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="nav-icon fas fa-warehouse me-2"></i> Kelola Stok
                    @php
                        $stokCount = \App\Models\StokMasuk::count() + \App\Models\StokKeluar::count();
                    @endphp
                    @if($stokCount > 0)
                        <span class="badge bg-info ms-auto">{{ $stokCount }}</span>
                    @endif
                </a>
                <ul class="nav-group-items">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('stok.masuk.*') ? 'active' : '' }}"
                           href="{{ route('stok.masuk.index') }}">
                            <i class="nav-icon fas fa-arrow-down me-2"></i> Stok Masuk
                            @php
                                $stokMasukCount = \App\Models\StokMasuk::count();
                            @endphp
                            @if($stokMasukCount > 0)
                                <span class="badge bg-success ms-auto">{{ $stokMasukCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('stok.keluar.*') ? 'active' : '' }}"
                           href="{{ route('stok.keluar.index') }}">
                            <i class="nav-icon fas fa-arrow-up me-2"></i> Stok Keluar
                            @php
                                $stokKeluarCount = \App\Models\StokKeluar::count();
                            @endphp
                            @if($stokKeluarCount > 0)
                                <span class="badge bg-danger ms-auto">{{ $stokKeluarCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link">
                            <i class="nav-icon fas fa-gas-pump me-2"></i> Stok Gas
                            @php
                                $stokGas = \App\Models\Barang::where('jenis', 'gas')->sum('stok');
                            @endphp
                            <span class="badge {{ $stokGas < 10 ? 'bg-danger' : 'bg-warning' }} ms-auto">
                                {{ $stokGas }}
                            </span>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link">
                            <i class="nav-icon fas fa-water me-2"></i> Stok Galon
                            @php
                                $stokGalon = \App\Models\Barang::where('jenis', 'galon')->sum('stok');
                            @endphp
                            <span class="badge {{ $stokGalon < 10 ? 'bg-danger' : 'bg-primary' }} ms-auto">
                                {{ $stokGalon }}
                            </span>
                        </div>
                    </li>
                </ul>
            </li>

            <li class="nav-title">Laporan</li>

            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('laporan.*') ? 'active' : '' }}"
                   href="{{ route('laporan.index') }}">
                    <i class="nav-icon fas fa-chart-pie me-2"></i> Laporan
                </a>
            </li>

            @can('manage-users')
            <li class="nav-title">Pengaturan</li>

            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('users.*') ? 'active' : '' }}"
                   href="{{ route('users.index') }}">
                    <i class="nav-icon fas fa-users me-2"></i> Users
                </a>
            </li>
            @endcan
        </ul>

        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Main Wrapper -->
    <div class="wrapper d-flex flex-column min-vh-100">
        <header class="header header-sticky">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="header-toggler me-3" type="button" 
                            onclick="toggleSidebar()"
                            aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <!-- User Dropdown -->
                    @auth
                    <div class="dropdown ms-auto">
                        <button class="btn btn-link text-dark dropdown-toggle d-flex align-items-center" 
                                type="button" 
                                data-coreui-toggle="dropdown" 
                                aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="fas fa-user me-2"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- Breadcrumb -->
            <div class="container-fluid mt-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i>
                            </a>
                        </li>
                        @yield('breadcrumb-items', '<li class="breadcrumb-item active">' . (isset($breadcrumb) ? $breadcrumb : 'Dashboard') . '</li>')
                    </ol>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer mt-auto py-3">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <strong>Gas & Galon</strong> &copy; {{ date('Y') }}
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small class="text-muted">
                            <i class="fas fa-code me-1"></i> Powered by Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                        </small>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- CoreUI JS -->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.3.0/dist/js/coreui.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // CSRF Token setup for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Sidebar toggle function
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarInstance = coreui.Sidebar.getInstance(sidebar);
            if (sidebarInstance) {
                sidebarInstance.toggle();
            }
        }
        
        // Loading overlay management
        window.addEventListener('beforeunload', function() {
            showLoading();
        });
        
        window.addEventListener('load', function() {
            hideLoading();
        });
        
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }
        
        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }
        
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new coreui.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-coreui-toggle="tooltip"]');
            tooltips.forEach(tooltip => {
                new coreui.Tooltip(tooltip);
            });
            
            // Handle logout confirmation
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    if (!confirm('Apakah Anda yakin ingin logout?')) {
                        e.preventDefault();
                    }
                });
            }
            
            // Save sidebar state to localStorage
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.addEventListener('mouseenter', function() {
                    localStorage.setItem('sidebarState', 'expanded');
                });
                
                sidebar.addEventListener('mouseleave', function() {
                    if (window.innerWidth > 768) {
                        localStorage.setItem('sidebarState', 'collapsed');
                    }
                });
                
                // Restore sidebar state
                const savedState = localStorage.getItem('sidebarState');
                if (savedState === 'collapsed' && window.innerWidth > 768) {
                    const sidebarInstance = coreui.Sidebar.getInstance(sidebar);
                    if (sidebarInstance && !sidebarInstance.isUnfoldable()) {
                        sidebarInstance.hide();
                    }
                }
            }
            
            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + S for save
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    const saveButton = document.querySelector('button[type="submit"]');
                    if (saveButton) {
                        saveButton.click();
                    }
                }
                
                // Escape to close modals
                if (e.key === 'Escape') {
                    const modals = document.querySelectorAll('.modal.show');
                    modals.forEach(modal => {
                        const modalInstance = coreui.Modal.getInstance(modal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    });
                }
            });
        });
        
        // Performance monitoring
        window.addEventListener('load', function() {
            const timing = performance.timing;
            const loadTime = timing.loadEventEnd - timing.navigationStart;
            console.log(`Page loaded in ${loadTime}ms`);
            
            // Log slow assets
            performance.getEntriesByType("resource").forEach(function(resource) {
                if (resource.duration > 1000) {
                    console.warn(`Slow resource: ${resource.name} took ${resource.duration}ms`);
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>