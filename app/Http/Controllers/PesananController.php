<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        // =====================
        // QUERY DASAR
        // =====================
        $query = Pesanan::query();

        // =====================
        // FILTER SEARCH
        // =====================
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_pembeli', 'like', '%' . $request->search . '%')
                ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        // =====================
        // FILTER STATUS
        // =====================
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // =====================
        // FILTER TANGGAL
        // =====================
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // =====================
        // DATA PESANAN (TABLE)
        // =====================
        $pesanan = $query
            ->orderByDesc('tanggal')
            ->paginate(10);

        // =====================
        // STATISTIK CARD
        // =====================
        $totalPesanan  = Pesanan::count();
        $pendingCount  = Pesanan::where('status', 'pending')->count();
        $diprosesCount = Pesanan::where('status', 'diproses')->count();

        // =====================
        // TOTAL REVENUE (SELESAI)
        // =====================
        $totalRevenue = Pesanan::where('status', 'selesai')
            ->sum('total_harga');

        // =====================
        // RETURN VIEW
        // =====================
        return view('pesanan.index', compact(
            'pesanan',
            'totalPesanan',
            'pendingCount',
            'diprosesCount',
            'totalRevenue'
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
            'nama_pembeli'        => 'required|string|max:100',
            'no_hp'               => 'required|string|max:20',
            'alamat'              => 'required|string',
            'tanggal'             => 'required|date',
            'status'              => 'required|in:pending,diproses,selesai',
            'barang'              => 'required|array|min:1',
            'barang.*.id'         => 'required|exists:barang,id_barang',
            'barang.*.jumlah'     => 'required|integer|min:1',
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

                $barang = Barang::where('id_barang', $item['id'])
                    ->lockForUpdate()
                    ->firstOrFail();

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

            $pesanan->update([
                'total_harga' => $total
            ]);
        });

        return redirect()
            ->route('pesanan.index')
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

        return redirect()
            ->route('pesanan.index')
            ->with('success', 'Pesanan berhasil dihapus');
    }
}
