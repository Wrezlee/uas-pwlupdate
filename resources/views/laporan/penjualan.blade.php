@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('breadcrumb', 'Laporan Penjualan')

@section('content')
<div class="card">
    <div class="card-header">
        <strong>Laporan Penjualan</strong>
    </div>

    <div class="card-body">
        @if($pesanan->count())
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Pembeli</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $p->nama_pembeli }}</td>
                        <td>Rp {{ number_format($p->total_harga,0,',','.') }}</td>
                        <td>
                            <span class="badge bg-success">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="3">Total Pendapatan</td>
                        <td colspan="2">
                            Rp {{ number_format($pesanan->sum('total_harga'),0,',','.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <p class="text-center text-muted">Belum ada data penjualan</p>
        @endif
    </div>
</div>
@endsection
