@extends('layouts.app')

@section('title', 'Detail Pesanan')
@section('breadcrumb', 'Pesanan / Detail')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Detail Pesanan #{{ $pesanan->id_pesanan }}</h4>
                <div>
                    <a href="{{ route('pesanan.edit', $pesanan->id_pesanan) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('pesanan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Informasi Pesanan -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="mb-0">Informasi Pembeli</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Nama Pembeli:</strong><br>
                                    {{ $pesanan->nama_pembeli }}
                                </div>
                                <div class="mb-3">
                                    <strong>Nomor HP:</strong><br>
                                    {{ $pesanan->no_hp }}
                                </div>
                                <div class="mb-3">
                                    <strong>Alamat:</strong><br>
                                    {{ $pesanan->alamat }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="mb-0">Informasi Pesanan</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>ID Pesanan:</strong><br>
                                    #{{ $pesanan->id_pesanan }}
                                </div>
                                <div class="mb-3">
                                    <strong>Tanggal:</strong><br>
                                    {{ $pesanan->tanggal->format('d M Y') }}
                                </div>
                                <div class="mb-3">
                                    <strong>Status:</strong><br>
                                    @if($pesanan->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($pesanan->status == 'diproses')
                                        <span class="badge bg-warning text-dark">Diproses</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <strong>Total Harga:</strong><br>
                                    <span class="h5 text-primary">{{ 'Rp ' . number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Barang -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Detail Barang</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Jenis</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pesanan->details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->barang->nama_barang ?? 'Unknown' }}</td>
                                        <td>
                                            @if($detail->barang && $detail->barang->jenis == 'gas')
                                                <span class="badge bg-danger">Gas</span>
                                            @elseif($detail->barang && $detail->barang->jenis == 'galon')
                                                <span class="badge bg-info">Galon</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>{{ 'Rp ' . number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td>{{ 'Rp ' . number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Tidak ada detail barang
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Total:</th>
                                        <th>{{ 'Rp ' . number_format($pesanan->total_harga, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Timeline Status -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Timeline Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-secondary"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Pesanan Dibuat</h6>
                                    <p class="timeline-text">{{ $pesanan->tanggal->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            @if($pesanan->status == 'diproses' || $pesanan->status == 'selesai')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Pesanan Diproses</h6>
                                    <p class="timeline-text">Status diubah menjadi diproses</p>
                                </div>
                            </div>
                            @endif

                            @if($pesanan->status == 'selesai')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Pesanan Selesai</h6>
                                    <p class="timeline-text">Pesanan telah selesai diproses</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">Status Baru</label>
                        <select class="form-select" id="newStatus" name="status" required>
                            <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="diproses" {{ $pesanan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
}

.timeline-title {
    margin: 0 0 5px 0;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin: 0;
    font-size: 12px;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status form submission
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const status = formData.get('status');

        // Here you would make an AJAX request to update the status
        // For now, we'll just show a success message
        alert('Status berhasil diupdate menjadi: ' + status);

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
        modal.hide();

        // Reload page to show updated status
        location.reload();
    });
});
</script>
@endpush
