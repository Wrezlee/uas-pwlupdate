@extends('layouts.app')

@section('title', 'Data Barang')
@section('breadcrumb', 'Data Barang')

@push('styles')
<style>
    .badge-stock {
        min-width: 60px;
        display: inline-block;
    }
    .stock-low { background-color: #ffc107 !important; color: #000 !important; }
    .stock-out { background-color: #dc3545 !important; color: #fff !important; }
    .stock-good { background-color: #28a745 !important; color: #fff !important; }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    .action-buttons {
        min-width: 120px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-boxes me-2 text-primary"></i>
                            <strong>Data Barang</strong>
                        </h5>
                        @if($stats['total_barang'] > 0)
                        <small class="text-muted">
                            Total: {{ $stats['total_barang'] }} barang | 
                            Stok Gas: {{ $stats['total_gas'] }} | 
                            Stok Galon: {{ $stats['total_galon'] }}
                        </small>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('barang.export') }}" class="btn btn-outline-success btn-sm me-2">
                            <i class="fas fa-file-export me-1"></i> Export
                        </a>
                        <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> Tambah Barang
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body border-bottom bg-light">
                    <form method="GET" class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Cari nama barang..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="jenis" class="form-control form-control-sm">
                                <option value="">Semua Jenis</option>
                                <option value="gas" {{ request('jenis') == 'gas' ? 'selected' : '' }}>Gas</option>
                                <option value="galon" {{ request('jenis') == 'galon' ? 'selected' : '' }}>Galon</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sort_by" class="form-control form-control-sm">
                                <option value="id_barang" {{ request('sort_by') == 'id_barang' ? 'selected' : '' }}>ID</option>
                                <option value="nama_barang" {{ request('sort_by') == 'nama_barang' ? 'selected' : '' }}>Nama</option>
                                <option value="harga" {{ request('sort_by') == 'harga' ? 'selected' : '' }}>Harga</option>
                                <option value="stok" {{ request('sort_by') == 'stok' ? 'selected' : '' }}>Stok</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sort_order" class="form-control form-control-sm">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>↓ Desc</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>↑ Asc</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="card-body p-0">
                    @if($barangs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th>Nama Barang</th>
                                    <th width="12%" class="text-center">Jenis</th>
                                    <th width="15%" class="text-end">Harga</th>
                                    <th width="12%" class="text-center">Stok</th>
                                    <th width="20%" class="text-center">Total Nilai</th>
                                    <th width="16%" class="text-center action-buttons">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangs as $index => $barang)
                                @php
                                    $rowClass = '';
                                    if ($barang->stok <= 0) $rowClass = 'table-danger';
                                    elseif ($barang->stok <= 5) $rowClass = 'table-warning';
                                    $totalNilai = $barang->harga * $barang->stok;
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="text-center">{{ ($barangs->currentPage() - 1) * $barangs->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <i class="fas fa-{{ $barang->jenis == 'gas' ? 'fire' : 'wine-bottle' }} text-{{ $barang->jenis == 'gas' ? 'warning' : 'info' }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong>{{ $barang->nama_barang }}</strong>
                                                <br>
                                                <small class="text-muted">ID: {{ $barang->id_barang }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $barang->jenis == 'gas' ? 'warning' : 'info' }} text-dark px-3 py-1">
                                            {{ ucfirst($barang->jenis) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-primary fw-semibold">
                                            Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($barang->stok <= 0)
                                            <span class="badge badge-stock stock-out px-3 py-1">Habis</span>
                                        @elseif($barang->stok <= 5)
                                            <span class="badge badge-stock stock-low px-3 py-1">{{ $barang->stok }}</span>
                                            <small class="d-block text-danger">⚠ Menipis</small>
                                        @else
                                            <span class="badge badge-stock stock-good px-3 py-1">{{ $barang->stok }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-semibold">
                                            Rp {{ number_format($totalNilai, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('barang.edit', $barang->id_barang) }}" 
                                               class="btn btn-outline-primary btn-sm" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-warning btn-sm" 
                                                    onclick="updateStock({{ $barang->id_barang }})"
                                                    title="Update Stok">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm" 
                                                    onclick="confirmDelete({{ $barang->id_barang }}, '{{ $barang->nama_barang }}')"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary & Pagination -->
                    <div class="card-footer bg-white border-top">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="text-muted">
                                    Menampilkan {{ $barangs->firstItem() }} - {{ $barangs->lastItem() }} 
                                    dari {{ $barangs->total() }} barang
                                    @if(request()->has('search'))
                                        untuk "<strong>{{ request('search') }}</strong>"
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <nav class="float-end">
                                    {{ $barangs->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-box-open fa-4x text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-3">Belum ada data barang</h5>
                        <p class="text-muted mb-4">Tambahkan barang pertama Anda untuk mulai mengelola stok</p>
                        <a href="{{ route('barang.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Barang Pertama
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    @if($barangs->count() > 0)
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Total Barang</h6>
                            <h3 class="mb-0">{{ $stats['total_barang'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-box text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Stok Gas</h6>
                            <h3 class="mb-0">{{ $stats['total_gas'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-fire text-warning fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Stok Galon</h6>
                            <h3 class="mb-0">{{ $stats['total_galon'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-wine-bottle text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Total Nilai</h6>
                            <h5 class="mb-0 text-success">Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}</h5>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-coins text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Stok Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateStockForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="operation" id="operationType">
                    <div class="mb-3">
                        <label class="form-label">Jenis Operasi</label>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-success" onclick="setOperation('tambah')">
                                <i class="fas fa-plus me-1"></i> Tambah Stok
                            </button>
                            <button type="button" class="btn btn-warning" onclick="setOperation('kurangi')">
                                <i class="fas fa-minus me-1"></i> Kurangi Stok
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" 
                               min="1" max="9999" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="2" 
                                  placeholder="Contoh: Restok dari supplier..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentBarangId = null;
    
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus <strong>"${name}"</strong>?<br>
                  <small class="text-danger">Barang yang pernah digunakan dalam pesanan tidak dapat dihapus.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/barang/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Terhapus!',
                            'Barang berhasil dihapus.',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Gagal!',
                            data.message || 'Tidak dapat menghapus barang.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        'Terjadi kesalahan saat menghapus.',
                        'error'
                    );
                });
            }
        });
    }
    
    function updateStock(id) {
        currentBarangId = id;
        document.getElementById('operationType').value = '';
        document.querySelector('#updateStockForm input[name="jumlah"]').value = '';
        document.querySelector('#updateStockForm textarea[name="keterangan"]').value = '';
        
        const modal = new bootstrap.Modal(document.getElementById('updateStockModal'));
        modal.show();
    }
    
    function setOperation(type) {
        document.getElementById('operationType').value = type;
        const buttons = document.querySelectorAll('#updateStockForm .btn-group button');
        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
    }
    
    // Handle form submission
    document.getElementById('updateStockForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!document.getElementById('operationType').value) {
            Swal.fire('Peringatan', 'Pilih jenis operasi terlebih dahulu!', 'warning');
            return;
        }
        
        const formData = new FormData(this);
        
        fetch(`/barang/${currentBarangId}/update-stock`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire(
                    'Berhasil!',
                    data.message,
                    'success'
                ).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire(
                    'Gagal!',
                    data.message || 'Terjadi kesalahan.',
                    'error'
                );
            }
        })
        .catch(error => {
            Swal.fire('Error!', 'Terjadi kesalahan jaringan.', 'error');
        });
    });
    
    // Auto focus search on page load
    @if(request()->has('search'))
        document.querySelector('input[name="search"]').focus();
    @endif
    
    // Clear filters
    function clearFilters() {
        window.location.href = '{{ route("barang.index") }}';
    }
</script>
@endpush