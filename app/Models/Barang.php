<?php
// app/Models/Barang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'harga',
        'stok', // Pastikan namanya 'stok' bukan 'stock'
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
        'stok' => 0,
    ];

    // Relasi ke pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    // Scope untuk barang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk barang tersedia (ada stok)
    public function scopeAvailable($query)
    {
        return $query->where('stok', '>', 0);
    }

    // Cek apakah stok cukup
    public function stokCukup($jumlah)
    {
        return $this->stok >= $jumlah;
    }
}