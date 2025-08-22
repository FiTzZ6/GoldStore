<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
    protected $primaryKey = 'kdstaff';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdstaff',
        'nama',
        'posisi',
        'kdtoko',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kdtoko', 'kdtoko');
    }
}
