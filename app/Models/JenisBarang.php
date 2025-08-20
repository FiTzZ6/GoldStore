<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    protected $table = 'jenis';
    protected $primaryKey = 'kdjenis';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['kdjenis','namajenis','kdkategori'];

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kdkategori', 'kdkategori');
    }
}
