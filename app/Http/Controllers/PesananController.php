<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Barang;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::with('details.barang')
            ->orderBy('id_pesanan', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id_pesanan,
                    'nama_pembeli' => $item->nama_pembeli,
                    'no_hp' => $item->no_hp,
                    'alamat' => $item->alamat,
                    'tanggal' => $item->tanggal->format('Y-m-d'),
                    'total_harga' => $item->total_harga,
                    'status' => $item->status,
                    'details' => $item->details->map(function($detail) {
                        return [
                            'nama_barang' => $detail->barang->nama_barang ?? 'Unknown',
                            'jumlah' => $detail->jumlah,
                            'harga' => $detail->harga,
                            'subtotal' => $detail->subtotal
                        ];
                    })
                ];
            });

        return view('pesanan.index', compact('pesanan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::all()->map(function($item) {
            return [
                'id' => $item->id_barang,
                'nama' => $item->nama_barang,
                'jenis' => $item->jenis,
                'harga' => $item->harga
            ];
        });

        return view('pesanan.create', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|max:100',
            'no_hp' => 'required|max:20',
            'alamat' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required|in:pending,diproses,selesai',
            'total_harga' => 'required|numeric|min:0',
            'barang' => 'required|array|min:1',
            'jumlah' => 'required|array|min:1',
            'harga' => 'required|array|min:1'
        ], [
            'nama_pembeli.required' => 'Nama pembeli harus diisi',
            'no_hp.required' => 'Nomor HP harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'tanggal.required' => 'Tanggal harus diisi',
            'barang.required' => 'Minimal harus ada 1 barang',
            'barang.min' => 'Minimal harus ada 1 barang'
        ]);

        DB::beginTransaction();
        try {
            // Check stock availability for new order
            foreach ($request->barang as $index => $id_barang) {
                $jumlah = $request->jumlah[$index];
                $barang = Barang::find($id_barang);
                if ($barang->stok < $jumlah) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak cukup. Stok tersedia: {$barang->stok}");
                }
            }

            // Create pesanan
            $pesanan = Pesanan::create([
                'nama_pembeli' => $request->nama_pembeli,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tanggal' => $request->tanggal,
                'total_harga' => $request->total_harga,
                'status' => $request->status
            ]);

            // Create pesanan details and decrement stock
            foreach ($request->barang as $index => $id_barang) {
                $jumlah = $request->jumlah[$index];
                $harga = $request->harga[$index];
                $subtotal = $jumlah * $harga;

                PesananDetail::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_barang' => $id_barang,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotal
                ]);

                // Decrement stock
                Barang::where('id_barang', $id_barang)->decrement('stok', $jumlah);
            }

            // Create notification
            Notifikasi::create([
                'id_user' => null,
                'id_pesanan' => $pesanan->id_pesanan,
                'pesan' => "Pesanan baru dari {$request->nama_pembeli}",
                'status' => 'belum_dibaca',
                'tanggal' => Carbon::now()
            ]);

            DB::commit();
            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pesanan = Pesanan::with('details.barang')->findOrFail($id);
        return view('pesanan.show', compact('pesanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pesanan = Pesanan::with('details.barang')->findOrFail($id);
        
        $barang = Barang::all()->map(function($item) {
            return [
                'id' => $item->id_barang,
                'nama' => $item->nama_barang,
                'jenis' => $item->jenis,
                'harga' => $item->harga
            ];
        });

        $pesananItem = [
            'id' => $pesanan->id_pesanan,
            'nama_pembeli' => $pesanan->nama_pembeli,
            'no_hp' => $pesanan->no_hp,
            'alamat' => $pesanan->alamat,
            'tanggal' => $pesanan->tanggal->format('Y-m-d'),
            'total_harga' => $pesanan->total_harga,
            'status' => $pesanan->status,
            'details' => $pesanan->details->map(function($detail) {
                return [
                    'id_barang' => $detail->id_barang,
                    'nama_barang' => $detail->barang->nama_barang ?? 'Unknown',
                    'jumlah' => $detail->jumlah,
                    'harga' => $detail->harga,
                    'subtotal' => $detail->subtotal
                ];
            })
        ];

        return view('pesanan.edit', compact('pesananItem', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_pembeli' => 'required|max:100',
            'no_hp' => 'required|max:20',
            'alamat' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required|in:pending,diproses,selesai',
            'total_harga' => 'required|numeric|min:0',
            'barang' => 'required|array|min:1',
            'jumlah' => 'required|array|min:1',
            'harga' => 'required|array|min:1'
        ]);

        DB::beginTransaction();
        try {
            $pesanan = Pesanan::findOrFail($id);

            // Get old details to restore stock
            $oldDetails = PesananDetail::where('id_pesanan', $id)->get();

            // Restore old stock
            foreach ($oldDetails as $detail) {
                Barang::where('id_barang', $detail->id_barang)->increment('stok', $detail->jumlah);
            }

            // Check stock availability for new order
            foreach ($request->barang as $index => $id_barang) {
                $jumlah = $request->jumlah[$index];
                $barang = Barang::find($id_barang);
                if ($barang->stok < $jumlah) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak cukup. Stok tersedia: {$barang->stok}");
                }
            }

            // Update pesanan
            $pesanan->update([
                'nama_pembeli' => $request->nama_pembeli,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tanggal' => $request->tanggal,
                'total_harga' => $request->total_harga,
                'status' => $request->status
            ]);

            // Delete old details
            PesananDetail::where('id_pesanan', $id)->delete();

            // Create new details and decrement stock
            foreach ($request->barang as $index => $id_barang) {
                $jumlah = $request->jumlah[$index];
                $harga = $request->harga[$index];
                $subtotal = $jumlah * $harga;

                PesananDetail::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_barang' => $id_barang,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotal
                ]);

                // Decrement stock
                Barang::where('id_barang', $id_barang)->decrement('stok', $jumlah);
            }

            DB::commit();
            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate pesanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show confirmation page before deleting
     */
    public function confirmDelete(string $id)
    {
        $pesanan = Pesanan::with('details.barang')->findOrFail($id);
        
        $pesananItem = [
            'id' => $pesanan->id_pesanan,
            'nama_pembeli' => $pesanan->nama_pembeli,
            'no_hp' => $pesanan->no_hp,
            'alamat' => $pesanan->alamat,
            'tanggal' => $pesanan->tanggal->format('Y-m-d'),
            'total_harga' => $pesanan->total_harga,
            'status' => $pesanan->status,
            'details' => $pesanan->details->map(function($detail) {
                return [
                    'nama_barang' => $detail->barang->nama_barang ?? 'Unknown',
                    'jumlah' => $detail->jumlah,
                    'harga' => $detail->harga,
                    'subtotal' => $detail->subtotal
                ];
            })->toArray()
        ];

        return view('pesanan.hapus', compact('pesananItem'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $pesanan = Pesanan::findOrFail($id);

            // Get details to restore stock
            $details = PesananDetail::where('id_pesanan', $id)->get();

            // Restore stock
            foreach ($details as $detail) {
                Barang::where('id_barang', $detail->id_barang)->increment('stok', $detail->jumlah);
            }

            // Delete details first (foreign key constraint)
            PesananDetail::where('id_pesanan', $id)->delete();

            // Delete pesanan
            $pesanan->delete();

            DB::commit();
            return redirect()->route('pesanan.index')
                ->with('success', 'Pesanan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
        }
    }
}