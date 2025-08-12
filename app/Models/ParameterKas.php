<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParameterKas extends Model
{
    protected $table = 'parameterkas';  // nama tabel sesuai database
    protected $primaryKey = 'kdparameterkas'; // primary key yang bukan id default
    public $incrementing = false; // karena primary key char, bukan auto-increment integer
    protected $keyType = 'string'; // tipe primary key string

    protected $fillable = [
        'kdparameterkas',
        'parameterkas',
        'kdtoko'
    ];

    public $timestamps = false; // jika tabel tidak ada kolom created_at/updated_at
}