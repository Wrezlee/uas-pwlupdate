<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $timestamps = false;

    protected $fillable = [
        'nama_pembeli',
        'no_hp',
        'alamat',
        'tanggal',
        'total_harga',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function details()
    {
        return $this->hasMany(PesananDetail::class, 'id_pesanan');
    }
}
