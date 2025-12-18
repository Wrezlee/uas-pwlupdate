<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    // Cache duration in minutes
    protected const CACHE_DURATION = 10;
    protected const ITEMS_PER_PAGE = 15;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cacheKey = 'barang_index_' . md5(serialize($request->all()));
        
        $data = Cache::remember($cacheKey, now()->addMinutes(self::CACHE_DURATION), function () use ($request) {
            $query = Barang::query();
            
            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_barang', 'LIKE', "%{$search}%")
                      ->orWhere('jenis', 'LIKE', "%{$search}%");
                });
            }
            
            // Filter by jenis
            if ($request->has('jenis') && in_array($request->jenis, ['gas', 'galon'])) {
                $query->where('jenis', $request->jenis);
            }
            
            // Sort functionality
            $sortBy = $request->get('sort_by', 'id_barang');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $validSortColumns = ['id_barang', 'nama_barang', 'jenis', 'harga', 'stok', 'created_at'];
            $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'id_barang';
            $sortOrder = $sortOrder === 'asc' ? 'asc' : 'desc';
            
            $barangs = $query->orderBy($sortBy, $sortOrder)
                ->paginate(self::ITEMS_PER_PAGE)
                ->withQueryString();
            
            // Statistics
            $stats = [
                'total_barang' => Barang::count(),
                'total_gas' => Barang::where('jenis', 'gas')->sum('stok'),
                'total_galon' => Barang::where('jenis', 'galon')->sum('stok'),
                'total_nilai' => Barang::sum(DB::raw('harga * stok')),
            ];
            
            return [
                'barangs' => $barangs,
                'stats' => $stats,
                'filters' => $request->only(['search', 'jenis', 'sort_by', 'sort_order'])
            ];
        });
        
        return view('barang.index', $data);
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
        $validated = $request->validate([
            'nama_barang' => 'required|max:50|unique:barang,nama_barang',
            'jenis'       => ['required', Rule::in(['gas', 'galon'])],
            'harga'       => 'required|numeric|min:0|max:999999999',
            'stok'        => 'required|integer|min:0|max:999999',
        ], [
            'nama_barang.required' => 'Nama barang harus diisi',
            'nama_barang.max'      => 'Nama barang maksimal 50 karakter',
            'nama_barang.unique'   => 'Nama barang sudah terdaftar',
            'jenis.required'       => 'Jenis barang harus dipilih',
            'jenis.in'             => 'Jenis barang hanya boleh gas atau galon',
            'harga.required'       => 'Harga harus diisi',
            'harga.numeric'        => 'Harga harus berupa angka',
            'harga.min'            => 'Harga tidak boleh negatif',
            'harga.max'            => 'Harga terlalu besar',
            'stok.required'        => 'Stok awal harus diisi',
            'stok.integer'         => 'Stok harus berupa angka bulat',
            'stok.min'             => 'Stok tidak boleh negatif',
            'stok.max'             => 'Stok terlalu besar',
        ]);

        // Use transaction for data consistency
        DB::transaction(function () use ($validated) {
            Barang::create([
                'nama_barang' => $validated['nama_barang'],
                'jenis'       => $validated['jenis'],
                'harga'       => $validated['harga'],
                'stok'        => $validated['stok'],
            ]);
        });

        // Clear cache
        $this->clearBarangCache();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barang = Barang::with(['pesananDetails' => function($query) {
            $query->select('id_pesanan', 'id_barang', 'jumlah', 'created_at')
                  ->orderBy('created_at', 'desc')
                  ->limit(10);
        }])->findOrFail($id);

        return view('barang.show', compact('barang'));
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
     */
    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);

        $validated = $request->validate([
            'nama_barang' => [
                'required',
                'max:50',
                Rule::unique('barang', 'nama_barang')->ignore($barang->id_barang, 'id_barang')
            ],
            'jenis'       => ['required', Rule::in(['gas', 'galon'])],
            'harga'       => 'required|numeric|min:0|max:999999999',
            // Stok tidak diupdate di sini - gunakan method khusus untuk update stok
        ], [
            'nama_barang.required' => 'Nama barang harus diisi',
            'nama_barang.max'      => 'Nama barang maksimal 50 karakter',
            'nama_barang.unique'   => 'Nama barang sudah terdaftar',
            'jenis.required'       => 'Jenis barang harus dipilih',
            'jenis.in'             => 'Jenis barang hanya boleh gas atau galon',
            'harga.required'       => 'Harga harus diisi',
            'harga.numeric'        => 'Harga harus berupa angka',
            'harga.min'            => 'Harga tidak boleh negatif',
            'harga.max'            => 'Harga terlalu besar',
        ]);

        // Use transaction for data consistency
        DB::transaction(function () use ($barang, $validated) {
            $barang->update([
                'nama_barang' => $validated['nama_barang'],
                'jenis'       => $validated['jenis'],
                'harga'       => $validated['harga'],
                // Stok tidak diubah
            ]);
        });

        // Clear cache
        $this->clearBarangCache();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = Barang::withCount(['pesananDetails'])->findOrFail($id);

        // Safety checks
        if ($barang->stok > 0) {
            return redirect()->route('barang.index')
                ->with('error', 'Tidak dapat menghapus barang yang masih memiliki stok!');
        }

        if ($barang->pesanan_details_count > 0) {
            return redirect()->route('barang.index')
                ->with('error', 'Tidak dapat menghapus barang yang pernah digunakan dalam pesanan!');
        }

        // Use transaction
        DB::transaction(function () use ($barang) {
            $barang->delete();
        });

        // Clear cache
        $this->clearBarangCache();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Update stock for a specific item.
     */
    public function updateStock(Request $request, string $id)
    {
        $request->validate([
            'operation' => ['required', Rule::in(['tambah', 'kurangi'])],
            'jumlah'    => 'required|integer|min:1|max:9999',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $barang = Barang::findOrFail($id);

        DB::transaction(function () use ($request, $barang) {
            $oldStock = $barang->stok;
            
            if ($request->operation === 'tambah') {
                $newStock = $oldStock + $request->jumlah;
            } else {
                // Check if enough stock
                if ($oldStock < $request->jumlah) {
                    throw new \Exception('Stok tidak mencukupi untuk dikurangi');
                }
                $newStock = $oldStock - $request->jumlah;
            }

            $barang->update(['stok' => $newStock]);

            // Log stock change (if you have a stock_mutations table)
            // StockMutation::create([
            //     'id_barang' => $barang->id,
            //     'old_stock' => $oldStock,
            //     'new_stock' => $newStock,
            //     'operation' => $request->operation,
            //     'jumlah' => $request->jumlah,
            //     'keterangan' => $request->keterangan,
            //     'user_id' => auth()->id(),
            // ]);
        });

        $this->clearBarangCache();

        return redirect()->route('barang.index')
            ->with('success', 'Stok berhasil diperbarui!');
    }

    /**
     * Get barang data for API/select2.
     */
    public function getBarangData(Request $request)
    {
        $query = Barang::where('stok', '>', 0);
        
        if ($request->has('search')) {
            $query->where('nama_barang', 'LIKE', "%{$request->search}%");
        }
        
        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        
        $barangs = $query->select('id_barang', 'nama_barang', 'harga', 'stok', 'jenis')
            ->orderBy('nama_barang')
            ->limit(50)
            ->get();
            
        return response()->json($barangs);
    }

    /**
     * Export barang data.
     */
    public function export(Request $request)
    {
        $barangs = Barang::orderBy('id_barang', 'desc')->get();
        
        $data = [
            'title' => 'Data Barang',
            'date' => now()->format('d/m/Y'),
            'barangs' => $barangs,
            'total_nilai' => $barangs->sum(fn($item) => $item->harga * $item->stok),
        ];
        
        if ($request->has('format') && $request->format === 'csv') {
            return $this->exportToCSV($barangs);
        }
        
        return view('barang.export', $data);
    }

    /**
     * Clear barang-related cache.
     */
    protected function clearBarangCache(): void
    {
        Cache::flush();
        // Or specific cache clearing:
        // Cache::forget('barang_index_*'); // if using tagged cache
    }

    /**
     * Export to CSV.
     */
    protected function exportToCSV($barangs)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="barang_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($barangs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nama Barang', 'Jenis', 'Harga', 'Stok', 'Total Nilai']);
            
            foreach ($barangs as $barang) {
                fputcsv($file, [
                    $barang->id_barang,
                    $barang->nama_barang,
                    ucfirst($barang->jenis),
                    number_format($barang->harga, 0, ',', '.'),
                    $barang->stok,
                    number_format($barang->harga * $barang->stok, 0, ',', '.'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}