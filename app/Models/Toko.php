<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    // Nama tabel (default Laravel akan cari "tokos", jadi kita pakai override)
    protected $table = 'toko';

    // Primary key
    protected $primaryKey = 'kdtoko';

    // Kalau primary key bukan integer auto increment
    public $incrementing = false; 
    protected $keyType = 'string';

    // Kalau tabel tidak ada created_at & updated_at
    public $timestamps = false;

    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'kdtoko',
        'namatoko',
        'alamattoko',
        'area',
        'logo',
    ];
}
