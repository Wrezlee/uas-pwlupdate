@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="container-fluid px-4">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Pesanan
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalPesanan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Pending
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $pendingCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Diproses
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $diprosesCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Selesai (Revenue)
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-shopping-cart me-2"></i>Daftar Pesanan
            </h6>
            <div class="mt-2 mt-md-0">
                <a href="{{ route('pesanan.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Tambah Pesanan
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-body border-bottom">
            <form method="GET" id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Cari nama/no HP..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="start_date" class="form-control form-control-sm" 
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="end_date" class="form-control form-control-sm" 
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('pesanan.index') }}" class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="card-body p-0">
            @if($pesanan->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th>Pembeli</th>
                            <th width="12%">No HP</th>
                            <th width="12%">Tanggal</th>
                            <th width="15%">Total</th>
                            <th width="12%">Status</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan as $item)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">#{{ str_pad($item->id_pesanan, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <strong>{{ $item->nama_pembeli }}</strong><br>
                                <small class="text-muted">{{ Str::limit($item->alamat, 30) }}</small>
                            </td>
                            <td>
                                <small>{{ $item->no_hp }}</small>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="fw-bold text-primary">
                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusConfig = [
                                        'pending' => ['class' => 'secondary', 'icon' => 'clock'],
                                        'diproses' => ['class' => 'warning', 'icon' => 'cog'],
                                        'selesai' => ['class' => 'success', 'icon' => 'check-circle']
                                    ];
                                    $config = $statusConfig[$item->status] ?? ['class' => 'secondary', 'icon' => 'question'];
                                @endphp
                                <span class="badge bg-{{ $config['class'] }} px-3 py-1">
                                    <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <a href="{{ route('pesanan.show', $item->id_pesanan) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('pesanan.edit', $item->id_pesanan) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Status Dropdown -->
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-info dropdown-toggle" 
                                                data-bs-toggle="dropdown" title="Ubah Status">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item status-change" 
                                                   href="#" 
                                                   data-id="{{ $item->id_pesanan }}" 
                                                   data-status="pending">
                                                    <i class="fas fa-clock text-secondary me-2"></i>Pending
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item status-change" 
                                                   href="#" 
                                                   data-id="{{ $item->id_pesanan }}" 
                                                   data-status="diproses">
                                                    <i class="fas fa-cog text-warning me-2"></i>Diproses
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item status-change" 
                                                   href="#" 
                                                   data-id="{{ $item->id_pesanan }}" 
                                                   data-status="selesai">
                                                    <i class="fas fa-check-circle text-success me-2"></i>Selesai
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <!-- Delete Button -->
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            onclick="confirmDelete({{ $item->id_pesanan }}, '{{ $item->nama_pembeli }}')"
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

            <!-- Pagination -->
            <div class="card-footer bg-white border-top">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Menampilkan {{ $pesanan->firstItem() }} - {{ $pesanan->lastItem() }} 
                            dari {{ $pesanan->total() }} pesanan
                        </small>
                    </div>
                    <div class="col-md-6">
                        <nav class="float-end">
                            {{ $pesanan->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted mb-3">Belum ada data pesanan</h5>
                <p class="text-muted mb-4">Tambahkan pesanan pertama Anda untuk mulai mengelola transaksi</p>
                <a href="{{ route('pesanan.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Pesanan Pertama
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pesanan dari <strong id="deleteName"></strong>?</p>
                <p class="text-danger small">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Pesanan yang sudah selesai tidak dapat dihapus.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .status-badge {
        min-width: 90px;
        display: inline-block;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
// Confirm Delete Function
function confirmDelete(id, name) {
    document.getElementById('deleteName').textContent = name;
    document.getElementById('deleteForm').action = `/pesanan/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Status Change with AJAX
document.querySelectorAll('.status-change').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const id = this.dataset.id;
        const newStatus = this.dataset.status;
        
        if (!confirm(`Ubah status pesanan menjadi "${newStatus}"?`)) {
            return;
        }
        
        fetch(`/pesanan/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const toast = new bootstrap.Toast(document.getElementById('liveToast'));
                document.getElementById('toastMessage').textContent = data.message;
                toast.show();
                
                // Reload page after 1.5 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status');
        });
    });
});

// Quick Search with Debounce
let searchTimeout;
document.querySelector('input[name="search"]').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
});

// Set max date for date inputs to today
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.max = today;
    });
    
    // Auto-focus search input if it has value
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput.value) {
        searchInput.focus();
    }
});
</script>

<!-- Toast for status updates -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto"><i class="fas fa-check-circle text-success me-2"></i>Berhasil</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            Status berhasil diubah!
        </div>
    </div>
</div>
@endpush