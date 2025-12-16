<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\StokKeluarController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// =======================
// PESANAN
// =======================
Route::get('pesanan/{id}/hapus', [\App\Http\Controllers\PesananController::class, 'confirmDelete'])
    ->name('pesanan.hapus');
Route::resource('pesanan', \App\Http\Controllers\PesananController::class);

// =======================
// BARANG
// =======================
Route::resource('barang', \App\Http\Controllers\BarangController::class);


// =======================
// LAPORAN
// =======================
Route::prefix('laporan')->name('laporan.')->group(function () {

    Route::get('/', function () {
        return view('laporan.index');
    })->name('index');

    Route::get('/penjualan', [App\Http\Controllers\LaporanController::class, 'penjualan'])->name('penjualan');

    Route::get('/stok', [App\Http\Controllers\LaporanController::class, 'stok'])->name('stok');
});

// =======================
// USERS
// =======================
Route::prefix('users')->name('users.')->group(function () {

    Route::get('/', function () {
        return view('users.index');
    })->name('index');

    Route::get('/create', function () {
        return view('users.create');
    })->name('create');

    Route::post('/', function () {
        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    })->name('store');

    Route::get('/{id}/edit', function ($id) {
        return view('users.edit', compact('id'));
    })->name('edit');

    Route::put('/{id}', function ($id) {
        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    })->name('update');

    Route::delete('/{id}', function ($id) {
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    })->name('destroy');
});

// =======================
// PROFILE & SETTINGS
// =======================
Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/settings', function () {
    return view('settings');
})->name('settings');

// Logout
Route::post('/logout', function () {
    return redirect('/')->with('success', 'Berhasil logout');
})->name('logout');


// Stok Masuk
Route::resource('stok/masuk', StokMasukController::class)
    ->names([
        'index'   => 'stok.masuk.index',
        'create'  => 'stok.masuk.create',
        'store'   => 'stok.masuk.store',
        'edit'    => 'stok.masuk.edit',
        'update'  => 'stok.masuk.update',
        'destroy' => 'stok.masuk.destroy',
    ]);

// =======================
// STOK KELUAR
// =======================
Route::prefix('stok/keluar')->name('stok.keluar.')->group(function () {
    Route::get('/', [StokKeluarController::class, 'index'])->name('index');
    Route::get('/create', [StokKeluarController::class, 'create'])->name('create');
    Route::post('/', [StokKeluarController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [StokKeluarController::class, 'edit'])->name('edit');
    Route::put('/{id}', [StokKeluarController::class, 'update'])->name('update');
    Route::delete('/{id}', [StokKeluarController::class, 'destroy'])->name('destroy');
});
