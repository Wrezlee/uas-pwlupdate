<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_pesanan',
        'pesan',
        'status',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }
}
