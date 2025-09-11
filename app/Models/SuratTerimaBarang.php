<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratTerimaBarang extends Model
{
    protected $table = 'suratterimabarang';
    protected $primaryKey = 'noterima';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'noterima', 'nokirim', 'kdtokoterima', 'tanggalterima', 'picterima'
    ];

    public function detail()
    {
        return $this->hasMany(TerimaBarang::class, 'noterima', 'noterima');
    }

    public function suratKirim()
    {
        return $this->belongsTo(SuratKirimBarang::class, 'nokirim', 'nokirim');
    }
}
