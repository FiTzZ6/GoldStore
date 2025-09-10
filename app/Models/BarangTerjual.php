<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTerjual extends Model
{
    use HasFactory;

    protected $table = 'barang_terjual';
    protected $primaryKey = 'id';

     public $timestamps = true; 

    protected $fillable = [
        'fakturbarangterjual',
        'namabarang',
        'barcode',
        'kdbaki',
        'berat',
        'kadar',
        'harga',
        'namastaff',
        'tanggalterjual',
    ];
}
