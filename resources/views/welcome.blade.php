<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Rumah Gas dan Galon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #dc3545; /* Merah untuk gas */
            --secondary-color: #198754; /* Hijau untuk galon */
            --success-color: #1cc88a;
            --light-bg: #f8f9fc;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
            min-height: 100vh;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 80px 0;
            border-radius: 0 0 30px 30px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.3;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .logo-icon {
            font-size: 48px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .card-option {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            border: 3px solid transparent;
        }
        
        .card-option:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }
        
        .card-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
            color: white;
        }
        
        .icon-pembeli {
            background: linear-gradient(135deg, var(--success-color) 0%, #17a673 100%);
        }
        
        .icon-pedagang {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        
        .btn-pembeli {
            background: var(--success-color);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            color: white;
        }
        
        .btn-pembeli:hover {
            background: #17a673;
            transform: scale(1.05);
            color: white;
        }
        
        .btn-pedagang {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            color: white;
        }
        
        .btn-pedagang:hover {
            transform: scale(1.05);
            color: white;
        }
        
        .feature-list li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .feature-list li i {
            color: var(--success-color);
            margin-right: 10px;
        }
        
        .footer {
            background: #2e3a4e;
            color: white;
            padding: 30px 0;
            margin-top: 80px;
        }
        
        .feature-icon {
            font-size: 40px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .product-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: var(--secondary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .stats-box {
            background: rgba(255,255,255,0.9);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="logo-container">
                        <div class="logo">
                            <i class="bi bi-fire logo-icon"></i>
                        </div>
                        <h1 class="display-4 fw-bold mb-2">Rumah Gas dan Galon</h1>
                        <p class="lead mb-4">Solusi Lengkap Kebutuhan Gas & Galon Air Minum Anda</p>
                    </div>
                    
                    <div class="d-flex gap-3 justify-content-center">
                        <span class="badge bg-light text-danger p-2">
                            <i class="bi bi-fire me-1"></i>Gas LPG
                        </span>
                        <span class="badge bg-light text-success p-2">
                            <i class="bi bi-droplet me-1"></i>Galon Air
                        </span>
                        <span class="badge bg-light text-primary p-2">
                            <i class="bi bi-truck me-1"></i>Gratis Antar
                        </span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row text-center">
                        <div class="col-6 mb-4">
                            <div class="stats-box">
                                <i class="bi bi-fire feature-icon"></i>
                                <div class="stats-number">3Kg</div>
                                <p class="mb-0">Tersedia Ukuran</p>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="stats-box">
                                <i class="bi bi-droplet feature-icon"></i>
                                <div class="stats-number">20L</div>
                                <p class="mb-0">Galon Standar</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-box">
                                <i class="bi bi-clock feature-icon"></i>
                                <div class="stats-number">24/7</div>
                                <p class="mb-0">Layanan</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-box">
                                <i class="bi bi-truck feature-icon"></i>
                                <div class="stats-number">Gratis</div>
                                <p class="mb-0">Pengantaran</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Pilih Akses Anda</h2>
            <p class="text-muted">Masuk sebagai pembeli atau pengelola toko</p>
        </div>
        
        <div class="row justify-content-center g-4">
            <!-- Pembeli Card -->
            <div class="col-md-6">
                <div class="card card-option">
                    <div class="card-body text-center p-5">
                        <div class="card-icon icon-pembeli">
                            <i class="bi bi-cart"></i>
                        </div>
                        <h3 class="card-title fw-bold mb-3">Saya Pembeli</h3>
                        <p class="card-text text-muted mb-4">
                            Ingin pesan gas atau galon? Masuk tanpa login. Langsung pesan dan kami antar ke tempat Anda.
                        </p>
                        
                        <div class="mb-4 text-start">
                            <h6 class="fw-bold">Fitur untuk Pembeli:</h6>
                            <ul class="feature-list list-unstyled">
                                <li><i class="bi bi-check-circle"></i> Tidak perlu login/register</li>
                                <li><i class="bi bi-check-circle"></i> Langsung pesan gas/galon</li>
                                <li><i class="bi bi-check-circle"></i> Gratis pengantaran</li>
                                <li><i class="bi bi-check-circle"></i> Cek ketersediaan real-time</li>
                            </ul>
                        </div>
                        
                        <a href="{{ route('sebagai.pembeli') }}" class="btn btn-pembeli btn-lg w-100">
                            <i class="bi bi-arrow-right me-2"></i>Masuk Sebagai Pembeli
                        </a>
                        
                        <p class="text-muted mt-3 small">
                            <i class="bi bi-info-circle me-1"></i>
                            Anda akan langsung diarahkan ke halaman pemesanan
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Pedagang Card -->
            <div class="col-md-6">
                <div class="card card-option">
                    <div class="card-body text-center p-5">
                        <div class="card-icon icon-pedagang">
                            <i class="bi bi-shop"></i>
                        </div>
                        <h3 class="card-title fw-bold mb-3">Saya Pengelola</h3>
                        <p class="card-text text-muted mb-4">
                            Kelola toko gas & galon. Login untuk mengelola stok, pesanan, dan lihat laporan penjualan.
                        </p>
                        
                        <div class="mb-4 text-start">
                            <h6 class="fw-bold">Fitur untuk Pengelola:</h6>
                            <ul class="feature-list list-unstyled">
                                <li><i class="bi bi-check-circle"></i> Kelola stok gas & galon</li>
                                <li><i class="bi bi-check-circle"></i> Manajemen pesanan</li>
                                <li><i class="bi bi-check-circle"></i> Laporan penjualan harian</li>
                                <li><i class="bi bi-check-circle"></i> Monitoring pengantaran</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-pedagang btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login Pengelola
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-danger btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Daftar Pengelola Baru
                            </a>
                        </div>
                        
                        <p class="text-muted mt-3 small">
                            <i class="bi bi-shield-lock me-1"></i>
                            Hanya pengelola toko yang terdaftar
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products Section -->
        <div class="row mt-5 pt-5">
            <div class="col-12 text-center mb-4">
                <h3 class="fw-bold">Produk Kami</h3>
                <p class="text-muted">Berbagai pilihan gas dan galon berkualitas</p>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 position-relative">
                    <span class="product-badge">Gas LPG</span>
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-danger">
                            <i class="bi bi-fire"></i>
                        </div>
                        <h5 class="fw-bold">Gas 3Kg</h5>
                        <p>Gas LPG 3Kg untuk kebutuhan rumah tangga kecil</p>
                        <div class="d-grid">
                            <button class="btn btn-outline-danger">Pesan Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 position-relative">
                    <span class="product-badge">Gas LPG</span>
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-danger">
                            <i class="bi bi-fire"></i>
                        </div>
                        <h5 class="fw-bold">Gas 12Kg</h5>
                        <p>Gas LPG 12Kg untuk kebutuhan rumah tangga besar</p>
                        <div class="d-grid">
                            <button class="btn btn-outline-danger">Pesan Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 position-relative">
                    <span class="product-badge">Galon Air</span>
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-success">
                            <i class="bi bi-droplet"></i>
                        </div>
                        <h5 class="fw-bold">Galon 19L</h5>
                        <p>Galon air minum isi ulang 19 liter berkualitas</p>
                        <div class="d-grid">
                            <button class="btn btn-outline-success">Pesan Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Features Section -->
        <div class="row mt-5">
            <div class="col-12 text-center mb-4">
                <h3 class="fw-bold">Kenapa Memilih Kami?</h3>
                <p class="text-muted">Layanan terbaik untuk kebutuhan gas dan galon Anda</p>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h5 class="fw-bold">Gratis Antar</h5>
                    <p>Pengantaran gratis ke seluruh area layanan kami.</p>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <h5 class="fw-bold">24 Jam</h5>
                    <p>Layanan 24 jam untuk kebutuhan mendesak Anda.</p>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="text-center p-4">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold">Terjamin</h5>
                    <p>Produk original dengan kualitas terjamin.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Rumah Gas dan Galon</h5>
                    <p>Solusi lengkap kebutuhan gas LPG dan galon air minum dengan layanan terbaik dan pengantaran gratis.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-white"><i class="bi bi-whatsapp fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-facebook fs-5"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-telephone me-2"></i> (021) 1234-5678</li>
                        <li><i class="bi bi-whatsapp me-2"></i> 0812-3456-7890</li>
                        <li><i class="bi bi-envelope me-2"></i> info@rumahgasgalon.com</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Jam Operasional</h5>
                    <ul class="list-unstyled">
                        <li>Senin - Minggu: 24 Jam</li>
                        <li>Hari Libur: Tetap Buka</li>
                        <li>Pengantaran: Setiap Hari</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Rumah Gas dan Galon. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>