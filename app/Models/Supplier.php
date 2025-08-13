<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';          // ganti kalau nama tabelnya berbeda
    protected $primaryKey = 'kdsupplier';   // primary key kamu
    public $incrementing = false;           // karena varchar, bukan auto-increment
    protected $keyType = 'string';
    public $timestamps = false;             // tabel kamu tidak pakai created_at/updated_at

    protected $fillable = [
        'kdsupplier',
        'namasupplier',
        'alamat',
        'hp',
        'email',
        'ket',
    ];
}
