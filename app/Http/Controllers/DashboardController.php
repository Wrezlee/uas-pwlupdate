<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Barang;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use App\Models\Notifikasi;
use App\Models\PesananDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Pesanan Hari Ini
        $pesananHariIni = Pesanan::whereDate('tanggal', Carbon::today())->count();

        // Pendapatan Hari Ini
        $pendapatanHariIni = Pesanan::whereDate('tanggal', Carbon::today())
            ->where('status', 'selesai')
            ->sum('total_harga');

        // Hitung Stok Gas dari tabel barang (sudah otomatis terupdate)
        $stokGas = Barang::where('jenis', 'gas')->sum('stok');

        // Hitung Stok Galon dari tabel barang (sudah otomatis terupdate)
        $stokGalon = Barang::where('jenis', 'galon')->sum('stok');

        // Pesanan Terbaru (5 terakhir)
        $pesananTerbaru = Pesanan::orderBy('tanggal', 'desc')
            ->orderBy('id_pesanan', 'desc')
            ->take(5)
            ->get();

        // Notifikasi Terbaru (5 terakhir)
        $notifikasi = Notifikasi::orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Barang Terlaris
        $barangTerlaris = PesananDetail::select('id_barang', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('id_barang')
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->with('barang')
            ->get()
            ->map(function($item) {
                return [
                    'nama_barang' => $item->barang->nama_barang ?? 'Unknown',
                    'total_terjual' => $item->total_terjual
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

        return view('dashboard', compact(
            'pesananHariIni',
            'pendapatanHariIni',
            'stokGas',
            'stokGalon',
            'pesananTerbaru',
            'notifikasi',
            'barangTerlaris',
            'maxTerjual',
            'chartLabels',
            'chartData'
        ));
    }
}