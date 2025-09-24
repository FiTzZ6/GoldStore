<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormPenyimpanan extends Model
{
    protected $table = 'form_penyimpanan';

    protected $fillable = [
        'kdbarang',
        'kdpelanggan',
        'kondisi',
        'suhu',
        'kelembaban',
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
