<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKirimBarang extends Model
{
    protected $table = 'suratkirimbarang';
    protected $primaryKey = 'nokirim';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nokirim',
        'kdtokokirim',
        'kdtokoterima',
        'tanggal',
        'pic',
        'status'
    ];

    public function terimaBarang()
    {
        return $this->hasOne(SuratTerimaBarang::class, 'nokirim', 'nokirim');
    }

    public function detail()
    {
        return $this->hasMany(DetailKirimBarang::class, 'nokirim', 'nokirim');
    }

}
