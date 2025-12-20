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
        
        /* Barang Detail Form */
        .item-row {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: white;
        }
        
        .add-item-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .add-item-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
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
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItemRow()">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Barang
                        </button>
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
                        
                        <!-- Barang Detail Container -->
                        <div id="itemsContainer">
                            <!-- Item rows will be added here -->
                        </div>
                        
                        <!-- Total Harga -->
                        <div class="row mt-3">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <strong>Total Harga:</strong>
                                            <strong id="totalHarga">Rp 0</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="total_harga" id="total_harga_input" value="0">
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                </a>
                <button type="submit" class="btn btn-pesan" id="submitBtn">
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
        // Data barang dari controller
        const barangData = @json($barang ?? []);
        let itemCounter = 0;

        function formatRupiah(amount) {
            return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
        }

        function addItemRow() {
            console.log('Adding item row...');
            
            const container = document.getElementById('itemsContainer');
            
            // Create item row
            const itemDiv = document.createElement('div');
            itemDiv.className = 'item-row border rounded p-3 mb-3';
            itemDiv.dataset.index = itemCounter;
            
            itemDiv.innerHTML = `
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Barang *</label>
                    <select class="form-control barang-select" name="barang_id[]" required>
                        <option value="">-- Pilih Barang --</option>
                        ${barangData.map(b => `
                            <option value="${b.id_barang}" data-harga="${b.harga}" data-stok="${b.stok}">
                                ${b.nama_barang} - ${formatRupiah(b.harga)} (Stok: ${b.stok})
                            </option>
                        `).join('')}
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Jumlah *</label>
                    <input type="number" class="form-control jumlah-input"
                        name="jumlah[]" min="1" value="1" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Harga Satuan</label>
                    <input type="text" class="form-control harga-display" readonly>
                    <input type="hidden" name="harga_satuan[]" class="harga-input" value="">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Subtotal</label>
                    <input type="text" class="form-control subtotal-display" readonly>
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm w-100"
                            onclick="removeItemRow(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            `;
            
            container.appendChild(itemDiv);
            
            // Add event listeners
            const select = itemDiv.querySelector('.barang-select');
            const jumlahInput = itemDiv.querySelector('.jumlah-input');
            const hargaDisplay = itemDiv.querySelector('.harga-display');
            const hargaInput = itemDiv.querySelector('.harga-input');
            
            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const harga = selectedOption.dataset.harga || 0;
                const stok = selectedOption.dataset.stok || 0;
                
                hargaDisplay.value = formatRupiah(harga);
                hargaInput.value = harga;
                
                // Update max jumlah berdasarkan stok
                jumlahInput.max = stok;
                
                calculateSubtotal(itemDiv);
                updateSubmitButton();
                validateItemStok(itemDiv);
            });
            
            jumlahInput.addEventListener('input', function() {
                calculateSubtotal(itemDiv);
                updateSubmitButton();
                validateItemStok(this.closest('.item-row'));
            });
            
            itemCounter++;
            console.log('Item row added. Total items:', container.children.length);
            
            // Set default value if first item
            if (itemCounter === 1 && barangData.length > 0) {
                setTimeout(() => {
                    select.selectedIndex = 1;
                    select.dispatchEvent(new Event('change'));
                }, 100);
            }
        }

        function calculateSubtotal(row) {
            const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
            const harga = parseInt(row.querySelector('.harga-input').value) || 0;
            const subtotal = jumlah * harga;
            
            row.querySelector('.subtotal-display').value = formatRupiah(subtotal);
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
                const harga = parseInt(row.querySelector('.harga-input').value) || 0;
                total += jumlah * harga;
            });
            
            document.getElementById('totalHarga').textContent = formatRupiah(total);
            document.getElementById('total_harga_input').value = total;
            
            console.log('Total updated:', total);
        }

        function removeItemRow(button) {
            const row = button.closest('.item-row');
            if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
                row.remove();
                updateTotal();
                updateSubmitButton();
                console.log('Item removed. Total items:', document.querySelectorAll('.item-row').length);
            }
        }

        function validateItemStok(row) {
            const select = row.querySelector('.barang-select');
            const jumlahInput = row.querySelector('.jumlah-input');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption.value) {
                const stok = parseInt(selectedOption.dataset.stok) || 0;
                const jumlah = parseInt(jumlahInput.value) || 0;
                
                if (jumlah > stok) {
                    showNotification(`Jumlah melebihi stok. Stok tersedia: ${stok}`, 'warning');
                    jumlahInput.value = stok;
                    calculateSubtotal(row);
                    updateTotal();
                }
            }
        }

        function updateSubmitButton() {
            const submitBtn = document.getElementById('submitBtn');
            const totalItems = document.querySelectorAll('.item-row').length;
            const allValid = validateAllItems();
            
            if (totalItems > 0 && allValid) {
                submitBtn.disabled = false;
                const totalPrice = parseInt(document.getElementById('total_harga_input').value) || 0;
                submitBtn.innerHTML = `<i class="bi bi-check-circle me-2"></i>Pesan (${totalItems} item)`;
            } else {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<i class="bi bi-check-circle me-2"></i>Pesan Sekarang`;
            }
        }

        function validateAllItems() {
            let valid = true;
            document.querySelectorAll('.item-row').forEach(row => {
                const select = row.querySelector('.barang-select');
                const jumlahInput = row.querySelector('.jumlah-input');
                
                if (!select.value || !jumlahInput.value || parseInt(jumlahInput.value) < 1) {
                    valid = false;
                }
            });
            return valid;
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
            const totalItems = document.querySelectorAll('.item-row').length;
            if (totalItems === 0) {
                e.preventDefault();
                showNotification('Silakan tambahkan minimal satu barang!', 'warning');
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

            // Validasi stok barang
            let stockValid = true;
            let errorMessage = '';
            
            document.querySelectorAll('.item-row').forEach(row => {
                const select = row.querySelector('.barang-select');
                const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
                const selectedOption = select.options[select.selectedIndex];
                
                if (selectedOption.value) {
                    const stok = parseInt(selectedOption.dataset.stok) || 0;
                    const barangName = selectedOption.text.split(' - ')[0];
                    
                    if (jumlah > stok) {
                        stockValid = false;
                        errorMessage = `Stok ${barangName} tidak mencukupi. Stok tersedia: ${stok}`;
                    }
                }
            });

            if (!stockValid) {
                e.preventDefault();
                showNotification(errorMessage, 'danger');
                return false;
            }

            // Tampilkan loading
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses pesanan...';
            submitBtn.disabled = true;

            return true;
        });

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

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Loaded. Initializing...');
            console.log('Barang count:', barangData.length);
            
            // Add first item automatically
            if (barangData.length > 0) {
                addItemRow();
            }
            
            console.log('Initialization complete');
        });
    </script>
</body>
</html>