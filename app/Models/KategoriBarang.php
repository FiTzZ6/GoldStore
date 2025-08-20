<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class KategoriBarang extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'kdkategori';
    public $incrementing = false;
   public $timestamps = false;

    protected $fillable = ['kdkategori','namakategori','hargapergr','jumlahkadar'];

    public function jenisBarang()
    {
        return $this->hasMany(JenisBarang::class, 'kdkategori', 'kdkategori');
    }
}