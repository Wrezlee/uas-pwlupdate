@extends('layouts.app')

@section('title','Tambah Stok Masuk')

@section('content')
<div class="card">
    <div class="card-header"><strong>Tambah Stok Masuk</strong></div>
    <div class="card-body">
        <form method="POST" action="{{ route('stok.masuk.store') }}">
            @csrf
            <div class="mb-3">
                <label>Barang</label>
                <select name="id_barang" class="form-select" required>
                    @foreach($barangs as $barang)
                    <option value="{{ $barang->id_barang }}">
                        {{ $barang->nama_barang }} ({{ ucfirst($barang->jenis) }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Jumlah</label>
                <input type="number" name="jumlah" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('stok.masuk.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
