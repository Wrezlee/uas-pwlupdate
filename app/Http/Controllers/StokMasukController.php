<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\StokMasuk;
use App\Models\Notifikasi;
use Carbon\Carbon;

class StokMasukController extends Controller
{
    // ===============================
    // INDEX (WAJIB ADA)
    // ===============================
    public function index()
    {
        $stokMasuk = StokMasuk::with('barang')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('stok.masuk.index', compact('stokMasuk'));
    }

    // ===============================
    // CREATE
    // ===============================
    public function create()
    {
        $barangs = Barang::all();
        return view('stok.masuk.create', compact('barangs'));
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

        DB::transaction(function () use ($request) {

            StokMasuk::create($request->all());

            DB::table('barang')
                ->where('id_barang', $request->id_barang)
                ->increment('stok', $request->jumlah);
        });

        Notifikasi::create([
            'id_user'    => null,
            'id_pesanan' => null,
            'pesan'      => "Stok masuk baru: {$barang->nama_barang} (+{$request->jumlah})",
            'status'     => 'belum_dibaca',
            'tanggal'    => Carbon::now()
        ]);

        return redirect()->route('stok.masuk.index')
            ->with('success', 'Stok masuk berhasil ditambahkan');
    }

    // ===============================
    // EDIT
    // ===============================
    public function edit($id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);
        $barangs = Barang::all();

        return view('stok.masuk.edit', compact('stokMasuk', 'barangs'));
    }

    // ===============================
    // UPDATE
    // ===============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_barang' => 'required',
            'jumlah'    => 'required|integer|min:1',
            'tanggal'   => 'required|date'
        ]);

        $stokMasuk = StokMasuk::findOrFail($id);
        $barang = Barang::findOrFail($stokMasuk->id_barang);

        DB::transaction(function () use ($request, $stokMasuk) {

            $selisih = $request->jumlah - $stokMasuk->jumlah;

            $stokMasuk->update($request->all());

            DB::table('barang')
                ->where('id_barang', $request->id_barang)
                ->increment('stok', $selisih);
        });

        $tanda = $request->jumlah > $stokMasuk->jumlah ? '+' : '';
        $nilai = abs($request->jumlah - $stokMasuk->jumlah);

        Notifikasi::create([
            'id_user'    => null,
            'id_pesanan' => null,
            'pesan'      => "Update stok masuk: {$barang->nama_barang} ({$tanda}{$nilai})",
            'status'     => 'belum_dibaca',
            'tanggal'    => Carbon::now()
        ]);

        return redirect()->route('stok.masuk.index')
            ->with('success', 'Stok masuk berhasil diperbarui');
    }

    // ===============================
    // DESTROY
    // ===============================
    public function destroy($id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);
        $barang = Barang::findOrFail($stokMasuk->id_barang);

        DB::transaction(function () use ($stokMasuk) {

            DB::table('barang')
                ->where('id_barang', $stokMasuk->id_barang)
                ->decrement('stok', $stokMasuk->jumlah);

            $stokMasuk->delete();
        });

        Notifikasi::create([
            'id_user'    => null,
            'id_pesanan' => null,
            'pesan'      => "Hapus stok masuk: {$barang->nama_barang} (-{$stokMasuk->jumlah})",
            'status'     => 'belum_dibaca',
            'tanggal'    => Carbon::now()
        ]);

        return redirect()->route('stok.masuk.index')
            ->with('success', 'Stok masuk berhasil dihapus');
    }
}
