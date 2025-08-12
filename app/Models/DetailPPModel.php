<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPPModel extends Model
{
    protected $table = 'detail_pp';
    protected $fillable = [
        'nopp', 'namabarang', 'spesifikasi', 'jumlah', 'satuan',
        'supplier1', 'harga1', 'supplier2', 'harga2', 'supplier3', 'harga3'
    ];
}
