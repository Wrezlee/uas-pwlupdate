@extends('layouts.app')

@section('title','Stok Masuk')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <strong>Data Stok Masuk</strong>
        <a href="{{ route('stok.masuk.create') }}" class="btn btn-primary btn-sm">
            Tambah Stok
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stokMasuk as $i => $row)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->barang->nama_barang }}</td>
                    <td>{{ ucfirst($row->barang->jenis) }}</td>
                    <td>{{ $row->jumlah }}</td>
                    <td>{{ date('d/m/Y', strtotime($row->tanggal)) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
