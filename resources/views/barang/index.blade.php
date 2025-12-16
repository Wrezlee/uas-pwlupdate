@extends('layouts.app')

@section('title', 'Data Barang')
@section('breadcrumb', 'Data Barang')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Data Barang</strong>
        <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Barang
        </a>
    </div>

    <div class="card-body">
        @if($barangs->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Barang</th>
                        <th width="12%">Jenis</th>
                        <th width="18%">Harga</th>
                        <th width="10%">Stok</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangs as $index => $barang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>
                            <span class="badge text-bg-{{ $barang->jenis == 'gas' ? 'warning' : 'info' }}">
                                {{ ucfirst($barang->jenis) }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>

                        <!-- 🔥 STOK -->
                        <td>
                            @if($barang->stok <= 0)
                                <span class="badge text-bg-danger">Habis</span>
                            @elseif($barang->stok <= 5)
                                <span class="badge text-bg-warning">Menipis</span>
                            @else
                                <span class="badge text-bg-success">{{ $barang->stok }}</span>
                            @endif
                        </td>

                        <!-- AKSI -->
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('barang.edit', $barang->id_barang) }}"
                                   class="btn btn-warning">
                                    Edit
                                </a>
                                <button class="btn btn-danger"
                                        onclick="confirmDelete({{ $barang->id_barang }})">
                                    Hapus
                                </button>
                            </div>

                            <form id="delete-form-{{ $barang->id_barang }}"
                                  action="{{ route('barang.destroy', $barang->id_barang) }}"
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-box-open fa-2x mb-3"></i>
            <p>Belum ada data barang</p>
            <a href="{{ route('barang.create') }}" class="btn btn-primary">
                Tambah Barang Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush