<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPP extends Model
{
    protected $table = 'detail_pp';
    protected $primaryKey = 'id';
    public $timestamps = true; // karena ada created_at & updated_at

    protected $fillable = [
        'nopp',
        'kdtoko',
        'namabarang',
        'spesifikasi',
        'jumlah',
        'satuan',
        'supplier1',
        'harga1',
        'supplier2',
        'harga2',
        'supplier3',
        'harga3',
        'tanggal_permintaan',
        'tanggal_dibutuhkan',
    ];

    // 🔥 Relasi ke Toko
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'kdtoko', 'kdtoko');
    }
}

