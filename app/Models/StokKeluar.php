<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    protected $table = 'stok_keluar';
    protected $primaryKey = 'id_keluar';
    public $timestamps = true;

    protected $fillable = [
        'id_barang',
        'jumlah',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
