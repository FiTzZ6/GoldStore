<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanPembelian extends Model
{
    protected $table = 'permintaan_pembelian';
    protected $fillable = [
        'nopp', 'kdtoko', 'tgl_permintaan', 'tgl_butuh'
    ];

    public function details()
    {
        return $this->hasMany(DetailPP::class, 'nopp', 'nopp');
    }
}
