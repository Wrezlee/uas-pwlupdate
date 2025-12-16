@extends('layouts.app')

@section('title', 'Laporan Stok')
@section('breadcrumb', 'Laporan Stok')

@section('content')
<div class="card">
    <div class="card-header">
        <strong>Laporan Stok Barang</strong>
    </div>

    <div class="card-body">
        @if($barangs->count())
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jenis</th>
                        <th>Stok Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangs as $i => $barang)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>
                            @if($barang->jenis == 'gas')
                                <span class="badge bg-warning text-dark">Gas</span>
                            @else
                                <span class="badge bg-info">Galon</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success">
                                {{ $barang->stok }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center text-muted">Belum ada data stok</p>
        @endif
    </div>
</div>
@endsection
