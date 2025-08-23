<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'kdbarang';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdbarang', 'barcode', 'namabarang', 'kdkategori', 'kdjenis', 'kdbaki', 'kdtoko',
        'berat', 'kadar', 'hargabeli', 'photo', 'kdstatus', 'kdsupplier',
        'atribut', 'hargaatribut', 'beratasli', 'beratbandrol', 'kdintern',
        'inputby', 'inputdate', 'editby', 'editdate',
        'deletedby', 'deleteddate', 'statusdelete', 'barcode_pict', 'photo_type', 'camera_type'
    ];

     public function getBarcodeUrlAttribute() {
        return $this->barcode ? asset('storage/barcodeBarang/'.$this->barcode.'.png') : null;
    }


    public function getPhotoUrlAttribute() {
        return $this->photo ? asset('storage/barangFoto/'.$this->photo) : null;
    }

    // Relasi ke tabel lain
    public function KategoriBarang() {
        return $this->belongsTo(KategoriBarang::class, 'kdkategori', 'kdkategori');
    }

    public function JenisBarang() {
        return $this->belongsTo(JenisBarang::class, 'kdjenis', 'kdjenis');
    }


    public function baki() {
        return $this->belongsTo(Baki::class, 'kdbaki', 'kdbaki');
    }

    public function Cabang() {
        return $this->belongsTo(Cabang::class, 'kdtoko', 'kdtoko');
    }

    public function Status() {
        return $this->belongsTo(Status::class, 'kdstatus', 'kdstatus');
    }
    public function Supplier() {
        return $this->belongsTo(Supplier::class, 'kdsupplier', 'kdsupplier');
    }
    public function CetakBarcode() {
        return $this->belongsTo(CetakBarcode::class, 'kdintern', 'tipebarang');
    }

}
