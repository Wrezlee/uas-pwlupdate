<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    /* ================= ADMIN ================= */
    public function index(Request $request)
    {
        $query = Pesanan::query();

        // Filter search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pembeli', 'like', '%' . $request->search . '%')
                  ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $pesanan = $query->orderByDesc('tanggal')->paginate(10);

        $totalPesanan  = Pesanan::count();
        $pendingCount  = Pesanan::where('status', 'pending')->count();
        $diprosesCount = Pesanan::where('status', 'diproses')->count();
        $totalRevenue  = Pesanan::where('status', 'selesai')->sum('total_harga');

        return view('pesanan.index', compact(
            'pesanan', 'totalPesanan', 'pendingCount', 'diprosesCount', 'totalRevenue'
        ));
    }

    public function create()
    {
        $barang = Barang::orderBy('nama_barang')->get();
        return view('pesanan.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:100',
            'no_hp'        => 'required|string|max:20',
            'alamat'       => 'required|string',
            'tanggal'      => 'required|date',
            'status'       => 'required|in:pending,diproses,selesai',
            'barang'       => 'required|array|min:1',
            'barang.*.id'  => 'required|exists:barang,id_barang',
            'barang.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $pesanan = Pesanan::create([
                'nama_pembeli' => $request->nama_pembeli,
                'no_hp'        => $request->no_hp,
                'alamat'       => $request->alamat,
                'tanggal'      => $request->tanggal,
                'status'       => $request->status,
                'total_harga'  => 0,
            ]);

            $total = 0;

            foreach ($request->barang as $item) {
                $barang = Barang::lockForUpdate()->findOrFail($item['id']);
                $jumlah = $item['jumlah'];

                if ($barang->stok < $jumlah) {
                    abort(422, "Stok {$barang->nama_barang} tidak cukup");
                }

                $subtotal = $barang->harga * $jumlah;

                PesananDetail::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_barang'  => $barang->id_barang,
                    'jumlah'     => $jumlah,
                    'harga'      => $barang->harga,
                    'subtotal'   => $subtotal,
                ]);

                $barang->decrement('stok', $jumlah);
                $total += $subtotal;
            }

            $pesanan->update(['total_harga' => $total]);
        });

        return redirect()->route('pesanan.index')
                         ->with('success', 'Pesanan berhasil disimpan');
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load('details.barang');
        return view('pesanan.show', compact('pesanan'));
    }

    public function destroy(Pesanan $pesanan)
    {
        DB::transaction(function () use ($pesanan) {
            foreach ($pesanan->details as $detail) {
                Barang::where('id_barang', $detail->id_barang)
                      ->increment('stok', $detail->jumlah);
            }
            $pesanan->details()->delete();
            $pesanan->delete();
        });

        return redirect()->route('pesanan.index')
                         ->with('success', 'Pesanan berhasil dihapus');
    }

    /* ================= GUEST ================= */
    public function createForGuest()
    {
        $barang = Barang::orderBy('nama_barang')->get();
        return view('pesanan.create-guest', compact('barang'));
    }

    public function storeFromGuest(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:100',
            'no_hp'        => 'required|string|max:20',
            'email'        => 'nullable|email|max:255',
            'alamat'       => 'required|string',
            'barang_id'    => 'required|array|min:1',
            'barang_id.*'  => 'required|exists:barang,id_barang',
            'jumlah'       => 'required|array|min:1',
            'jumlah.*'     => 'required|integer|min:1',
            'harga_satuan' => 'required|array|min:1',
            'harga_satuan.*' => 'required|numeric',
            'catatan'      => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Hitung total harga
            $totalHarga = 0;
            foreach ($request->barang_id as $index => $barangId) {
                $jumlah = $request->jumlah[$index];
                $hargaSatuan = $request->harga_satuan[$index];
                $totalHarga += $jumlah * $hargaSatuan;
            }

            // Buat pesanan
            $pesanan = Pesanan::create([
                'nama_pembeli' => $request->nama_pembeli,
                'no_hp'        => $request->no_hp,
                'email'        => $request->email,
                'alamat'       => $request->alamat,
                'total_harga'  => $totalHarga,
                'status'       => 'pending',
                'tanggal'      => now(),
            ]);

            // Simpan detail pesanan dan kurangi stok
            foreach ($request->barang_id as $index => $barangId) {
                $barang = Barang::lockForUpdate()->findOrFail($barangId);
                $jumlah = $request->jumlah[$index];
                $hargaSatuan = $request->harga_satuan[$index];

                if ($barang->stok < $jumlah) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak cukup. Stok tersedia: {$barang->stok}");
                }

                PesananDetail::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_barang'  => $barangId,
                    'jumlah'     => $jumlah,
                    'harga'      => $hargaSatuan,
                    'subtotal'   => $jumlah * $hargaSatuan,
                    'catatan'    => $index === 0 ? $request->catatan : null,
                ]);

                $barang->decrement('stok', $jumlah);
            }

            DB::commit();

            return redirect()->route('pembeli.pesanan.success', ['id_pesanan' => $pesanan->id_pesanan])
                            ->with('info', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ========================
    // TAMBAHKAN METHOD INI!
    // ========================
    public function success($id_pesanan)
    {
        $pesanan = Pesanan::with('details.barang')->findOrFail($id_pesanan);
        return view('pesanan.success-guest', compact('pesanan'));
    }
}