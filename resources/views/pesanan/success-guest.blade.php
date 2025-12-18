<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Rumah Gas dan Galon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #dc3545;
            --secondary-color: #198754;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .success-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 48px;
            color: white;
        }
        
        .order-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 10px 30px;
            border-radius: 10px;
            font-weight: 600;
            color: white;
        }
        
        .btn-outline-success {
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            padding: 10px 30px;
            border-radius: 10px;
            font-weight: 600;
        }
        
        .btn-outline-success:hover {
            background: var(--secondary-color);
            color: white;
        }
        
        .info-box {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.05) 0%, rgba(25, 135, 84, 0.05) 100%);
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            border-radius: 5px;
        }
        
        .delivery-icon {
            font-size: 40px;
            color: var(--primary-color);
        }
        
        .order-code {
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="success-card bg-white p-5">
            <!-- Success Icon -->
            <div class="text-center mb-4">
                <div class="success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>
            </div>
            
            <!-- Success Message -->
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-3" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Pesanan Berhasil!
                </h1>
                <p class="text-muted mb-4">Terima kasih telah memesan di Rumah Gas dan Galon. Pesanan Anda akan segera kami proses.</p>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
            </div>
            
            <!-- Delivery Info -->
            <div class="text-center mb-4">
                <div class="delivery-icon mb-2">
                    <i class="bi bi-truck"></i>
                </div>
                <h5 class="fw-bold">Gratis Pengantaran</h5>
                <p class="text-muted">Pesanan Anda akan diantar ke alamat yang telah diberikan</p>
            </div>
            
            <!-- Order Details -->
            <div class="order-details mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-receipt me-2"></i>Detail Pesanan
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <span class="text-muted">Kode Pesanan:</span>
                        <div class="fw-bold order-code">{{ $pesanan->kode_pesanan }}</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <span class="text-muted">Tanggal:</span>
                        <div class="fw-bold">{{ $pesanan->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <span class="text-muted">Nama Pembeli:</span>
                        <div class="fw-bold">{{ $pesanan->nama_pembeli }}</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <span class="text-muted">No. HP:</span>
                        <div class="fw-bold">{{ $pesanan->no_hp }}</div>
                    </div>
                    <div class="col-12 mb-2">
                        <span class="text-muted">Alamat Pengiriman:</span>
                        <div class="fw-bold">{{ $pesanan->alamat }}</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <span class="text-muted">Produk:</span>
                        <div class="fw-bold">{{ $pesanan->barang->nama_barang ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <span class="text-muted">Jumlah:</span>
                        <div class="fw-bold">{{ $pesanan->jumlah }} item</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <span class="text-muted">Harga Satuan:</span>
                        <div class="fw-bold">Rp {{ number_format($pesanan->barang->harga ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <span class="text-muted">Total Harga:</span>
                        <div class="fw-bold text-success">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-12 mb-2">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-warning">{{ ucfirst($pesanan->status) }}</span>
                    </div>
                    @if($pesanan->catatan)
                    <div class="col-12 mb-2">
                        <span class="text-muted">Catatan:</span>
                        <div class="fw-bold">{{ $pesanan->catatan }}</div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Information Box -->
            <div class="info-box mb-4">
                <h6 class="fw-bold mb-2">
                    <i class="bi bi-info-circle me-2"></i>Informasi Penting
                </h6>
                <ul class="mb-0">
                    <li><i class="bi bi-check-circle text-success me-2"></i>Simpan kode pesanan untuk tracking</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Pesanan akan diantar dalam 1-3 jam</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Driver akan menghubungi Anda via WhatsApp</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Pembayaran dilakukan saat barang diterima</li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div class="alert alert-info mb-4">
                <h6 class="fw-bold mb-2">
                    <i class="bi bi-headset me-2"></i>Butuh Bantuan?
                </h6>
                <p class="mb-1">
                    <i class="bi bi-whatsapp me-2 text-success"></i>
                    WhatsApp: <strong>0812-3456-7890</strong>
                </p>
                <p class="mb-0">
                    <i class="bi bi-telephone me-2"></i>
                    Telepon: <strong>(021) 1234-5678</strong>
                </p>
            </div>
            
            <!-- Action Buttons -->
            <div class="text-center">
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('welcome') }}" class="btn btn-primary">
                        <i class="bi bi-house me-2"></i>Kembali ke Beranda
                    </a>
                    <a href="{{ route('pembeli.pesanan.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-cart-plus me-2"></i>Pesan Lagi
                    </a>
                </div>
                <p class="text-muted mt-3 small">
                    <i class="bi bi-shield-check me-1"></i>
                    Terima kasih telah mempercayai <strong>Rumah Gas dan Galon</strong>
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-3">
        <div class="container text-center">
            <h5 class="mb-3">Rumah Gas dan Galon</h5>
            <p class="text-muted mb-2">Layanan pengantaran gas dan galon 24 jam</p>
            <p class="mb-0 small">&copy; {{ date('Y') }} Rumah Gas dan Galon. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>