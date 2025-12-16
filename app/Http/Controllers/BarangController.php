<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::orderBy('id_barang', 'desc')->get();
        return view('barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|max:50',
            'jenis'       => 'required|in:gas,galon',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
        ], [
            'nama_barang.required' => 'Nama barang harus diisi',
            'nama_barang.max'      => 'Nama barang maksimal 50 karakter',
            'jenis.required'       => 'Jenis barang harus dipilih',
            'jenis.in'             => 'Jenis barang hanya boleh gas atau galon',
            'harga.required'       => 'Harga harus diisi',
            'harga.numeric'        => 'Harga harus berupa angka',
            'harga.min'            => 'Harga tidak boleh negatif',
            'stok.required'        => 'Stok awal harus diisi',
            'stok.integer'         => 'Stok harus berupa angka',
            'stok.min'             => 'Stok tidak boleh negatif',
        ]);

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'jenis'       => $request->jenis,
            'harga'       => $request->harga,
            'stok'        => $request->stok, // 🔥 STOK AWAL
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     * ❗ STOK TIDAK DIUBAH DI SINI
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_barang' => 'required|max:50',
            'jenis'       => 'required|in:gas,galon',
            'harga'       => 'required|numeric|min:0',
        ], [
            'nama_barang.required' => 'Nama barang harus diisi',
            'nama_barang.max'      => 'Nama barang maksimal 50 karakter',
            'jenis.required'       => 'Jenis barang harus dipilih',
            'jenis.in'             => 'Jenis barang hanya boleh gas atau galon',
            'harga.required'       => 'Harga harus diisi',
            'harga.numeric'        => 'Harga harus berupa angka',
            'harga.min'            => 'Harga tidak boleh negatif',
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'jenis'       => $request->jenis,
            'harga'       => $request->harga,
            // ❌ STOK JANGAN DIUBAH
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);

        // Optional safety check
        if ($barang->stok > 0) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang masih memiliki stok!');
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
