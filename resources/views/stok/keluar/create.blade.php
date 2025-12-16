@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header"><strong>Tambah Stok Keluar</strong></div>

    <div class="card-body">
        <form method="POST" action="{{ route('stok.keluar.store') }}">
            @csrf

            <div class="mb-3">
                <label>Barang</label>
                <select name="id_barang" class="form-select" required>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id_barang }}">
                            {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Jumlah Keluar</label>
                <input type="number" name="jumlah" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <button class="btn btn-danger">Simpan</button>
            <a href="{{ route('stok.keluar.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </form>
    </div>
</div>
@endsection
