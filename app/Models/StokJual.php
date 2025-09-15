<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokJual extends Model
{
    protected $table = 'stokjual';
    protected $primaryKey = 'nofaktur';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nofaktur',
        'barcode',
        'namabarang',
        'hargabeli',
        'hargajual',
        'ongkos',
        'berat',
        'kadar',
        'stok',
        'stokterjual',
        'stoktotal'
    ];


    // Generate No Faktur custom
    public static function generateNoFaktur()
    {
        $prefix = 'FJ-SAMBAS1-21036-';

        // Ambil nofaktur terakhir dengan prefix ini
        $last = self::where('nofaktur', 'like', $prefix . '%')
            ->orderBy('nofaktur', 'desc')
            ->first();

        if ($last) {
            // Ambil angka urut terakhir
            $lastNumber = (int) substr($last->nofaktur, strlen($prefix));
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001'; // default jika belum ada
        }

        return $prefix . $nextNumber;
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barcode', 'barcode');
    }


}
