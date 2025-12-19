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
            max-width: 900px;
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
            transition: all 0.3s;
        }
        
        .btn-pesan:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
            color: white;
        }
        
        .btn-pesan:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .product-card {
            border: 2px solid #e3e6f0;
            border-radius: 10px;
            transition: all 0.3s;
            cursor: pointer;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
            background: white;
            height: 100%;
        }
        
        .product-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.1);
            transform: translateY(-2px);
        }
        
        .product-card.selected {
            border-color: var(--primary-color);
            background: rgba(220, 53, 69, 0.03);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.15);
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
            font-size: 11px;
            padding: 3px 8px;
        }
        
        .product-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 15px;
            flex-shrink: 0;
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
        
        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            opacity: 0;
            height: 0;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .product-card.selected .quantity-control {
            opacity: 1;
            height: auto;
            margin-top: 15px;
        }
        
        .qty-btn {
            width: 35px;
            height: 35px;
            border: 1px solid #dee2e6;
            background: white;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .qty-btn:hover:not(:disabled) {
            background: #f8f9fa;
            border-color: #adb5bd;
        }
        
        .qty-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .qty-input {
            width: 60px;
            height: 35px;
            text-align: center;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin: 0 5px;
            font-weight: 500;
        }
        
        .selected-indicator {
            position: absolute;
            top: 5px;
            left: 5px;
            width: 20px;
            height: 20px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .product-card.selected .selected-indicator {
            opacity: 1;
        }
        
        .cart-summary {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.05) 0%, rgba(25, 135, 84, 0.05) 100%);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border: 2px dashed #dee2e6;
        }
        
        .cart-empty {
            text-align: center;
            padding: 30px;
        }
        
        .cart-empty i {
            font-size: 3rem;
            opacity: 0.5;
        }
        
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: white;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .cart-item:hover {
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .cart-item-info {
            flex: 1;
        }
        
        .cart-item-info h6 {
            margin-bottom: 5px;
            color: #333;
        }
        
        .cart-item-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .cart-total {
            border-top: 2px solid #dee2e6;
            padding-top: 15px;
            margin-top: 15px;
        }
        
        .remove-item {
            color: #dc3545;
            cursor: pointer;
            transition: all 0.2s;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            background: rgba(220, 53, 69, 0.1);
        }
        
        .remove-item:hover {
            color: white;
            background: #dc3545;
            transform: scale(1.05);
        }
        
        .cart-item-quantity {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .cart-item-price {
            text-align: right;
            min-width: 150px;
        }
        
        .item-subtotal {
            font-weight: bold;
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        
        .item-unit-price {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .stock-warning {
            color: #ffc107;
            font-size: 0.8rem;
            margin-top: 3px;
        }
        
        .max-limit {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 3px;
        }
        
        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .cart-item-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .cart-item-price {
                text-align: left;
            }
            
            .cart-item-quantity {
                width: 100%;
                justify-content: space-between;
            }
        }
        
        .selected-products-count {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .form-required::after {
            content: " *";
            color: #dc3545;
        }
        
        .product-add-info {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        /* Custom notification styles */
        .custom-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
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
            <p class="text-muted">Pilih produk, atur jumlah, dan lengkapi data pengiriman</p>
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
                            <label for="nama_pembeli" class="form-label form-required">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" 
                                   placeholder="Masukkan nama lengkap" required value="{{ old('nama_pembeli') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label form-required">No. HP / WhatsApp</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" 
                                   placeholder="0812-3456-7890" required value="{{ old('no_hp') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email (Opsional)</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="nama@email.com" value="{{ old('email') }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="alamat" class="form-label form-required">Alamat Pengiriman</label>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-cart me-2"></i>Pilih Produk
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary" id="cartCount">0 item</span>
                            <span class="selected-products-count" id="selectedProductsCount">Belum ada produk dipilih</span>
                        </div>
                    </div>
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
                            Gratis pengantaran untuk seluruh area layanan kami. Anda bisa memesan gas dan galon sekaligus!
                        </div>
                        
                        <!-- Produk List -->
                        <div class="row g-3" id="productList">
                            @foreach($barang as $item)
                                @php
                                    $isGas = str_contains(strtolower($item->nama_barang), 'gas') || 
                                             str_contains(strtolower($item->kategori), 'gas');
                                    $iconClass = $isGas ? 'gas-icon' : 'galon-icon';
                                    $badgeClass = $isGas ? 'gas-badge' : 'galon-badge';
                                    $icon = $isGas ? 'bi-fire' : 'bi-droplet';
                                @endphp
                                
                                <div class="col-lg-6">
                                    <div class="product-card product-selector" 
                                         data-product-id="{{ $item->id }}"
                                         data-product-name="{{ $item->nama_barang }}"
                                         data-product-price="{{ $item->harga }}"
                                         data-product-stock="{{ $item->stok }}"
                                         data-product-icon="{{ $iconClass }}"
                                         data-product-category="{{ $item->kategori }}">
                                        <div class="selected-indicator">
                                            <i class="bi bi-check"></i>
                                        </div>
                                        <span class="badge {{ $item->stok > 0 ? 'bg-success' : 'bg-danger' }} stock-badge">
                                            {{ $item->stok > 0 ? 'Stok: ' . $item->stok : 'Habis' }}
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
                                                    <small class="text-muted">
                                                        <i class="bi bi-truck me-1"></i>Gratis Antar
                                                    </small>
                                                </div>
                                                <div class="product-add-info">
                                                    Klik untuk memilih produk
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Quantity Control (Hidden by default) -->
                                        <div class="quantity-control">
                                            <div class="d-flex align-items-center justify-content-between w-100">
                                                <div>
                                                    <small class="text-muted">Atur jumlah:</small>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <button type="button" class="qty-btn decrease" data-product-id="{{ $item->id }}">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number" 
                                                           class="qty-input" 
                                                           data-product-id="{{ $item->id }}"
                                                           value="1" 
                                                           min="1" 
                                                           max="{{ $item->stok }}">
                                                    <button type="button" class="qty-btn increase" data-product-id="{{ $item->id }}">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted">Maksimal: {{ $item->stok }} item</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Keranjang Belanja -->
                        <div class="cart-summary" id="cartSummary">
                            <div class="cart-empty" id="cartEmpty">
                                <i class="bi bi-cart-x text-muted"></i>
                                <h5 class="mt-3">Keranjang Kosong</h5>
                                <p class="text-muted">Klik produk untuk menambahkannya ke pesanan</p>
                            </div>
                            
                            <div id="cartItems" style="display: none;">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-cart-check me-2"></i>Pesanan Anda
                                    <small class="text-muted ms-2">(Anda bisa pesan gas dan galon sekaligus)</small>
                                </h6>
                                <div id="cartItemsList">
                                    <!-- Items akan ditambahkan dinamis -->
                                </div>
                                <div class="cart-total">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold mb-0">Total Pesanan:</h6>
                                            <small class="text-muted" id="totalItemsCount">0 item</small>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="text-primary mb-0" id="cartTotal">Rp 0</h5>
                                            <small class="text-muted">Termasuk gratis ongkir</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden inputs untuk semua produk -->
                        <div id="productInputs">
                            <!-- Input untuk produk yang dipilih akan ditambahkan dinamis -->
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
                    <div class="form-text">Contoh: "Tolong antar jam 2-4 siang", "Tinggal di lantai 3", "Isi tabung gas yang penuh"</div>
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
        // Variabel global untuk keranjang
        let cart = [];
        
        // Fungsi untuk menampilkan notifikasi sederhana
        function showNotification(message, type = 'info') {
            // Hapus notifikasi lama jika ada
            const oldNotification = document.getElementById('custom-notification');
            if (oldNotification) {
                oldNotification.remove();
            }
            
            // Tentukan kelas alert berdasarkan type
            let alertClass = 'alert-info';
            let iconClass = 'bi-info-circle';
            
            if (type === 'success') {
                alertClass = 'alert-success';
                iconClass = 'bi-check-circle';
            } else if (type === 'warning') {
                alertClass = 'alert-warning';
                iconClass = 'bi-exclamation-triangle';
            } else if (type === 'danger') {
                alertClass = 'alert-danger';
                iconClass = 'bi-exclamation-triangle';
            }
            
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.id = 'custom-notification';
            notification.className = `alert ${alertClass} alert-dismissible fade show custom-alert`;
            notification.innerHTML = `
                <i class="bi ${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Hapus otomatis setelah 5 detik
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
        
        // Event listener sederhana untuk product cards
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk product cards
            document.querySelectorAll('.product-selector').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Cegah trigger saat klik quantity control atau tombolnya
                    if (e.target.closest('.quantity-control') || 
                        e.target.closest('.qty-btn') || 
                        e.target.classList.contains('qty-input')) {
                        return;
                    }
                    
                    const productId = this.dataset.productId;
                    const productName = this.dataset.productName;
                    const productPrice = parseInt(this.dataset.productPrice);
                    const productStock = parseInt(this.dataset.productStock);
                    const productIcon = this.dataset.productIcon;
                    const productCategory = this.dataset.productCategory;
                    
                    if (productStock <= 0) {
                        showNotification('Maaf, produk ini sedang habis stok', 'warning');
                        return;
                    }
                    
                    // Cek apakah produk sudah ada di cart
                    const existingIndex = cart.findIndex(item => item.id == productId);
                    
                    if (existingIndex > -1) {
                        // Hapus dari cart
                        cart.splice(existingIndex, 1);
                        this.classList.remove('selected');
                        showNotification(`${productName} dihapus dari keranjang`, 'info');
                    } else {
                        // Tambah ke cart
                        const qtyInput = this.querySelector('.qty-input');
                        const quantity = parseInt(qtyInput.value) || 1;
                        
                        cart.push({
                            id: productId,
                            name: productName,
                            price: productPrice,
                            quantity: quantity,
                            stock: productStock,
                            icon: productIcon,
                            category: productCategory
                        });
                        
                        this.classList.add('selected');
                        showNotification(`${productName} ditambahkan ke keranjang`, 'success');
                    }
                    
                    updateCartDisplay();
                    updateSubmitButton();
                    validateForm();
                });
            });
            
            // Event listener untuk tombol minus dan plus di product cards
            document.addEventListener('click', function(e) {
                const decreaseBtn = e.target.closest('.decrease');
                const increaseBtn = e.target.closest('.increase');
                
                if (decreaseBtn) {
                    const productId = decreaseBtn.dataset.productId;
                    const card = document.querySelector(`.product-selector[data-product-id="${productId}"]`);
                    if (card) {
                        const qtyInput = card.querySelector('.qty-input');
                        let quantity = parseInt(qtyInput.value) || 1;
                        const stock = parseInt(card.dataset.productStock);
                        
                        if (quantity > 1) {
                            quantity--;
                            qtyInput.value = quantity;
                            
                            // Update cart jika produk sudah dipilih
                            if (card.classList.contains('selected')) {
                                const cartItem = cart.find(item => item.id == productId);
                                if (cartItem) {
                                    cartItem.quantity = quantity;
                                    updateCartDisplay();
                                    updateSubmitButton();
                                    validateForm();
                                }
                            }
                        }
                    }
                }
                
                if (increaseBtn) {
                    const productId = increaseBtn.dataset.productId;
                    const card = document.querySelector(`.product-selector[data-product-id="${productId}"]`);
                    if (card) {
                        const qtyInput = card.querySelector('.qty-input');
                        let quantity = parseInt(qtyInput.value) || 1;
                        const stock = parseInt(card.dataset.productStock);
                        
                        if (quantity < stock) {
                            quantity++;
                            qtyInput.value = quantity;
                            
                            // Update cart jika produk sudah dipilih
                            if (card.classList.contains('selected')) {
                                const cartItem = cart.find(item => item.id == productId);
                                if (cartItem) {
                                    cartItem.quantity = quantity;
                                    updateCartDisplay();
                                    updateSubmitButton();
                                    validateForm();
                                }
                            }
                        } else {
                            showNotification(`Maksimal stok: ${stock} item`, 'warning');
                        }
                    }
                }
            });
            
            // Event listener untuk input quantity
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('qty-input')) {
                    const productId = e.target.dataset.productId;
                    const card = document.querySelector(`.product-selector[data-product-id="${productId}"]`);
                    if (card) {
                        const stock = parseInt(card.dataset.productStock);
                        let quantity = parseInt(e.target.value) || 1;
                        
                        if (quantity < 1) {
                            quantity = 1;
                            e.target.value = 1;
                        } else if (quantity > stock) {
                            quantity = stock;
                            e.target.value = stock;
                            showNotification(`Jumlah melebihi stok. Maksimal: ${stock}`, 'warning');
                        }
                        
                        // Update cart jika produk sudah dipilih
                        if (card.classList.contains('selected')) {
                            const cartItem = cart.find(item => item.id == productId);
                            if (cartItem) {
                                cartItem.quantity = quantity;
                                updateCartDisplay();
                                updateSubmitButton();
                                validateForm();
                            }
                        }
                    }
                }
            });
            
            // Inisialisasi tampilan awal
            updateCartDisplay();
            updateSubmitButton();
        });
        
        // Update tampilan keranjang
        function updateCartDisplay() {
            const cartItemsList = document.getElementById('cartItemsList');
            const cartTotal = document.getElementById('cartTotal');
            const cartCount = document.getElementById('cartCount');
            const selectedProductsCount = document.getElementById('selectedProductsCount');
            const totalItemsCount = document.getElementById('totalItemsCount');
            const cartEmpty = document.getElementById('cartEmpty');
            const cartItems = document.getElementById('cartItems');
            const productInputs = document.getElementById('productInputs');
            
            if (cart.length === 0) {
                cartEmpty.style.display = 'block';
                cartItems.style.display = 'none';
                cartCount.textContent = '0 item';
                selectedProductsCount.textContent = 'Belum ada produk dipilih';
                productInputs.innerHTML = '';
            } else {
                cartEmpty.style.display = 'none';
                cartItems.style.display = 'block';
                
                // Hitung total item dan kategori
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                const categories = [...new Set(cart.map(item => item.category))];
                
                cartCount.textContent = cart.length + ' jenis produk';
                selectedProductsCount.textContent = `${totalItems} item (${categories.join(', ')})`;
                totalItemsCount.textContent = `${totalItems} item`;
                
                // Update cart items
                let itemsHTML = '';
                let total = 0;
                productInputs.innerHTML = '';
                
                cart.forEach((item) => {
                    const subtotal = item.price * item.quantity;
                    total += subtotal;
                    
                    // Tentukan ikon berdasarkan kategori
                    const itemIcon = item.icon.includes('gas-icon') ? 'bi-fire' : 'bi-droplet';
                    const itemIconClass = item.icon.includes('gas-icon') ? 'text-danger' : 'text-success';
                    
                    // Tentukan label kategori
                    let categoryLabel = item.category;
                    if (item.name.toLowerCase().includes('aqua')) {
                        categoryLabel = 'Galon Air';
                    } else if (item.name.toLowerCase().includes('gas') || item.category.toLowerCase().includes('gas')) {
                        categoryLabel = 'Gas LPG';
                    }
                    
                    // Item cart
                    itemsHTML += `
                        <div class="cart-item" id="cart-item-${item.id}">
                            <div class="cart-item-info">
                                <h6 class="mb-1">
                                    <i class="bi ${itemIcon} ${itemIconClass} me-2"></i>
                                    ${item.name}
                                </h6>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted">${categoryLabel}</small>
                                    <small class="ms-3 text-success">
                                        <i class="bi bi-check-circle me-1"></i>Stok: ${item.stock}
                                    </small>
                                </div>
                            </div>
                            <div class="cart-item-actions">
                                <div class="cart-item-quantity">
                                    <button type="button" class="qty-btn decrease" onclick="adjustCartQuantity('${item.id}', -1)" ${item.quantity <= 1 ? 'disabled' : ''}>
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" 
                                           class="qty-input" 
                                           value="${item.quantity}" 
                                           min="1" 
                                           max="${item.stock}"
                                           onchange="updateCartQuantity('${item.id}', this.value)">
                                    <button type="button" class="qty-btn increase" onclick="adjustCartQuantity('${item.id}', 1)" ${item.quantity >= item.stock ? 'disabled' : ''}>
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                                <div class="cart-item-price">
                                    <div class="item-subtotal">Rp ${subtotal.toLocaleString('id-ID')}</div>
                                    <div class="item-unit-price">Rp ${item.price.toLocaleString('id-ID')} × ${item.quantity}</div>
                                    ${item.quantity >= item.stock ? '<div class="max-limit"><i class="bi bi-exclamation-triangle me-1"></i>Maksimal stok</div>' : ''}
                                </div>
                                <div class="remove-item" onclick="removeCartItem('${item.id}')" title="Hapus dari pesanan">
                                    <i class="bi bi-trash"></i>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Hidden inputs untuk form
                    productInputs.innerHTML += `
                        <input type="hidden" name="barang_id[]" value="${item.id}">
                        <input type="hidden" name="jumlah[]" value="${item.quantity}">
                    `;
                });
                
                cartItemsList.innerHTML = itemsHTML;
                cartTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
        }
        
        // Atur jumlah di keranjang
        function adjustCartQuantity(productId, change) {
            const cartItem = cart.find(item => item.id == productId);
            if (cartItem) {
                let newQuantity = cartItem.quantity + change;
                
                if (newQuantity < 1) newQuantity = 1;
                if (newQuantity > cartItem.stock) {
                    newQuantity = cartItem.stock;
                    showNotification(`Jumlah melebihi stok. Maksimal: ${cartItem.stock}`, 'warning');
                }
                
                cartItem.quantity = newQuantity;
                
                // Update juga di product card
                const card = document.querySelector(`.product-selector[data-product-id="${productId}"]`);
                if (card) {
                    const qtyInput = card.querySelector('.qty-input');
                    qtyInput.value = newQuantity;
                }
                
                updateCartDisplay();
                updateSubmitButton();
                validateForm();
            }
        }
        
        // Update jumlah di keranjang
        function updateCartQuantity(productId, input) {
            const cartItem = cart.find(item => item.id == productId);
            if (cartItem) {
                let newQuantity = parseInt(input.value) || 1;
                
                if (newQuantity < 1) newQuantity = 1;
                if (newQuantity > cartItem.stock) {
                    newQuantity = cartItem.stock;
                    showNotification(`Jumlah melebihi stok. Maksimal: ${cartItem.stock}`, 'warning');
                }
                
                cartItem.quantity = newQuantity;
                
                // Update juga di product card
                const card = document.querySelector(`.product-selector[data-product-id="${productId}"]`);
                if (card) {
                    const qtyInput = card.querySelector('.qty-input');
                    qtyInput.value = newQuantity;
                }
                
                updateCartDisplay();
                updateSubmitButton();
                validateForm();
            }
        }
        
        // Hapus item dari keranjang
        function removeCartItem(productId) {
            const cartItem = cart.find(item => item.id == productId);
            if (cartItem) {
                // Gunakan confirm dialog native
                const userConfirmed = confirm(`Apakah Anda yakin ingin menghapus ${cartItem.name} dari pesanan?`);
                if (userConfirmed) {
                    // Hapus dari cart
                    const index = cart.findIndex(item => item.id == productId);
                    if (index > -1) {
                        cart.splice(index, 1);
                    }
                    
                    // Hapus class selected
                    const card = document.querySelector(`.product-selector[data-product-id="${productId}"]`);
                    if (card) {
                        card.classList.remove('selected');
                        
                        // Reset quantity ke 1
                        const qtyInput = card.querySelector('.qty-input');
                        qtyInput.value = 1;
                    }
                    
                    updateCartDisplay();
                    updateSubmitButton();
                    validateForm();
                }
            }
        }
        
        // Update tombol submit
        function updateSubmitButton() {
            const submitBtn = document.getElementById('submitBtn');
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            
            if (cart.length > 0 && totalItems > 0) {
                submitBtn.disabled = false;
                const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                submitBtn.innerHTML = `<i class="bi bi-check-circle me-2"></i>Pesan ${totalItems} Item (Rp ${totalPrice.toLocaleString('id-ID')})`;
            } else {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<i class="bi bi-check-circle me-2"></i>Pesan Sekarang`;
            }
        }
        
        // Validasi form
        function validateForm() {
            const nama = document.getElementById('nama_pembeli').value.trim();
            const noHp = document.getElementById('no_hp').value.trim();
            const alamat = document.getElementById('alamat').value.trim();
            const hpRegex = /^[0-9]{10,13}$/;
            const cleanNoHp = noHp.replace(/[^0-9]/g, '');
            
            const isValid = nama.length >= 3 && 
                          hpRegex.test(cleanNoHp) && 
                          alamat.length >= 10 && 
                          cart.length > 0;
            
            const submitBtn = document.getElementById('submitBtn');
            if (cart.length > 0) {
                submitBtn.disabled = !isValid;
            }
            return isValid;
        }
        
        // Format nomor HP saat input
        document.getElementById('no_hp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 0) {
                if (value.length <= 4) {
                    value = value;
                } else if (value.length <= 7) {
                    value = value.replace(/(\d{4})(\d{1,})/, '$1-$2');
                } else if (value.length <= 11) {
                    value = value.replace(/(\d{4})(\d{3})(\d{1,})/, '$1-$2-$3');
                } else {
                    value = value.replace(/(\d{4})(\d{3})(\d{4})/, '$1-$2-$3');
                }
            }
            
            e.target.value = value;
        });
        
        // Validasi form sebelum submit
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            if (cart.length === 0) {
                e.preventDefault();
                showNotification('Silakan pilih minimal satu produk gas atau galon!', 'warning');
                return false;
            }
            
            // Validasi data diri
            const nama = document.getElementById('nama_pembeli').value.trim();
            const noHp = document.getElementById('no_hp').value.trim();
            const alamat = document.getElementById('alamat').value.trim();
            
            if (!nama || !noHp || !alamat) {
                e.preventDefault();
                showNotification('Harap lengkapi data diri Anda!', 'warning');
                return false;
            }
            
            // Validasi nomor HP
            const hpRegex = /^[0-9]{10,13}$/;
            const cleanNoHp = noHp.replace(/[^0-9]/g, '');
            if (!hpRegex.test(cleanNoHp)) {
                e.preventDefault();
                showNotification('Nomor HP/WhatsApp tidak valid! Harap masukkan 10-13 digit angka.', 'warning');
                return false;
            }
            
            // Tampilkan loading
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses pesanan...';
            submitBtn.disabled = true;
            
            return true;
        });
        
        // Validasi real-time untuk form
        document.getElementById('nama_pembeli').addEventListener('input', validateForm);
        document.getElementById('no_hp').addEventListener('input', validateForm);
        document.getElementById('alamat').addEventListener('input', validateForm);
        
        console.log('Pemesanan Gas & Galon initialized - Simple version');
    </script>
</body>
</html>