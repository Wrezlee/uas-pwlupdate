<?php

namespace App\Http\Controllers;

use App\Models\StokKeluar;
use App\Models\Barang;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokKeluarController extends Controller
{
    // ===============================
// INDEX (WAJIB ADA)
// ===============================
    public function index()
    {
        $stokKeluar = StokKeluar::with('barang')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('stok.keluar.index', compact('stokKeluar'));
    }

    // ===============================
    // CREATE
    // ===============================
    public function create()
    {
        $barangs = Barang::all();
        return view('stok.keluar.create', compact('barangs'));
    }

    // ===============================
    // EDIT
    // ===============================
    public function edit($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);
        $barangs = Barang::all();

        return view('stok.keluar.edit', compact('stokKeluar', 'barangs'));
    }

    // ===============================
    // STORE
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'jumlah'    => 'required|integer|min:1',
            'tanggal'   => 'required|date'
        ]);

        $barang = Barang::findOrFail($request->id_barang);

        DB::transaction(function () use ($request, $barang) {

            if ($barang->stok < $request->jumlah) {
                abort(400, 'Stok tidak mencukupi');
            }

            StokKeluar::create($request->all());
            $barang->decrement('stok', $request->jumlah);
        });

        Notifikasi::create([
            'id_user'    => null,
            'id_pesanan' => null,
            'pesan'      => "Stok keluar baru: {$barang->nama_barang} (-{$request->jumlah})",
            'status'     => 'belum_dibaca',
            'tanggal'    => Carbon::now()
        ]);

        return redirect()->route('stok.keluar.index')
            ->with('success', 'Stok keluar berhasil ditambahkan');
    }

    // ===============================
    // UPDATE
    // ===============================
    public function update(Request $request, $id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);
        $barang     = Barang::findOrFail($stokKeluar->id_barang);

        DB::transaction(function () use ($request, $stokKeluar, $barang) {

            $barang->increment('stok', $stokKeluar->jumlah);

            if ($barang->stok < $request->jumlah) {
                abort(400, 'Stok tidak mencukupi');
            }

            $stokKeluar->update($request->all());
            $barang->decrement('stok', $request->jumlah);
        });

        Notifikasi::create([
            'id_user'    => null,
            'id_pesanan' => null,
            'pesan'      => "Update stok keluar: {$barang->nama_barang}",
            'status'     => 'belum_dibaca',
            'tanggal'    => Carbon::now()
        ]);

        return redirect()->route('stok.keluar.index')
            ->with('success', 'Stok keluar berhasil diperbarui');
    }

    // ===============================
    // DESTROY
    // ===============================
    public function destroy($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);
        $barang     = Barang::findOrFail($stokKeluar->id_barang);

        DB::transaction(function () use ($stokKeluar, $barang) {
            $barang->increment('stok', $stokKeluar->jumlah);
            $stokKeluar->delete();
        });

        Notifikasi::create([
            'id_user'    => null,
            'id_pesanan' => null,
            'pesan'      => "Hapus stok keluar: {$barang->nama_barang}",
            'status'     => 'belum_dibaca',
            'tanggal'    => Carbon::now()
        ]);

        return redirect()->route('stok.keluar.index')
            ->with('success', 'Stok keluar berhasil dihapus');
    }
}
