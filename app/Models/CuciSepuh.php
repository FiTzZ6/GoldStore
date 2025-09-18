<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuciSepuh extends Model
{
    protected $table = 'cucisepuh';
    protected $primaryKey = 'id_cuci';
    protected $fillable = [
        'nama_pelanggan',
        'jenis_barang',
        'berat',
        'karat',
        'tanggal_cuci',
        'status'
    ];
}
