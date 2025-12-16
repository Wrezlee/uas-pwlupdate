@extends('layouts.app')

@section('title','Edit Stok Masuk')

@section('content')
<div class="card">
    <div class="card-header"><strong>Edit Stok Masuk</strong></div>
    <div class="card-body">
        <form>
            <div class="mb-3">
                <label>Barang</label>
                <select class="form-select">
                    @foreach($barangs as $barang)
                    <option {{ $stokMasuk->id_barang == $barang->id_barang ? 'selected' : '' }}>
                        {{ $barang->nama_barang }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Jumlah</label>
                <input type="number" class="form-control" value="{{ $stokMasuk->jumlah }}">
            </div>
            <a href="{{ route('stok.masuk.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
