<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $table = 'kas';
    protected $primaryKey = 'id'; // pastikan di DB kolomnya id INT AUTO_INCREMENT
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'jumlahkas',
        'idparameterkas',
        'type',
        'tanggal',
        'keterangan'
    ];

    public $timestamps = false;
    protected $casts = [
        'tanggal' => 'datetime',
    ];
    public function parameterKas()
    {
        return $this->belongsTo(ParameterKas::class, 'idparameterkas', 'kdparameterkas');
    }

}
