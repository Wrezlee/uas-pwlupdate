<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Barang;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
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
            ->sum('total_harga') ?? 0;

        $stokGas = Barang::where('jenis', 'gas')->sum('stok') ?? 0;
        $stokGalon = Barang::where('jenis', 'galon')->sum('stok') ?? 0;

        /* =========================
         * PESANAN TERBARU
         * ========================= */
        $pesananTerbaru = Pesanan::orderByDesc('created_at')
            ->limit(5)
            ->get();

        /* =========================
         * CHART 7 HARI - PERBAIKAN DI SINI
         * ========================= */
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');

            $totalHari = Pesanan::whereDate('tanggal', $date)
                ->where('status', 'selesai')
                ->sum('total_harga');
            
            $chartData[] = $totalHari ?? 0; // Pastikan tidak null
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
         * NOTIFIKASI (UNTUK CARD NOTIFIKASI)
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

    /**
     * Method untuk mengambil notifikasi real-time
     */
    public function notifications(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get latest activities (1 jam terakhir)
        $notifications = collect();
        
        // 1. Pesanan masuk (dalam 1 jam terakhir)
        $newOrders = Pesanan::where('created_at', '>=', now()->subHour())
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($order) {
                return [
                    'id' => 'order_' . $order->id_pesanan,
                    'type' => 'pesanan',
                    'icon' => 'shopping-cart',
                    'title' => 'Pesanan Baru',
                    'description' => 'Pesanan dari ' . $order->nama_pembeli,
                    'detail' => 'ID: #' . str_pad($order->id_pesanan, 4, '0', STR_PAD_LEFT) . ' • Rp ' . number_format($order->total_harga, 0, ',', '.'),
                    'time' => $order->created_at->diffForHumans(),
                    'link' => route('pesanan.show', $order->id_pesanan),
                    'is_new' => $order->created_at->gt(now()->subMinutes(5))
                ];
            });
        
        // 2. Stok masuk (dalam 1 jam terakhir)
        $stockIns = StokMasuk::where('created_at', '>=', now()->subHour())
            ->with('barang')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($stock) {
                return [
                    'id' => 'stock_in_' . $stock->id,
                    'type' => 'stok-masuk',
                    'icon' => 'box',
                    'title' => 'Stok Masuk',
                    'description' => $stock->barang->nama_barang ?? 'Barang',
                    'detail' => 'Jumlah: ' . $stock->jumlah . ' unit',
                    'time' => $stock->created_at->diffForHumans(),
                    'link' => route('stok.masuk.index'),
                    'is_new' => $stock->created_at->gt(now()->subMinutes(5))
                ];
            });
        
        // 3. Stok keluar (dalam 1 jam terakhir)
        $stockOuts = StokKeluar::where('created_at', '>=', now()->subHour())
            ->with('barang')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($stock) {
                return [
                    'id' => 'stock_out_' . $stock->id,
                    'type' => 'stok-keluar',
                    'icon' => 'truck',
                    'title' => 'Stok Keluar',
                    'description' => $stock->barang->nama_barang ?? 'Barang',
                    'detail' => 'Jumlah: ' . $stock->jumlah . ' unit',
                    'time' => $stock->created_at->diffForHumans(),
                    'link' => route('stok.keluar.index'),
                    'is_new' => $stock->created_at->gt(now()->subMinutes(5))
                ];
            });
        
        // 4. Notifikasi stok hampir habis (kurang dari 10 unit)
        $lowStockItems = Barang::where('stok', '<', 10)
            ->where('stok', '>', 0)
            ->orderBy('stok', 'asc')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'id' => 'low_stock_' . $item->id_barang,
                    'type' => 'system',
                    'icon' => 'exclamation-triangle',
                    'title' => 'Stok Hampir Habis',
                    'description' => $item->nama_barang,
                    'detail' => 'Stok tersisa: ' . $item->stok . ' unit',
                    'time' => 'Baru saja',
                    'link' => route('barang.index'),
                    'is_new' => true
                ];
            });
        
        // 5. Notifikasi stok habis
        $outOfStockItems = Barang::where('stok', 0)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'id' => 'out_stock_' . $item->id_barang,
                    'type' => 'system',
                    'icon' => 'times-circle',
                    'title' => 'Stok Habis',
                    'description' => $item->nama_barang,
                    'detail' => 'Stok: 0 unit',
                    'time' => 'Baru saja',
                    'link' => route('barang.index'),
                    'is_new' => true
                ];
            });
        
        // Merge semua notifikasi dan sort by time
        $allNotifications = collect()
            ->merge($newOrders)
            ->merge($stockIns)
            ->merge($stockOuts)
            ->merge($lowStockItems)
            ->merge($outOfStockItems)
            ->sortByDesc('time')
            ->take(10);
        
        // Check if there are new notifications
        $hasNewNotifications = $allNotifications->contains('is_new', true);
        
        // Generate HTML for notifications
        $html = '';
        if ($allNotifications->isEmpty()) {
            $html = '<tr><td colspan="5" class="text-center text-muted py-4">Tidak ada aktivitas terbaru</td></tr>';
        } else {
            foreach ($allNotifications as $notification) {
                $badgeClass = match($notification['type']) {
                    'pesanan' => 'bg-primary',
                    'stok-masuk' => 'bg-success',
                    'stok-keluar' => 'bg-danger',
                    'system' => 'bg-warning text-dark',
                    default => 'bg-secondary'
                };
                
                $rowClass = $notification['is_new'] ? 'notification-new' : '';
                $rowClass .= ' notification-type-' . $notification['type'];
                
                $html .= '
                <tr class="' . $rowClass . '" data-id="' . $notification['id'] . '">
                    <td>
                        <small class="text-muted">' . $notification['time'] . '</small>
                    </td>
                    <td>
                        <span class="badge ' . $badgeClass . '">
                            <i class="fas fa-' . $notification['icon'] . ' me-1"></i>
                            ' . ucfirst(str_replace('-', ' ', $notification['type'])) . '
                        </span>
                    </td>
                    <td>
                        <div><strong>' . $notification['title'] . '</strong></div>
                        <small>' . $notification['description'] . '</small>
                    </td>
                    <td>
                        <small>' . $notification['detail'] . '</small>
                    </td>
                    <td>
                        ' . ($notification['is_new'] ? '<span class="badge bg-warning">Baru</span>' : '<span class="badge bg-secondary">Terbaca</span>') . '
                    </td>
                </tr>';
            }
        }
        
        return response()->json([
            'success' => true,
            'html' => $html,
            'hasNewNotifications' => $hasNewNotifications,
            'count' => $allNotifications->count()
        ]);
    }

    /**
     * Method untuk menandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead($notificationId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah dibaca',
            'notification_id' => $notificationId
        ]);
    }

    /**
     * Method untuk mengambil statistik dashboard (API)
     */
    public function getStats()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $today = Carbon::today();
        
        $pesananHariIni = Pesanan::whereDate('tanggal', $today)->count();
        $pendapatanHariIni = Pesanan::whereDate('tanggal', $today)
            ->where('status', 'selesai')
            ->sum('total_harga') ?? 0;
        
        $stokGas = Barang::where('jenis', 'gas')->sum('stok') ?? 0;
        $stokGalon = Barang::where('jenis', 'galon')->sum('stok') ?? 0;
        
        return response()->json([
            'success' => true,
            'data' => [
                'pesanan_hari_ini' => $pesananHariIni,
                'pendapatan_hari_ini' => $pendapatanHariIni,
                'stok_gas' => $stokGas,
                'stok_galon' => $stokGalon
            ]
        ]);
    }

    public function clearCache()
    {
        Cache::forget('dashboard_' . Auth::id());
        return back();
    }
}