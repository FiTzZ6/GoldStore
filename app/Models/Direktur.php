<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direktur extends Model
{
    protected $table = 'direksi';
    protected $primaryKey = 'id_direksi';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'jabatan',
        'qrcode',
    ];
}
