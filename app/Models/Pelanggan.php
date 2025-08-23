<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan'; // sesuaikan dengan nama tabel
    public $timestamps = false; // <---- tambahkan ini
    protected $fillable = [
        'kdpelanggan', 
        'namapelanggan', 
        'alamatpelanggan', 
        'notelp', 
        'poin',
        'tanggaldaftar'
    ];
}
