<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Barang;


class LaporanController extends Controller
{
    public function penjualan()
    {
        return view('laporan.penjualan', [
            'pesanan' => Pesanan::where('status','selesai')->get()
        ]);
    }

    public function stok()
    {
        return view('laporan.stok', [
            'barangs' => Barang::all()
        ]);
    }
}
