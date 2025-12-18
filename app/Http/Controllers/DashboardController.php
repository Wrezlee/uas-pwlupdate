<?php
// app/Http\Controllers\DashboardController.php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Barang;
use App\Models\PesananDetail;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    // Cache duration in minutes
    private $cacheDuration = 10;
    
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $userId = Auth::id();
        
        // Gunakan caching untuk data yang tidak sering berubah
        $dashboardData = Cache::remember("dashboard_data_{$userId}", 
            $this->cacheDuration * 60, 
            function () {
                return $this->getDashboardData();
            }
        );
        
        return view('dashboard', $dashboardData);
    }
    
    private function getDashboardData()
    {
        try {
            $today = Carbon::today();
            $user = Auth::user();
            
            // 1. Basic Stats (gunakan parallel queries untuk performa)
            $stats = $this->getBasicStats($today);
            
            // 2. Pesanan Terbaru
            $pesananTerbaru = $this->getPesananTerbaru();
            
            // 3. Notifikasi (jika ada)
            $notifikasi = $this->getNotifikasi();
            
            // 4. Barang Terlaris
            $barangTerlaris = $this->getBarangTerlaris();
            
            // 5. Chart Data
            $chartData = $this->getChartData();
            
            // 6. Additional Stats untuk UI
            $additionalStats = [
                'totalPesanan' => Pesanan::count(),
                'pesananPending' => Pesanan::where('status', 'pending')->count(),
                'pesananDiproses' => Pesanan::where('status', 'diproses')->count(),
                'pesananSelesai' => Pesanan::where('status', 'selesai')->count(),
                'maxTerjual' => $barangTerlaris->max('total_terjual') ?? 1,
                'unreadNotifications' => $this->getUnreadNotificationsCount(),
            ];
            
            return array_merge(
                $stats,
                [
                    'pesananTerbaru' => $pesananTerbaru,
                    'notifikasi' => $notifikasi,
                    'barangTerlaris' => $barangTerlaris,
                    'chartLabels' => $chartData['labels'],
                    'chartData' => $chartData['data'],
                    'user' => $user,
                ],
                $additionalStats
            );
            
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return $this->getFallbackData();
        }
    }
    
    /**
     * Get basic stats with optimized queries
     */
    private function getBasicStats(Carbon $today)
    {
        return [
            'pesananHariIni' => Pesanan::whereDate('tanggal', $today)->count(),
            'pendapatanHariIni' => Pesanan::whereDate('tanggal', $today)
                ->where('status', 'selesai')
                ->sum('total_harga') ?: 0,
            'stokGas' => Barang::where('jenis', 'gas')->sum('stok') ?: 0,
            'stokGalon' => Barang::where('jenis', 'galon')->sum('stok') ?: 0,
        ];
    }
    
    /**
     * Get latest orders with optimized query
     */
    private function getPesananTerbaru()
    {
        return Pesanan::with(['details.barang:id,nama'])
            ->select(['id_pesanan', 'nama_pembeli', 'tanggal', 'total_harga', 'status'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_pesanan', 'desc')
            ->take(5)
            ->get()
            ->map(function ($pesanan) {
                return (object)[
                    'id_pesanan' => $pesanan->id_pesanan,
                    'nama_pembeli' => $pesanan->nama_pembeli,
                    'tanggal' => $pesanan->tanggal,
                    'total_harga' => $pesanan->total_harga,
                    'status' => $pesanan->status,
                ];
            });
    }
    
    /**
     * Get notifications safely
     */
    private function getNotifikasi()
    {
        try {
            if (class_exists(Notifikasi::class) && Schema::hasTable('notifikasi')) {
                return Notifikasi::select(['id', 'pesan', 'status', 'tanggal', 'dibaca'])
                    ->orderBy('tanggal', 'desc')
                    ->take(5)
                    ->get()
                    ->map(function ($notif) {
                        return (object)[
                            'id' => $notif->id,
                            'pesan' => $notif->pesan ?? 'No message',
                            'status' => $notif->status ?? 'belum_dibaca',
                            'tanggal' => $notif->tanggal ? Carbon::parse($notif->tanggal) : Carbon::now(),
                            'dibaca' => $notif->dibaca ?? false,
                        ];
                    });
            }
        } catch (\Exception $e) {
            \Log::warning('Notifications error: ' . $e->getMessage());
        }
        
        return collect([]);
    }
    
    /**
     * Get best selling products with optimized query
     */
    private function getBarangTerlaris()
    {
        try {
            $query = PesananDetail::query();
            
            if (Schema::hasColumn('pesanan_detail', 'harga_saat_itu')) {
                $barangTerlaris = $query->select([
                        'id_barang',
                        DB::raw('SUM(jumlah) as total_terjual'),
                        DB::raw('SUM(jumlah * harga_saat_itu) as total_pendapatan')
                    ]);
            } else {
                $barangTerlaris = $query->select([
                        'pesanan_detail.id_barang',
                        DB::raw('SUM(pesanan_detail.jumlah) as total_terjual'),
                        DB::raw('SUM(pesanan_detail.jumlah * barang.harga) as total_pendapatan')
                    ])
                    ->join('barang', 'pesanan_detail.id_barang', '=', 'barang.id');
            }
            
            return $barangTerlaris
                ->with(['barang:id,nama'])
                ->groupBy('id_barang')
                ->orderByDesc('total_terjual')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'nama_barang' => $item->barang->nama ?? 'Unknown',
                        'total_terjual' => $item->total_terjual ?? 0,
                        'total_pendapatan' => $item->total_pendapatan ?? 0,
                    ];
                });
                
        } catch (\Exception $e) {
            \Log::warning('Barang terlaris error: ' . $e->getMessage());
            return collect([]);
        }
    }
    
    /**
     * Get chart data for sales
     */
    private function getChartData()
    {
        try {
            $labels = [];
            $data = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $labels[] = $date->format('d M');
                
                $totalPenjualan = Pesanan::whereDate('tanggal', $date)
                    ->where('status', 'selesai')
                    ->sum('total_harga');
                
                $data[] = $totalPenjualan ?: 0;
            }
            
            return ['labels' => $labels, 'data' => $data];
            
        } catch (\Exception $e) {
            \Log::warning('Chart data error: ' . $e->getMessage());
            return [
                'labels' => ['Hari 1', 'Hari 2', 'Hari 3', 'Hari 4', 'Hari 5', 'Hari 6', 'Hari 7'],
                'data' => [0, 0, 0, 0, 0, 0, 0]
            ];
        }
    }
    
    /**
     * Get unread notifications count
     */
    private function getUnreadNotificationsCount()
    {
        try {
            if (class_exists(Notifikasi::class) && Schema::hasTable('notifikasi')) {
                return Notifikasi::where('dibaca', false)->count();
            }
        } catch (\Exception $e) {
            // Silent fail
        }
        
        return 0;
    }
    
    /**
     * Fallback data for error handling
     */
    private function getFallbackData()
    {
        $user = Auth::user();
        
        return [
            'pesananHariIni' => 0,
            'pendapatanHariIni' => 0,
            'stokGas' => 0,
            'stokGalon' => 0,
            'pesananTerbaru' => collect([]),
            'notifikasi' => collect([]),
            'barangTerlaris' => collect([]),
            'chartLabels' => [],
            'chartData' => [],
            'totalPesanan' => 0,
            'pesananPending' => 0,
            'pesananDiproses' => 0,
            'pesananSelesai' => 0,
            'maxTerjual' => 1,
            'unreadNotifications' => 0,
            'user' => $user,
            'error' => 'Data dashboard sedang tidak tersedia'
        ];
    }
    
    /**
     * Clear dashboard cache (API endpoint)
     */
    public function clearCache()
    {
        Cache::forget("dashboard_data_" . Auth::id());
        return response()->json(['success' => true, 'message' => 'Cache cleared']);
    }
}