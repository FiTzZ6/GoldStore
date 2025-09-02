<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatalJual extends Model
{
    protected $table = 'trans_batal_jual';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'namastaff',
        'kdstaff',
        'barcode',
        'fakturjual',
        'fakturbataljual',
        'namabarang',
        'berat',
        'kadar',
        'ongkos',
        'quantity',
        'harga',
        'kondisi',
        'keterangan'
    ];
}
