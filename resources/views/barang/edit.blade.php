@extends('layouts.app')

@section('title', 'Edit Barang')
@section('breadcrumb', 'Edit Barang')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <strong>Edit Barang</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Barang *</label>
                        <input type="text" name="nama_barang"
                               class="form-control"
                               value="{{ $barang->nama_barang }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis *</label>
                        <select name="jenis" class="form-select" required>
                            <option value="gas" {{ $barang->jenis == 'gas' ? 'selected' : '' }}>Gas</option>
                            <option value="galon" {{ $barang->jenis == 'galon' ? 'selected' : '' }}>Galon</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga *</label>
                        <input type="number" name="harga"
                               class="form-control"
                               value="{{ $barang->harga }}" required>
                    </div>

                    <!-- 🔒 STOK READ ONLY -->
                    <div class="mb-3">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="number" class="form-control"
                               value="{{ $barang->stok }}" readonly>
                        <small class="text-muted">
                            Stok diubah melalui menu Stok Masuk / Keluar
                        </small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
