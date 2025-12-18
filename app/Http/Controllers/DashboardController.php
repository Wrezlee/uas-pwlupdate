<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cacheKey = 'dashboard_' . Auth::id();

        $data = Cache::remember($cacheKey, 600, function () {
            return $this->getDashboardData();
        });

        return view('dashboard', $data);
    }

    private function getDashboardData()
    {
        $today = Carbon::today();

        /* =========================
         * STATISTIK ATAS
         * ========================= */
        $pesananHariIni = Pesanan::whereDate('tanggal', $today)->count();

        $pendapatanHariIni = Pesanan::whereDate('tanggal', $today)
            ->where('status', 'selesai')
            ->sum('total_harga');

        $stokGas = Barang::where('jenis', 'gas')->sum('stok');
        $stokGalon = Barang::where('jenis', 'galon')->sum('stok');

        /* =========================
         * PESANAN TERBARU
         * ========================= */
        $pesananTerbaru = Pesanan::orderByDesc('created_at')
            ->limit(5)
            ->get();

        /* =========================
         * CHART 7 HARI
         * ========================= */
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');

            $chartData[] = Pesanan::whereDate('tanggal', $date)
                ->where('status', 'selesai')
                ->sum('total_harga');
        }

        /* =========================
         * BARANG TERLARIS
         * ========================= */
        $barangTerlaris = DB::table('pesanan_detail')
            ->join('barang', 'barang.id_barang', '=', 'pesanan_detail.id_barang')
            ->select(
                'barang.nama_barang',
                DB::raw('SUM(pesanan_detail.jumlah) as total_terjual'),
                DB::raw('SUM(pesanan_detail.subtotal) as total_pendapatan')
            )
            ->groupBy('barang.nama_barang')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'nama_barang' => $item->nama_barang,
                    'total_terjual' => (int) $item->total_terjual,
                    'total_pendapatan' => (int) $item->total_pendapatan,
                ];
            });

        $maxTerjual = $barangTerlaris->max('total_terjual') ?? 1;

        /* =========================
         * NOTIFIKASI (AMAN KOSONG)
         * ========================= */
        $notifikasi = collect();
        $unreadNotifications = 0;

        return compact(
            'pesananHariIni',
            'pendapatanHariIni',
            'stokGas',
            'stokGalon',
            'pesananTerbaru',
            'chartLabels',
            'chartData',
            'barangTerlaris',
            'maxTerjual',
            'notifikasi',
            'unreadNotifications'
        );
    }

    public function clearCache()
    {
        Cache::forget('dashboard_' . Auth::id());
        return back();
    }
}
