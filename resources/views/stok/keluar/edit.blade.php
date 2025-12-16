@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header"><strong>Edit Stok Keluar</strong></div>

    <div class="card-body">
        <form method="POST" action="{{ route('stok.keluar.update', $stokKeluar->id_keluar) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Barang</label>
                <select name="id_barang" class="form-select" required>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id_barang }}"
                            {{ $barang->id_barang == $stokKeluar->id_barang ? 'selected' : '' }}>
                            {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Jumlah Keluar</label>
                <input type="number" name="jumlah"
                       value="{{ $stokKeluar->jumlah }}"
                       class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal"
                       value="{{ $stokKeluar->tanggal }}"
                       class="form-control" required>
            </div>

            <button class="btn btn-warning">Update</button>
            <a href="{{ route('stok.keluar.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </form>
    </div>
</div>
@endsection
