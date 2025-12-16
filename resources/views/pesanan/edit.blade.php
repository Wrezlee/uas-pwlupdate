@extends('layouts.app')

@section('title', 'Edit Pesanan')
@section('breadcrumb', 'Pesanan / Edit')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Edit Pesanan #{{ $pesananItem['id'] }}</h4>
            </div>
            <div class="card-body">
                <form id="pesananForm" method="POST" action="{{ route('pesanan.update', $pesananItem['id']) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_pembeli" class="form-label">Nama Pembeli <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_pembeli') is-invalid @enderror"
                                       id="nama_pembeli" name="nama_pembeli"
                                       value="{{ old('nama_pembeli', $pesananItem['nama_pembeli']) }}" required>
                                @error('nama_pembeli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('no_hp') is-invalid @enderror"
                                       id="no_hp" name="no_hp"
                                       value="{{ old('no_hp', $pesananItem['no_hp']) }}" required>
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                  id="alamat" name="alamat" rows="3" required>{{ old('alamat', $pesananItem['alamat']) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                       id="tanggal" name="tanggal"
                                       value="{{ old('tanggal', $pesananItem['tanggal']) }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status">
                                    <option value="pending" {{ old('status', $pesananItem['status']) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="diproses" {{ old('status', $pesananItem['status']) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="selesai" {{ old('status', $pesananItem['status']) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Detail Barang -->
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Detail Barang</h5>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addItemRow()">
                                <i class="fas fa-plus"></i> Tambah Barang
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="itemsContainer">
                                <!-- Existing items will be loaded here -->
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <strong>Total Harga:</strong>
                                                <strong id="totalHarga">Rp {{ number_format($pesananItem['total_harga'], 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="total_harga" id="total_harga_input" value="{{ $pesananItem['total_harga'] }}">

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('pesanan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Data barang dari controller
const barangData = @json($barang ?? []);

// Existing pesanan data
const existingPesanan = @json($pesananItem);

let itemCounter = 0;

console.log('Barang Data:', barangData);
console.log('Existing Pesanan:', existingPesanan);

function formatRupiah(amount) {
    return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
}

function addItemRow(existingItem = null) {
    console.log('Adding item row...', existingItem);
    
    const container = document.getElementById('itemsContainer');
    
    // Create item row
    const itemDiv = document.createElement('div');
    itemDiv.className = 'item-row border rounded p-3 mb-3';
    itemDiv.dataset.index = itemCounter;
    
    // Build options HTML
    let optionsHTML = '<option value="">-- Pilih Barang --</option>';
    barangData.forEach(b => {
        const selected = existingItem && existingItem.id_barang == b.id ? 'selected' : '';
        optionsHTML += `<option value="${b.id}" data-harga="${b.harga}" ${selected}>${b.nama} - ${formatRupiah(b.harga)}</option>`;
    });
    
    // Set values
    const jumlahValue = existingItem ? existingItem.jumlah : 1;
    const hargaValue = existingItem ? existingItem.harga : '';
    const subtotalValue = existingItem ? formatRupiah(existingItem.subtotal) : '';
    
    itemDiv.innerHTML = `
        <div class="row align-items-end">
            <div class="col-md-4">
                <label class="form-label">Barang <span class="text-danger">*</span></label>
                <select class="form-select barang-select" name="barang[]" required>
                    ${optionsHTML}
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                <input type="number" class="form-control jumlah-input" name="jumlah[]" min="1" value="${jumlahValue}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Harga Satuan</label>
                <input type="number" class="form-control harga-input" name="harga[]" value="${hargaValue}" readonly required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Subtotal</label>
                <input type="text" class="form-control subtotal-display" value="${subtotalValue}" readonly>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeItemRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(itemDiv);
    
    // Add event listeners
    const select = itemDiv.querySelector('.barang-select');
    const jumlahInput = itemDiv.querySelector('.jumlah-input');
    const hargaInput = itemDiv.querySelector('.harga-input');
    
    select.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const harga = selectedOption.dataset.harga || 0;
        hargaInput.value = harga;
        calculateSubtotal(itemDiv);
    });
    
    jumlahInput.addEventListener('input', function() {
        calculateSubtotal(itemDiv);
    });
    
    itemCounter++;
    console.log('Item row added. Total items:', container.children.length);
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
    row.remove();
    updateTotal();
    console.log('Item removed. Total items:', document.querySelectorAll('.item-row').length);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Loaded. Initializing edit form...');
    
    // Load existing items
    if (existingPesanan.details && existingPesanan.details.length > 0) {
        console.log('Loading existing items:', existingPesanan.details.length);
        existingPesanan.details.forEach(item => {
            addItemRow(item);
        });
    } else {
        console.log('No existing items, adding empty row');
        addItemRow();
    }
    
    console.log('Initialization complete');
});
</script>
@endpush