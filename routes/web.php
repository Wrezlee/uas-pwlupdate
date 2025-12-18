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
        
        Route::get('/sukses/{pesanan}', [PesananController::class, 'success'])
            ->name('success')
            ->whereNumber('pesanan');
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
    Route::prefix('pesanan/{pesanan}')->controller(PesananController::class)->group(function () {
        Route::get('/', 'show')->name('pesanan.show');
        Route::patch('/status', 'updateStatus')->name('pesanan.status.update');
    });
    
    // =======================
    // STOK MANAGEMENT
    // =======================
    Route::prefix('stok-masuk')->name('stok.masuk.')->controller(StokMasukController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/create', 'create');
        Route::post('/', 'store');
        Route::get('/{stok_masuk}/edit', 'edit');
        Route::put('/{stok_masuk}', 'update');
        Route::delete('/{stok_masuk}', 'destroy');
    });
    
    Route::prefix('stok-keluar')->name('stok.keluar.')->controller(StokKeluarController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/create', 'create');
        Route::post('/', 'store');
        Route::get('/{stok_keluar}/edit', 'edit');
        Route::put('/{stok_keluar}', 'update');
        Route::delete('/{stok_keluar}', 'destroy');
    });
    
    // =======================
    // LAPORAN
    // =======================
    Route::prefix('laporan')->name('laporan.')->controller(LaporanController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/penjualan', 'penjualan');
        Route::get('/stok', 'stok');
        Route::get('/keuangan', 'keuangan');
        
        // Export
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/penjualan', 'exportPenjualan');
            Route::get('/stok', 'exportStok');
        });
    });
    
    // =======================
    // PROFILE
    // =======================
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/edit', 'edit');
        Route::put('/update', 'update');
        Route::put('/update-password', 'updatePassword');
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
