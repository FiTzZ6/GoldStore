<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nofakturpesan',
        'namabarang',
        'barcode',
        'tglpesan',
        'tglambil',
        'staff',
        'namapemesan',
        'alamatpemesan',
        'notelp',
        'quantity',
        'harga',
        'ongkos',
        'total',
    ];
}
