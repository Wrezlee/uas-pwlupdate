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
        min-width: 90px; /* Diperkecil karena tombol berkurang */
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
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
                        <button type="button" onclick="clearFilters()" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </button>
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
                                    <th width="20%" class="text-end">Total Nilai</th>
                                    <th width="12%" class="text-center action-buttons">Aksi</th>
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
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('barang.edit', $barang->id_barang) }}" 
                                               class="btn btn-outline-primary" 
                                               title="Edit" data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- HAPUS TOMBOL UPDATE STOK -->
                                            <button type="button" 
                                                    class="btn btn-outline-danger" 
                                                    onclick="confirmDelete({{ $barang->id_barang }}, '{{ $barang->nama_barang }}')"
                                                    title="Hapus" data-bs-toggle="tooltip">
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus barang <strong>"${name}"</strong>?<br>
                  <small class="text-danger">Barang yang pernah digunakan dalam pesanan atau masih memiliki stok tidak dapat dihapus.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`{{ url('barang') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        return { success: true, message: data.message };
                    } else {
                        throw new Error(data.message || 'Gagal menghapus barang');
                    }
                })
                .catch(error => {
                    throw new Error(error.message || 'Terjadi kesalahan');
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value && result.value.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: result.value.message || 'Barang berhasil dihapus.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                }
            }
        }).catch(error => {
            Swal.fire({
                title: 'Gagal!',
                text: error.message || 'Terjadi kesalahan saat menghapus.',
                icon: 'error',
                confirmButtonColor: '#d33',
            });
        });
    }
    
    // Clear filters
    function clearFilters() {
        window.location.href = '{{ route("barang.index") }}';
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush