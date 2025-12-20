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
    protected const ITEMS_PER_PAGE = 20; // Increased for better UX

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Generate cache key based on request parameters
        $cacheKey = 'barang_index_' . md5(json_encode($request->all()));
        
        // Use cache only for filtered searches, not for initial load
        if ($request->hasAny(['search', 'jenis', 'sort_by', 'sort_order'])) {
            $data = Cache::remember($cacheKey, now()->addMinutes(self::CACHE_DURATION), function () use ($request) {
                return $this->getBarangData($request);
            });
        } else {
            // For initial load, don't cache to get fresh data
            $data = $this->getBarangData($request);
        }
        
        return view('barang.index', $data);
    }

    /**
     * Get optimized barang data with minimal queries
     */
    private function getBarangData(Request $request)
    {
        // Start with optimized query
        $query = Barang::select(['id_barang', 'nama_barang', 'jenis', 'harga', 'stok', 'created_at']);
        
        // Search functionality - use exact match first for better performance
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Use exact match for ID
                if (is_numeric($search)) {
                    $q->where('id_barang', $search);
                }
                // For text search, use LIKE only if necessary
                $q->orWhere('nama_barang', 'LIKE', "{$search}%") // Starts with for better performance
                  ->orWhere('jenis', $search); // Exact match for jenis
            });
        }
        
        // Filter by jenis - exact match
        if ($request->has('jenis') && in_array($request->jenis, ['gas', 'galon'])) {
            $query->where('jenis', $request->jenis);
        }
        
        // Sort functionality - use index-friendly sorting
        $sortBy = $request->get('sort_by', 'id_barang');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $validSortColumns = ['id_barang', 'nama_barang', 'jenis', 'harga', 'stok', 'created_at'];
        $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'id_barang';
        $sortOrder = $sortOrder === 'asc' ? 'asc' : 'desc';
        
        $barangs = $query->orderBy($sortBy, $sortOrder)
            ->paginate(self::ITEMS_PER_PAGE)
            ->withQueryString();
        
        // Optimized statistics - calculate in single query
        $stats = Cache::remember('barang_stats', now()->addMinutes(5), function () {
            $totalBarang = Barang::count();
            $stockTotals = Barang::selectRaw("
                SUM(CASE WHEN jenis = 'gas' THEN stok ELSE 0 END) as total_gas,
                SUM(CASE WHEN jenis = 'galon' THEN stok ELSE 0 END) as total_galon,
                SUM(harga * stok) as total_nilai
            ")->first();
            
            return [
                'total_barang' => $totalBarang,
                'total_gas' => $stockTotals->total_gas ?? 0,
                'total_galon' => $stockTotals->total_galon ?? 0,
                'total_nilai' => $stockTotals->total_nilai ?? 0,
            ];
        });
        
        return [
            'barangs' => $barangs,
            'stats' => $stats,
            'filters' => $request->only(['search', 'jenis', 'sort_by', 'sort_order'])
        ];
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

        try {
            DB::beginTransaction();
            
            Barang::create([
                'nama_barang' => $validated['nama_barang'],
                'jenis'       => $validated['jenis'],
                'harga'       => $validated['harga'],
                'stok'        => $validated['stok'],
            ]);
            
            DB::commit();
            
            // Clear relevant caches
            $this->clearBarangCache();
            Cache::forget('barang_stats');

            return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama_barang' => [
                'required',
                'max:50',
                Rule::unique('barang', 'nama_barang')->ignore($barang->id_barang, 'id_barang')
            ],
            'jenis'       => ['required', Rule::in(['gas', 'galon'])],
            'harga'       => 'required|numeric|min:0|max:999999999',
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

        try {
            DB::beginTransaction();
            
            $barang->update([
                'nama_barang' => $validated['nama_barang'],
                'jenis'       => $validated['jenis'],
                'harga'       => $validated['harga'],
            ]);
            
            DB::commit();
            
            // Clear relevant caches
            $this->clearBarangCache();
            Cache::forget('barang_stats');

            return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil diperbarui!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui barang: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        // Check if barang has been used in orders
        $usedInOrder = DB::table('pesanan_detail')
            ->where('id_barang', $barang->id_barang)
            ->exists();

        if ($usedInOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus barang yang pernah digunakan dalam pesanan!'
            ]);
        }

        // Check if barang still has stock
        if ($barang->stok > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus barang yang masih memiliki stok!'
            ]);
        }

        try {
            DB::beginTransaction();
            
            $barang->delete();
            
            DB::commit();
            
            // Clear relevant caches
            $this->clearBarangCache();
            Cache::forget('barang_stats');

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get barang data for API/select2.
     */
    public function getBarangDataApi(Request $request)
    {
        $query = Barang::select('id_barang', 'nama_barang', 'harga', 'stok', 'jenis')
            ->where('stok', '>', 0);
        
        if ($request->has('search')) {
            $query->where('nama_barang', 'LIKE', "{$request->search}%"); // Starts with for performance
        }
        
        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        
        $barangs = $query->orderBy('nama_barang')
            ->limit(20) // Reduced limit for better performance
            ->get();
            
        return response()->json($barangs);
    }

    /**
     * Clear barang-related cache.
     */
    protected function clearBarangCache(): void
    {
        // Clear only barang index cache
        Cache::forget('barang_stats');
    }
}