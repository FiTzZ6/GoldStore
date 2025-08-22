<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ongkos extends Model
{
    protected $table = 'ongkos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'kdtoko',
        'ongkos',
    ];

    // Relasi ke tabel toko
    public function toko()
    {
        return $this->belongsTo(Cabang::class, 'kdtoko', 'kdtoko');
    }
}
