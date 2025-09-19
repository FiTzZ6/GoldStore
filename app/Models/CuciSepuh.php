<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuciSepuh extends Model
{
    protected $table = 'cucisepuh';
    protected $primaryKey = 'id_cuci';

    protected $fillable = [
        'nama_pelanggan',
        'no_hp',
        'alamat',
        'jenis_barang',
        'berat',
        'karat',
        'tanggal_cuci',
        'tanggal_selesai',
        'ongkos_cuci',
        'total_bayar',
        'metode_bayar',
        'catatan',
        'foto_barang',
        'status',
    ];
}
