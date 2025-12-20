<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::now()->endOfDay();

        $pesananSelesai = Pesanan::where('status', 'selesai');

        $todaySales = (clone $pesananSelesai)
            ->whereDate('created_at', today())
            ->sum('total_harga');

        $yesterdaySales = (clone $pesananSelesai)
            ->whereDate('created_at', today()->subDay())
            ->sum('total_harga');

        $salesGrowth = $yesterdaySales > 0
            ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1)
            : 100;

        $completedOrders = (clone $pesananSelesai)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $lastMonthOrders = (clone $pesananSelesai)
            ->whereBetween(
                'created_at',
                [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]
            )
            ->count();

        $orderGrowth = $lastMonthOrders > 0
            ? round((($completedOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1)
            : 100;

        $totalProducts = Barang::count();
        $lowStockProducts = Barang::where('stok', '<=', 5)->count();

        $avgOrderValue = (clone $pesananSelesai)->avg('total_harga') ?? 0;
        $avgOrderGrowth = 0;

        $totalRevenue = (clone $pesananSelesai)->sum('total_harga');
        $totalOrders  = Pesanan::count();

        $totalItemsSold = DB::table('pesanan_detail')
            ->join('pesanan', 'pesanan_detail.id_pesanan', '=', 'pesanan.id_pesanan')
            ->where('pesanan.status', 'selesai')
            ->sum('pesanan_detail.jumlah');

        $monthlyProfit = $totalRevenue * 0.3;

        $topProducts = DB::table('pesanan_detail as pd')
            ->join('barang as b', 'pd.id_barang', '=', 'b.id_barang')
            ->join('pesanan as p', 'pd.id_pesanan', '=', 'p.id_pesanan')
            ->where('p.status', 'selesai')
            ->select(
                'b.nama_barang',
                'b.jenis',
                DB::raw('SUM(pd.jumlah) as total_sold'),
                DB::raw('SUM(pd.jumlah * pd.harga) as total_revenue')
            )
            ->groupBy('b.id_barang', 'b.nama_barang', 'b.jenis')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $chartLabels = [];
        $chartData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');

            $chartData[] = (clone $pesananSelesai)
                ->whereDate('created_at', $date)
                ->sum('total_harga');
        }

        $recentActivities = Pesanan::latest()
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'title' => "Pesanan #{$p->id_pesanan}",
                'description' => $p->nama_pembeli,
                'time' => $p->created_at->diffForHumans(),
                'color' => 'primary'
            ]);

        // ✅ FIX UTAMA (INI YANG SEBELUMNYA HILANG)
        $lastUpdate = Carbon::now();

        return view('laporan.index', compact(
            'todaySales',
            'salesGrowth',
            'completedOrders',
            'orderGrowth',
            'totalProducts',
            'lowStockProducts',
            'avgOrderValue',
            'avgOrderGrowth',
            'totalRevenue',
            'totalOrders',
            'totalItemsSold',
            'monthlyProfit',
            'topProducts',
            'recentActivities',
            'chartLabels',
            'chartData',
            'lastUpdate'
        ));
    }

    public function penjualan(Request $request)
    {
        return $this->index($request);
    }

}
