<!DOCTYPE html>
<html lang="id">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>@yield('title', 'Dashboard') - Rumah Gas & Galon</title>

    <!-- CoreUI CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.3.0/dist/css/coreui.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@coreui/icons@3.0.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .sidebar {
            background: linear-gradient(180deg, #2eb85c 0%, #1f8a47 100%);
        }
        .sidebar-brand {
            background-color: rgba(0, 0, 0, 0.2);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>

    @stack('styles')
</head>

<body>
<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex">
        <div class="sidebar-brand-full px-3 text-white fw-bold fs-5">
            <i class="fas fa-fire me-2"></i> Rumah Gas & Galon
        </div>
        <div class="sidebar-brand-narrow text-white fw-bold">R&G</div>
    </div>

    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <i class="nav-icon fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>

        <li class="nav-title">Transaksi</li>

        <!-- Pesanan -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('pesanan.*') ? 'active' : '' }}"
               href="{{ route('pesanan.index') }}">
                <i class="nav-icon fas fa-shopping-cart"></i> Pesanan
            </a>
        </li>

        <li class="nav-title">Inventory</li>

        <!-- Barang -->
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('barang.*') ? 'active' : '' }}"
               href="{{ route('barang.index') }}">
                <i class="nav-icon fas fa-box"></i> Barang
            </a>
        </li>

        <!-- Kelola Stok (FIXED) -->
        <li class="nav-group {{ Request::routeIs('stok.*') ? 'show' : '' }}">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="nav-icon fas fa-warehouse"></i> Kelola Stok
                <span class="badge bg-info ms-auto">
                    {{ \App\Models\StokMasuk::count() + \App\Models\StokKeluar::count() }}
                </span>
            </a>
            <ul class="nav-group-items">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('stok.masuk.*') ? 'active' : '' }}"
                       href="{{ route('stok.masuk.index') }}">
                        <i class="nav-icon fas fa-arrow-down"></i> Stok Masuk
                        <span class="badge bg-success ms-auto">
                            {{ \App\Models\StokMasuk::count() }}
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('stok.keluar.*') ? 'active' : '' }}"
                       href="{{ route('stok.keluar.index') }}">
                        <i class="nav-icon fas fa-arrow-up"></i> Stok Keluar
                        <span class="badge bg-danger ms-auto">
                            {{ \App\Models\StokKeluar::count() }}
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <div class="nav-link">
                        <i class="nav-icon fas fa-gas-pump"></i> Stok Gas Saat Ini
                        <span class="badge bg-warning ms-auto">
                            {{ \App\Models\Barang::where('jenis', 'gas')->sum('stok') }}
                        </span>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="nav-link">
                        <i class="nav-icon fas fa-water"></i> Stok Galon Saat Ini
                        <span class="badge bg-primary ms-auto">
                            {{ \App\Models\Barang::where('jenis', 'galon')->sum('stok') }}
                        </span>
                    </div>
                </li>
            </ul>
        </li>

        <li class="nav-title">Laporan</li>

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('laporan.*') ? 'active' : '' }}"
               href="{{ route('laporan.index') }}">
                <i class="nav-icon fas fa-chart-pie"></i> Laporan
            </a>
        </li>

        <li class="nav-title">Pengaturan</li>

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('users.*') ? 'active' : '' }}"
               href="{{ route('users.index') }}">
                <i class="nav-icon fas fa-users"></i> Users
            </a>
        </li>
    </ul>

    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">
    <header class="header header-sticky mb-4">
        <div class="container-fluid">
            <button class="header-toggler px-md-0 me-md-3" type="button"
                onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb my-0 ms-2">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">@yield('breadcrumb','Dashboard')</li>
                </ol>
            </nav>
        </div>
    </header>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            @yield('content')
        </div>
    </div>

    <footer class="footer">
        <div><strong>Gas & Galon</strong> © {{ date('Y') }}</div>
        <div class="ms-auto">Powered by <strong>CoreUI</strong></div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.3.0/dist/js/coreui.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
