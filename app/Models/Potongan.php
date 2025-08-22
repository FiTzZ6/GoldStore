<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Potongan extends Model
{
    protected $table = 'potongan';
    protected $primaryKey = 'kdpotongan';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdpotongan',
        'kdkategori',
        'jumlahpotongan',
        'jenispotongan',
        'kdtoko',
    ];

    // relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kdkategori', 'kdkategori');
    }

    // relasi ke toko
    public function toko()
    {
        return $this->belongsTo(Cabang::class, 'kdtoko', 'kdtoko');
    }
}
