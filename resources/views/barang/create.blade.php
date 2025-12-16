@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('breadcrumb', 'Tambah Barang')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <strong>Tambah Barang Baru</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Barang *</label>
                        <input type="text" name="nama_barang"
                               class="form-control @error('nama_barang') is-invalid @enderror"
                               value="{{ old('nama_barang') }}" required>
                        @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Barang *</label>
                        <select name="jenis"
                                class="form-select @error('jenis') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            <option value="gas">Gas</option>
                            <option value="galon">Galon</option>
                        </select>
                        @error('jenis')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga (Rp) *</label>
                        <input type="number" name="harga"
                               class="form-control @error('harga') is-invalid @enderror"
                               value="{{ old('harga') }}" min="0" required>
                        @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 🔥 STOK AWAL -->
                    <div class="mb-3">
                        <label class="form-label">Stok Awal *</label>
                        <input type="number" name="stok"
                               class="form-control @error('stok') is-invalid @enderror"
                               value="{{ old('stok', 0) }}" min="0" required>
                        @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Stok awal sebelum transaksi</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
