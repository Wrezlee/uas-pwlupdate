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
    protected const ITEMS_PER_PAGE = 20;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StokMasuk::with(['barang:id_barang,nama_barang,jenis']);
        
        // Filter by date
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }
        
        // Filter by barang
        if ($request->filled('id_barang')) {
            $query->where('id_barang', $request->id_barang);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('barang', function($q) use ($search) {
                $q->where('nama_barang', 'LIKE', "%{$search}%");
            });
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'tanggal');
        $sortOrder = $request->get('sort_order', 'desc');
        $validSortColumns = ['tanggal', 'jumlah', 'created_at'];
        $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'tanggal';
        
        $stokMasuk = $query->orderBy($sortBy, $sortOrder)
            ->paginate(self::ITEMS_PER_PAGE)
            ->withQueryString();
        
        // Get barang list for filter
        $barangs = Barang::orderBy('nama_barang')->get();
        
        // Statistics
        $totalStokMasuk = StokMasuk::count();
        $totalJumlah = StokMasuk::sum('jumlah');
        
        return view('stok.masuk.index', compact(
            'stokMasuk', 
            'barangs',
            'totalStokMasuk',
            'totalJumlah'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::where('stok', '>=', 0)
            ->orderBy('nama_barang')
            ->get(['id_barang', 'nama_barang', 'jenis', 'stok']);
        
        return view('stok.masuk.create', compact('barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'jumlah'    => 'required|integer|min:1|max:9999',
            'tanggal'   => 'required|date|before_or_equal:today',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'id_barang.required' => 'Pilih barang terlebih dahulu',
            'id_barang.exists'   => 'Barang tidak ditemukan',
            'jumlah.required'    => 'Jumlah harus diisi',
            'jumlah.min'         => 'Jumlah minimal 1',
            'jumlah.max'         => 'Jumlah maksimal 9999',
            'tanggal.required'   => 'Tanggal harus diisi',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini',
        ]);

        $barang = Barang::findOrFail($validated['id_barang']);

        DB::transaction(function () use ($validated, $barang) {
            // Create stok masuk record
            StokMasuk::create([
                'id_barang'  => $validated['id_barang'],
                'jumlah'     => $validated['jumlah'],
                'tanggal'    => $validated['tanggal'],
                'keterangan' => $validated['keterangan'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Update barang stock
            $barang->increment('stok', $validated['jumlah']);
        });

        // Create notification
        Notifikasi::create([
            'id_user'    => auth()->id(),
            'id_pesanan' => null,
            'pesan'      => "Stok masuk: {$barang->nama_barang} (+{$validated['jumlah']})",
            'status'     => 'belum_dibaca',
            'tanggal'    => Carbon::now()
        ]);

        return redirect()->route('stok.masuk.index')
            ->with('success', "Stok masuk berhasil ditambahkan. Stok {$barang->nama_barang} bertambah {$validated['jumlah']} unit.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $stokMasuk = StokMasuk::with('barang')->findOrFail($id);
        $barangs = Barang::where('stok', '>=', 0)
            ->orderBy('nama_barang')
            ->get(['id_barang', 'nama_barang', 'jenis', 'stok']);
        
        return view('stok.masuk.edit', compact('stokMasuk', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_barang'  => 'required|exists:barang,id_barang',
            'jumlah'     => 'required|integer|min:1|max:9999',
            'tanggal'    => 'required|date|before_or_equal:today',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $stokMasuk = StokMasuk::findOrFail($id);
        $barang = Barang::findOrFail($validated['id_barang']);
        
        // Jika barang diubah, perlu adjust stock
        $oldBarang = Barang::find($stokMasuk->id_barang);

        DB::transaction(function () use ($validated, $stokMasuk, $barang, $oldBarang) {
            // Adjust old barang stock
            if ($oldBarang->id_barang != $validated['id_barang']) {
                // Jika barang berubah, kurangi dari barang lama
                $oldBarang->decrement('stok', $stokMasuk->jumlah);
            }
            
            // Calculate difference
            $difference = $validated['jumlah'] - $stokMasuk->jumlah;
            
            // Update stok masuk
            $stokMasuk->update([
                'id_barang'  => $validated['id_barang'],
                'jumlah'     => $validated['jumlah'],
                'tanggal'    => $validated['tanggal'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Update new barang stock
            if ($oldBarang->id_barang != $validated['id_barang']) {
                // Jika barang berubah, tambah ke barang baru
                $barang->increment('stok', $validated['jumlah']);
            } else {
                // Jika barang sama, adjust berdasarkan selisih
                $barang->increment('stok', $difference);
            }
        });

        // Create notification
        $tanda = $validated['jumlah'] > $stokMasuk->jumlah ? '+' : '-';
        $selisih = abs($validated['jumlah'] - $stokMasuk->jumlah);
        
        Notifikasi::create([
            'id_user'    => auth()->id(),
            'id_pesanan' => null,
            'pesan'      => "Update stok masuk: {$barang->nama_barang} ({$tanda}{$selisih})",
            'status'     => 'belum_dibaca',
            'tanggal'    => Carbon::now()
        ]);

        return redirect()->route('stok.masuk.index')
            ->with('success', 'Stok masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stokMasuk = StokMasuk::with('barang')->findOrFail($id);
        $barang = $stokMasuk->barang;

        DB::transaction(function () use ($stokMasuk) {
            // Reduce barang stock
            $barang->decrement('stok', $stokMasuk->jumlah);
            
            // Delete stok masuk record
            $stokMasuk->delete();
        });

        // Create notification
        Notifikasi::create([
            'id_user'    => auth()->id(),
            'id_pesanan' => null,
            'pesan'      => "Hapus stok masuk: {$barang->nama_barang} (-{$stokMasuk->jumlah})",
            'status'     => 'belum_dibaca',
            'tanggal'    => Carbon::now()
        ]);

        return redirect()->route('stok.masuk.index')
            ->with('success', "Stok masuk berhasil dihapus. Stok {$barang->nama_barang} berkurang {$stokMasuk->jumlah} unit.");
    }

    /**
     * Export data to CSV
     */
    public function export(Request $request)
    {
        $stokMasuk = StokMasuk::with('barang')
            ->when($request->filled('start_date'), function($q) use ($request) {
                $q->whereDate('tanggal', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                $q->whereDate('tanggal', '<=', $request->end_date);
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="stok_masuk_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($stokMasuk) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'No', 
                'Tanggal', 
                'Nama Barang', 
                'Jenis', 
                'Jumlah Masuk', 
                'Keterangan', 
                'Tanggal Input'
            ]);
            
            // Data
            foreach ($stokMasuk as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->tanggal,
                    $item->barang->nama_barang ?? '-',
                    $item->barang->jenis ?? '-',
                    $item->jumlah,
                    $item->keterangan ?? '-',
                    $item->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}