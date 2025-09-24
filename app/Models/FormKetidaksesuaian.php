<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormKetidaksesuaian extends Model
{
    protected $table = 'form_ketidaksesuaian';

    protected $fillable = [
        'kdbarang',
        'kdpelanggan',
        'deskripsi'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kdbarang', 'kdbarang');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'kdpelanggan', 'kdpelanggan');
    }
}
