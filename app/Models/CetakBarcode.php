<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CetakBarcode extends Model
{
    protected $table = 'cetakbarcode';
    protected $primaryKey = 'barcode';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'tipebarang'
    ];

}
