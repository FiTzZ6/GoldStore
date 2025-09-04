<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PindahBaki extends Model
{
    protected $table = 'pindah_baki';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'no_pindah',
        'barcode',
        'kdbaki_asal',
        'kdbaki_tujuan',
    ];
}
