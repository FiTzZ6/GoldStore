<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransBatalBeli extends Model
{
    protected $table = 'trans_batal_beli';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'namastaff',
        'nofakturbeli',
        'nofakturbatalbeli',
        'namapenjual',
        'kondisibeli',
        'kondisibatalbeli',
        'namabarang',
        'berat',
        'hargabeli',
        'hargabatalbeli',
        'keterangan',
    ];
}
