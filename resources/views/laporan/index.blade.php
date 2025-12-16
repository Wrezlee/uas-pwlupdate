@extends('layouts.app')

@section('title', 'Laporan')
@section('breadcrumb', 'Laporan')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                <h5>Laporan Penjualan</h5>
                <p class="text-muted">Rekap penjualan berdasarkan pesanan</p>
                <a href="{{ route('laporan.penjualan') }}" class="btn btn-primary">
                    Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-boxes fa-3x text-success mb-3"></i>
                <h5>Laporan Stok</h5>
                <p class="text-muted">Stok barang masuk & keluar</p>
                <a href="{{ route('laporan.stok') }}" class="btn btn-success">
                    Lihat Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
