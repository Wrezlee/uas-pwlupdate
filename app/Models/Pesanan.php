<?php
// app/Models/Pesanan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $incrementing = true;
    
    protected $fillable = [
        'nama_pembeli',
        'no_hp', 
        'alamat',
        'tanggal',
        'total_harga',
        'status'
    ];
    
    protected $casts = [
        'tanggal' => 'datetime',
        'total_harga' => 'integer',
    ];
    
    // Relasi dengan pesanan_detail (one-to-many)
    public function details()
    {
        return $this->hasMany(PesananDetail::class, 'id_pesanan', 'id_pesanan');
    }
    
    // Relasi dengan barang melalui pesanan_detail
    public function barang()
    {
        return $this->hasManyThrough(
            Barang::class,
            PesananDetail::class,
            'id_pesanan', // Foreign key pada pesanan_detail
            'id', // Foreign key pada barang
            'id_pesanan', // Local key pada pesanan
            'id_barang' // Local key pada pesanan_detail
        );
    }
    
    // Scope untuk hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', now());
    }
}