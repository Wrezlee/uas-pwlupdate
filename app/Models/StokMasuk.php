<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    protected $table = 'stok_masuk';
    protected $primaryKey = 'id_masuk';
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
