<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = true;

    protected $fillable = [
        'nama_barang',
        'jenis',
        'harga',
        'stok',
    ];

    public function pesananDetails()
    {
        return $this->hasMany(PesananDetail::class, 'id_barang', 'id_barang');
    }
}
