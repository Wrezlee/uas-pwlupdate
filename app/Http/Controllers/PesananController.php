<?php
// app/Http\Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Barang;
use App\Models\PesananDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Pesanan Hari Ini
        $pesananHariIni = Pesanan::whereDate('tanggal', Carbon::today())->count();

        // Pendapatan Hari Ini (hitung dari pesanan dengan status 'selesai')
        $pendapatanHariIni = Pesanan::whereDate('tanggal', Carbon::today())
            ->where('status', 'selesai')
            ->sum('total_harga');

        // Hitung Stok Gas (asumsi jenis barang ada kolom 'jenis')
        $stokGas = Barang::where('jenis', 'gas')->sum('stok');

        // Hitung Stok Galon
        $stokGalon = Barang::where('jenis', 'galon')->sum('stok');

        // Pesanan Terbaru (5 terakhir)
        $pesananTerbaru = Pesanan::with(['details.barang'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_pesanan', 'desc')
            ->take(5)
            ->get();

        // Barang Terlaris - dari pesanan_detail
        $barangTerlaris = PesananDetail::select([
                'id_barang',
                DB::raw('SUM(jumlah) as total_terjual'),
                DB::raw('SUM(jumlah * harga_saat_itu) as total_pendapatan')
            ])
            ->with(['barang:id,nama'])
            ->groupBy('id_barang')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'nama_barang' => $item->barang->nama ?? 'Unknown',
                    'total_terjual' => $item->total_terjual,
                    'total_pendapatan' => $item->total_pendapatan
                ];
            });

        $maxTerjual = $barangTerlaris->max('total_terjual') ?? 1;

        // Data untuk Grafik Penjualan 7 Hari Terakhir
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            
            $totalPenjualan = Pesanan::whereDate('tanggal', $date)
                ->where('status', 'selesai')
                ->sum('total_harga');
            
            $chartData[] = $totalPenjualan;
        }

        // Additional Stats
        $totalPesanan = Pesanan::count();
        $pesananPending = Pesanan::where('status', 'pending')->count();
        $pesananDiproses = Pesanan::where('status', 'diproses')->count();
        $pesananSelesai = Pesanan::where('status', 'selesai')->count();

        return view('dashboard', compact(
            'pesananHariIni',
            'pendapatanHariIni',
            'stokGas',
            'stokGalon',
            'pesananTerbaru',
            'barangTerlaris',
            'maxTerjual',
            'chartLabels',
            'chartData',
            'totalPesanan',
            'pesananPending',
            'pesananDiproses',
            'pesananSelesai'
        ));
    }
}