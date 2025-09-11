<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransPenjualan extends Model
{
    protected $table = 'trans_penjualan';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'nofaktur',
        'namastaff',
        'typepesanan',   // 🔹 ditambahkan
        'namapelanggan',
        'nohp',
        'alamat',
        'barcode',
        'namabarang',
        'harga',
        'ongkos',
        'total',
        'quantity',        // 🔹 sebelumnya belum ada di model
        'pembayaran'
    ];
}
