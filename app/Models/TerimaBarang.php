<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TerimaBarang extends Model
{
    protected $table = 'terimabarang';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'noterima', 'barcode', 'namabarang', 'kdjenis', 'kdbaki',
        'berat', 'beratterima', 'kdtokoterima'
    ];

    public function suratTerima()
    {
        return $this->belongsTo(SuratTerimaBarang::class, 'noterima', 'noterima');
    }
}
