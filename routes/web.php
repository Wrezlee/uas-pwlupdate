<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// =======================
// PUBLIC ROUTES
// =======================

// Landing Page & Guest Access
Route::get('/', [AuthController::class, 'welcome'])->name('welcome');

// Guest buyer routes
Route::middleware('guest')->prefix('pembeli')->name('pembeli.')->group(function () {
    Route::get('/sebagai-pembeli', [AuthController::class, 'sebagaiPembeli'])
        ->name('sebagai.pembeli');
    
    Route::prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/create', [PesananController::class, 'createForGuest'])
            ->name('create');
        
        Route::post('/', [PesananController::class, 'storeFromGuest'])
            ->name('store');
        
        // PERBAIKI ROUTE INI - PASTIKAN SESUAI DENGAN METHOD DI CONTROLLER
        Route::get('/sukses/{id_pesanan}', [PesananController::class, 'success'])
            ->name('success')
            ->whereNumber('id_pesanan');
    });
});

// Authentication routes (guest only)
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.post');
});

// =======================
// AUTHENTICATED ROUTES
// =======================
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // =======================
    // BARANG MANAGEMENT
    // =======================
    Route::controller(BarangController::class)->prefix('barang')->name('barang.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/export', 'export')->name('export');
        Route::get('/{barang}/edit', 'edit')->name('edit');
        Route::put('/{barang}', 'update')->name('update');
        Route::delete('/{barang}', 'destroy')->name('destroy');
        Route::post('/{barang}/update-stock', 'updateStock')->name('update-stock');
        Route::get('/api/data', 'getBarangData')->name('api.data');
    });
    
    // =======================
    // PESANAN MANAGEMENT
    // =======================
    Route::resource('pesanan', PesananController::class)->except(['show']);
    
    // TAMBAHKAN ROUTE INI UNTUK UPDATE STATUS - PASTIKAN SEBELUM ROUTE SHOW
    Route::patch('/pesanan/{pesanan}/status', [PesananController::class, 'updateStatus'])
         ->name('pesanan.updateStatus');
    
    Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
    
    // =======================
    // STOK MANAGEMENT
    // =======================
    Route::prefix('stok-masuk')->name('stok.masuk.')->controller(StokMasukController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{stok_masuk}/edit', 'edit')->name('edit');
        Route::put('/{stok_masuk}', 'update')->name('update');
        Route::delete('/{stok_masuk}', 'destroy')->name('destroy');
    });

        
    Route::prefix('stok-keluar')->name('stok.keluar.')->controller(StokKeluarController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{stok_keluar}/edit', 'edit')->name('edit');
        Route::put('/{stok_keluar}', 'update')->name('update');
        Route::delete('/{stok_keluar}', 'destroy')->name('destroy');
    });

    
    // =======================
    // LAPORAN
    // =======================
    Route::prefix('laporan')->name('laporan.')->controller(LaporanController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/penjualan', 'penjualan')->name('penjualan');
        Route::get('/stok', 'stok')->name('stok');
        Route::get('/keuangan', 'keuangan')->name('keuangan');

        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/penjualan', 'exportPenjualan')->name('penjualan');
            Route::get('/stok', 'exportStok')->name('stok');
        });
    });

    
    // =======================
    // PROFILE
    // =======================
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::put('/update-password', 'updatePassword')->name('update-password'); // Perbaiki typo
    });

    
    // =======================
    // SETTINGS
    // =======================
    Route::view('/settings', 'settings.index')->name('settings');
    
    // =======================
    // API ROUTES
    // =======================
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::get('/pesanan/chart-data', [PesananController::class, 'chartData'])->name('pesanan.chart');
    });
});

// =======================
// FALLBACK ROUTES
// =======================
Route::fallback(function () {
    if (request()->expectsJson()) {
        return response()->json(['error' => 'Not Found'], 404);
    }
    return response()->view('errors.404', [], 404);
})->name('fallback');

// Maintenance mode (optional)
Route::get('/maintenance', function () {
    if (!app()->isDownForMaintenance()) {
        return redirect('/');
    }
    return response()->view('maintenance');
})->name('maintenance');