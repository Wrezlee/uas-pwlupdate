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
            // Use transaction for data consistency
            DB::transaction(function () use ($barang) {
                $barang->delete();
            });

            // Clear cache
            $this->clearBarangCache();

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update stock for a specific item.
     */
    public function updateStock(Request $request, Barang $barang)
    {
        $request->validate([
            'operation' => ['required', Rule::in(['tambah', 'kurangi'])],
            'jumlah'    => 'required|integer|min:1|max:9999',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request, $barang) {
                $oldStock = $barang->stok;
                
                if ($request->operation === 'tambah') {
                    $newStock = $oldStock + $request->jumlah;
                    
                    // Catat stok masuk
                    DB::table('stok_masuk')->insert([
                        'id_barang' => $barang->id_barang,
                        'jumlah' => $request->jumlah,
                        'keterangan' => $request->keterangan ?: 'Penambahan stok manual',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    // Check if enough stock
                    if ($oldStock < $request->jumlah) {
                        throw new \Exception('Stok tidak mencukupi untuk dikurangi. Stok tersedia: ' . $oldStock);
                    }
                    $newStock = $oldStock - $request->jumlah;
                    
                    // Catat stok keluar
                    DB::table('stok_keluar')->insert([
                        'id_barang' => $barang->id_barang,
                        'jumlah' => $request->jumlah,
                        'keterangan' => $request->keterangan ?: 'Pengurangan stok manual',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $barang->update(['stok' => $newStock]);
            });

            $this->clearBarangCache();

            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil diperbarui! Stok baru: ' . $barang->fresh()->stok
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
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
     * Clear barang-related cache.
     */
    protected function clearBarangCache(): void
    {
        // Clear cache dengan pattern tertentu
        Cache::forget('barang_index_*');
    }
}