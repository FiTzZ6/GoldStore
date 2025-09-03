<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransBeli extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'trans_beli';

    // Primary key
    protected $primaryKey = 'id';

    // Field yang boleh diisi massal
    protected $fillable = [
        'staff',
        'barcode',
        'namapenjual',
        'alamat',
        'notelp',
        'nofaktur',
        'deskripsi',
        'jenis',
        'kondisi',
        'beratlama',
        'beratbaru',
        'hargalama',
        'hargabaru',
        'hargarata',
        'potongan',
        'total',
    ];

    // Kalau tabelmu tidak ada timestamps (created_at & updated_at)
    public $timestamps = true;
}
