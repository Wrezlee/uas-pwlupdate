<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Gas & Galon - Rumah Gas dan Galon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #dc3545; /* Merah untuk gas */
            --secondary-color: #198754; /* Hijau untuk galon */
        }
        
        body {
            background: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-guest {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .logo-small {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .container-custom {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .card-form {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .btn-pesan {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 10px;
            color: white;
        }
        
        .btn-pesan:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
            color: white;
        }
        
        .product-card {
            border: 2px solid #e3e6f0;
            border-radius: 10px;
            transition: all 0.3s;
            cursor: pointer;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }
        
        .product-card:hover, .product-card.selected {
            border-color: var(--primary-color);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.2);
        }
        
        .product-card.selected {
            background: rgba(220, 53, 69, 0.05);
        }
        
        .price-tag {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 12px;
        }
        
        .product-icon {
            width: 50px;
            height: 50px;
            background: #e3e6f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 20px;
            margin-right: 15px;
        }
        
        .gas-icon {
            color: var(--primary-color);
            background: rgba(220, 53, 69, 0.1);
        }
        
        .galon-icon {
            color: var(--secondary-color);
            background: rgba(25, 135, 84, 0.1);
        }
        
        .category-badge {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 10px;
        }
        
        .gas-badge {
            background: rgba(220, 53, 69, 0.1);
            color: var(--primary-color);
        }
        
        .galon-badge {
            background: rgba(25, 135, 84, 0.1);
            color: var(--secondary-color);
        }
        
        .info-box {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.05) 0%, rgba(25, 135, 84, 0.05) 100%);
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-guest">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="logo-small me-2">
                    <i class="bi bi-fire"></i>
                </div>
                <a class="navbar-brand fw-bold" href="{{ route('welcome') }}">
                    Rumah Gas & Galon
                </a>
            </div>
            <div class="navbar-text">
                <span class="badge bg-success">
                    <i class="bi bi-person me-1"></i>Pembeli
                </span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container container-custom py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-3">Pesan Gas & Galon</h1>
            <p class="text-muted">Isi data diri dan pilih produk yang ingin dipesan</p>
        </div>

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('pembeli.pesanan.store') }}" id="orderForm">
            @csrf
            
            <!-- Step 1: Data Diri -->
            <div class="card card-form">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>Data Diri Pembeli
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-box mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Data Anda akan digunakan untuk pengantaran dan konfirmasi pesanan.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_pembeli" class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" 
                                   placeholder="Masukkan nama lengkap" required value="{{ old('nama_pembeli') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">No. HP / WhatsApp *</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" 
                                   placeholder="0812-3456-7890" required value="{{ old('no_hp') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email (Opsional)</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="nama@email.com" value="{{ old('email') }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="alamat" class="form-label">Alamat Pengiriman *</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" 
                                      placeholder="Masukkan alamat lengkap untuk pengantaran" required>{{ old('alamat') }}</textarea>
                            <div class="form-text">Pastikan alamat jelas dan detail untuk memudahkan pengantaran</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Pilih Produk -->
            <div class="card card-form">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cart me-2"></i>Pilih Produk
                    </h5>
                </div>
                <div class="card-body">
                    @if($barang->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-emoji-frown display-1 text-muted"></i>
                            <h4 class="mt-3">Tidak ada produk tersedia</h4>
                            <p class="text-muted">Silakan hubungi kami langsung untuk pemesanan</p>
                        </div>
                    @else
                        <div class="info-box mb-4">
                            <i class="bi bi-truck me-2"></i>
                            Gratis pengantaran untuk seluruh area layanan kami.
                        </div>
                        
                        <div class="row">
                            @foreach($barang as $item)
                                @php
                                    $isGas = str_contains(strtolower($item->nama_barang), 'gas') || 
                                             str_contains(strtolower($item->kategori), 'gas');
                                    $iconClass = $isGas ? 'gas-icon' : 'galon-icon';
                                    $badgeClass = $isGas ? 'gas-badge' : 'galon-badge';
                                    $icon = $isGas ? 'bi-fire' : 'bi-droplet';
                                @endphp
                                
                                <div class="col-md-6">
                                    <div class="product-card" 
                                         onclick="selectProduct({{ $item->id }}, '{{ $item->nama_barang }}', {{ $item->harga }}, {{ $item->stok }})"
                                         id="product-{{ $item->id }}">
                                        <span class="badge bg-success stock-badge">
                                            Stok: {{ $item->stok }}
                                        </span>
                                        <div class="d-flex">
                                            <div class="product-icon {{ $iconClass }}">
                                                <i class="bi {{ $icon }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h6 class="fw-bold mb-1">{{ $item->nama_barang }}</h6>
                                                    <span class="category-badge {{ $badgeClass }}">
                                                        {{ $item->kategori }}
                                                    </span>
                                                </div>
                                                <p class="text-muted small mb-2">{{ Str::limit($item->deskripsi, 60) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="price-tag">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                                    <small class="text-muted">Gratis Antar</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Selected Product Info -->
                        <div class="selected-product mt-4 p-4 border rounded bg-light" id="selectedProductInfo" style="display: none;">
                            <h6 class="fw-bold mb-3">Produk yang dipilih:</h6>
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="product-icon gas-icon me-3" id="selectedIcon">
                                            <i class="bi bi-fire"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold" id="productName">-</div>
                                            <div class="mt-2">
                                                <span class="fw-bold text-danger" id="productPrice">Rp 0</span>
                                                <span class="badge bg-success ms-2">Gratis Antar</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Pesanan</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                                   min="1" value="1" required>
                                            <span class="input-group-text" id="maxStock">Maks: 0</span>
                                        </div>
                                        <div class="form-text" id="stockInfo">Stok tersedia: 0</div>
                                        <div class="form-text fw-bold" id="totalPriceInfo">Total: Rp 0</div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="barang_id" name="barang_id" value="">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Step 3: Catatan -->
            <div class="card card-form">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-text me-2"></i>Catatan Tambahan
                    </h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                              placeholder="Contoh: Waktu pengantaran, lokasi spesifik, atau permintaan khusus (opsional)">{{ old('catatan') }}</textarea>
                    <div class="form-text">Contoh: "Tolong antar jam 2-4 siang", "Tinggal di lantai 3"</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                </a>
                <button type="submit" class="btn btn-pesan" id="submitBtn" disabled>
                    <i class="bi bi-check-circle me-2"></i>Pesan Sekarang
                </button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <h5 class="mb-3">Rumah Gas dan Galon</h5>
            <p class="text-muted mb-2">Layanan pengantaran gas dan galon 24 jam</p>
            <div class="mb-3">
                <i class="bi bi-telephone me-2"></i> (021) 1234-5678 | 
                <i class="bi bi-whatsapp ms-3 me-2"></i> 0812-3456-7890
            </div>
            <p class="mb-0 small">&copy; {{ date('Y') }} Rumah Gas dan Galon. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedProductId = null;
        let selectedProductPrice = 0;
        let selectedProductStock = 0;
        let selectedProductName = '';

        function selectProduct(id, name, harga, stok) {
            // Reset semua card
            document.querySelectorAll('.product-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Select card yang dipilih
            const selectedCard = document.getElementById(`product-${id}`);
            selectedCard.classList.add('selected');
            
            // Simpan data
            selectedProductId = id;
            selectedProductPrice = harga;
            selectedProductStock = stok;
            selectedProductName = name;
            
            // Update icon berdasarkan produk
            const selectedIcon = document.getElementById('selectedIcon');
            if (name.toLowerCase().includes('gas')) {
                selectedIcon.className = 'product-icon gas-icon me-3';
                selectedIcon.innerHTML = '<i class="bi bi-fire"></i>';
            } else {
                selectedIcon.className = 'product-icon galon-icon me-3';
                selectedIcon.innerHTML = '<i class="bi bi-droplet"></i>';
            }
            
            // Tampilkan info produk
            document.getElementById('selectedProductInfo').style.display = 'block';
            document.getElementById('productName').textContent = name;
            document.getElementById('productPrice').textContent = 'Rp ' + harga.toLocaleString('id-ID');
            document.getElementById('barang_id').value = id;
            document.getElementById('maxStock').textContent = 'Maks: ' + stok;
            document.getElementById('stockInfo').textContent = 'Stok tersedia: ' + stok;
            
            // Set max jumlah dan hitung total awal
            const jumlahInput = document.getElementById('jumlah');
            jumlahInput.max = stok;
            jumlahInput.value = 1;
            updateTotalPrice();
            
            // Enable submit button
            document.getElementById('submitBtn').disabled = false;
        }

        function updateTotalPrice() {
            const jumlah = parseInt(document.getElementById('jumlah').value) || 0;
            const total = selectedProductPrice * jumlah;
            document.getElementById('totalPriceInfo').textContent = 'Total: Rp ' + total.toLocaleString('id-ID');
        }

        // Validasi jumlah
        document.getElementById('jumlah').addEventListener('input', function() {
            const jumlah = parseInt(this.value) || 0;
            const stok = selectedProductStock;
            
            if (jumlah > stok) {
                this.classList.add('is-invalid');
                document.getElementById('submitBtn').disabled = true;
            } else if (jumlah < 1) {
                this.classList.add('is-invalid');
                document.getElementById('submitBtn').disabled = true;
            } else {
                this.classList.remove('is-invalid');
                document.getElementById('submitBtn').disabled = false;
                updateTotalPrice();
            }
        });

        // Form validation
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            if (!selectedProductId) {
                e.preventDefault();
                alert('Silakan pilih produk gas atau galon terlebih dahulu!');
                return false;
            }
            
            const jumlah = parseInt(document.getElementById('jumlah').value) || 0;
            if (jumlah > selectedProductStock) {
                e.preventDefault();
                alert('Jumlah melebihi stok yang tersedia!');
                return false;
            }
            
            // Validasi data diri
            const nama = document.getElementById('nama_pembeli').value;
            const noHp = document.getElementById('no_hp').value;
            const alamat = document.getElementById('alamat').value;
            
            if (!nama || !noHp || !alamat) {
                e.preventDefault();
                alert('Harap lengkapi data diri Anda!');
                return false;
            }
            
            // Show loading
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>