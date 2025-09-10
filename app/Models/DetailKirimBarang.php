<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKirimBarang extends Model
{
    protected $table = 'detailkirimbarang';
    protected $fillable = [
        'nokirim', 'barcode', 'namabarang', 'kdjenis', 'kdbaki', 'berat', 'qty'
    ];

    public function suratKirim()
    {
        return $this->belongsTo(SuratKirimBarang::class, 'nokirim', 'nokirim');
    }
}
