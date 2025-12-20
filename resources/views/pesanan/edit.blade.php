@extends('layouts.app')

@section('title', 'Edit Pesanan')
@section('breadcrumb', 'Pesanan / Edit')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Edit Pesanan #{{ str_pad($pesanan->id_pesanan, 4, '0', STR_PAD_LEFT) }}</h4>
            </div>
            <div class="card-body">
                <form id="pesananForm" method="POST" action="{{ route('pesanan.update', $pesanan->id_pesanan) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_pembeli" class="form-label">Nama Pembeli <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_pembeli') is-invalid @enderror"
                                       id="nama_pembeli" name="nama_pembeli"
                                       value="{{ old('nama_pembeli', $pesanan->nama_pembeli) }}" required>
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
                                       value="{{ old('no_hp', $pesanan->no_hp) }}" required>
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                  id="alamat" name="alamat" rows="3" required>{{ old('alamat', $pesanan->alamat) }}</textarea>
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
                                       value="{{ old('tanggal', $pesanan->tanggal) }}" required>
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
                                    <option value="pending" {{ old('status', $pesanan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="diproses" {{ old('status', $pesanan->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="selesai" {{ old('status', $pesanan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
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
                                <!-- Existing items will be loaded here via JavaScript -->
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <strong>Total Harga:</strong>
                                                <strong id="totalHarga">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="total_harga" id="total_harga_input" value="{{ $pesanan->total_harga }}">

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
const existingDetails = @json($pesanan->details ?? []);

let itemCounter = 0;

function formatRupiah(amount) {
    return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
}

function addItemRow(existingItem = null) {
    const container = document.getElementById('itemsContainer');
    
    const itemDiv = document.createElement('div');
    itemDiv.className = 'item-row border rounded p-3 mb-3';
    itemDiv.dataset.index = itemCounter;
    
    // Build options HTML
    let optionsHTML = '<option value="">-- Pilih Barang --</option>';
    barangData.forEach(b => {
        const selected = existingItem && existingItem.id_barang == b.id_barang ? 'selected' : '';
        optionsHTML += `<option value="${b.id_barang}" data-harga="${b.harga}" ${selected}>${b.nama_barang} - ${formatRupiah(b.harga)}</option>`;
    });
    
    // Set values
    const jumlahValue = existingItem ? existingItem.jumlah : 1;
    const hargaValue = existingItem ? existingItem.harga : '';
    const subtotalValue = existingItem ? existingItem.subtotal : 0;
    
    itemDiv.innerHTML = `
        <div class="row align-items-end">
            <div class="col-md-4">
                <label class="form-label">Barang <span class="text-danger">*</span></label>
                <select class="form-select barang-select" name="barang[${itemCounter}][id]" required>
                    ${optionsHTML}
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                <input type="number" class="form-control jumlah-input" name="barang[${itemCounter}][jumlah]" 
                       min="1" value="${jumlahValue}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Harga Satuan</label>
                <input type="number" class="form-control harga-input" value="${hargaValue}" readonly>
                <input type="hidden" name="barang[${itemCounter}][harga]" value="${hargaValue}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Subtotal</label>
                <input type="text" class="form-control subtotal-display" value="${formatRupiah(subtotalValue)}" readonly>
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
    const hiddenHarga = itemDiv.querySelector('input[type="hidden"]');
    
    select.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const harga = selectedOption.dataset.harga || 0;
        hargaInput.value = harga;
        hiddenHarga.value = harga;
        calculateSubtotal(itemDiv);
    });
    
    jumlahInput.addEventListener('input', function() {
        calculateSubtotal(itemDiv);
    });
    
    itemCounter++;
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
}

function removeItemRow(button) {
    const row = button.closest('.item-row');
    row.remove();
    updateTotal();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Load existing items
    if (existingDetails && existingDetails.length > 0) {
        existingDetails.forEach(item => {
            addItemRow(item);
        });
    } else {
        addItemRow();
    }
});
</script>
@endpush