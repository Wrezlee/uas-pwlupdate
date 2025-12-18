<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Barang;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function penjualan()
    {
        // Logika laporan penjualan
        $pesanan = Pesanan::with('barang')->latest()->get();
        
        return view('laporan.penjualan', compact('pesanan'));
    }

    public function stok()
    {
        // Logika laporan stok
        $barang = Barang::orderBy('nama_barang')->get();
        
        return view('laporan.stok', compact('barang'));
    }
}