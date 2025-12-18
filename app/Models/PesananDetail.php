<?php
// app/Models/PesananDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    protected $table = 'pesanan_detail';
    protected $primaryKey = 'id_detail';
    public $incrementing = true;
    
    protected $fillable = [
        'id_pesanan',
        'id_barang',
        'jumlah',
        'harga_saat_itu'
    ];
    
    // Relasi dengan pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
    
    // Relasi dengan barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }
}