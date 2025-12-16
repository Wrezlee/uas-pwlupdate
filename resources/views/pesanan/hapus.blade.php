    @extends('layouts.app')

    @section('title', 'Hapus Pesanan')
    @section('breadcrumb', 'Pesanan / Hapus')

    @section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Konfirmasi Hapus Pesanan
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian!</strong> Anda akan menghapus pesanan ini. Tindakan ini tidak dapat dibatalkan.
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Detail Pesanan #{{ $pesananItem['id'] }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Nama Pembeli:</strong><br>
                                            {{ $pesananItem['nama_pembeli'] }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>No. HP:</strong><br>
                                            {{ $pesananItem['no_hp'] }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>Tanggal:</strong><br>
                                            {{ \Carbon\Carbon::parse($pesananItem['tanggal'])->format('d/m/Y') }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Status:</strong><br>
                                            @if($pesananItem['status'] == 'pending')
                                                <span class="badge bg-secondary">Pending</span>
                                            @elseif($pesananItem['status'] == 'diproses')
                                                <span class="badge bg-warning text-dark">Diproses</span>
                                            @elseif($pesananItem['status'] == 'selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Alamat:</strong><br>
                                        {{ $pesananItem['alamat'] }}
                                    </div>

                                    <div class="mb-3">
                                        <strong>Total Harga:</strong><br>
                                        <span class="h5 text-primary">Rp {{ number_format($pesananItem['total_harga'], 0, ',', '.') }}</span>
                                    </div>

                                    @if($pesananItem['details'] && count($pesananItem['details']) > 0)
                                    <h6>Detail Barang:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Barang</th>
                                                    <th>Jumlah</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pesananItem['details'] as $detail)
                                                <tr>
                                                    <td>{{ $detail['nama_barang'] ?? 'Unknown' }}</td>
                                                    <td>{{ $detail['jumlah'] }}</td>
                                                    <td>Rp {{ number_format($detail['harga'], 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <th colspan="3" class="text-end">Total:</th>
                                                    <th>Rp {{ number_format($pesananItem['total_harga'], 0, ',', '.') }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-trash me-2"></i>
                                        Konfirmasi Hapus
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Apakah Anda yakin ingin menghapus pesanan ini?</p>

                                    <div class="d-grid gap-2">
                                        <form method="POST" action="{{ route('pesanan.destroy', $pesananItem['id']) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-lg"
                                                    onclick="return confirm('Apakah Anda benar-benar yakin ingin menghapus pesanan ini?')">
                                                <i class="fas fa-trash me-2"></i>
                                                Ya, Hapus Pesanan
                                            </button>
                                        </form>

                                        <a href="{{ route('pesanan.index') }}" class="btn btn-secondary btn-lg">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            Batal, Kembali ke Daftar
                                        </a>

                                        <a href="{{ route('pesanan.show', $pesananItem['id']) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye me-2"></i>
                                            Lihat Detail Lengkap
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Additional confirmation for delete button
        const deleteBtn = document.querySelector('button[onclick*="confirm"]');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                const confirmed = confirm('PERINGATAN: Pesanan yang dihapus tidak dapat dikembalikan!\n\nApakah Anda yakin ingin melanjutkan?');
                if (!confirmed) {
                    e.preventDefault();
                }
            });
        }
    });
    </script>
    @endpush
