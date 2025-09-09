<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransService extends Model
{
    use HasFactory;

    protected $table = 'trans_service';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'fakturservice',
        'staff',
        'tanggalservice',
        'tanggalambil',
        'tipepelanggan',
        'nopelanggan',
        'namapelanggan',
        'notelp',
        'alamat',
        'namabarang',
        'jenis',
        'berat',
        'qty',
        'harga',   
        'ongkos',
        'deskripsi'
    ];

    protected $casts = [
        'namabarang' => 'array',
        'jenis' => 'array',
        'berat' => 'array',
        'qty' => 'array',
        'harga' => 'array',
        'ongkos' => 'array',
        'deskripsi' => 'array',
    ];
}
