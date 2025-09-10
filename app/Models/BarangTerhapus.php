<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangTerhapus extends Model
{
    protected $table = 'barang_terhapus';
    protected $primaryKey = 'id';
    public $timestamps = true; 

    protected $fillable = [
        'fakturbaranghapus',
        'kdbarang',
        'barcode',
        'namabarang',
        'kdkategori',
        'kdbaki',
        'kdtoko',
        'berat',
        'kadar',
        'tanggalhapus'
    ];
}
