<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'toko';
    protected $primaryKey = 'kdtoko';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kdtoko',
        'namatoko',
        'alamattoko',
        'area',
        'logo'
    ];

    public function parameterkas()
    {
        return $this->hasMany(ParameterKas::class, 'kdtoko', 'kdtoko');
    }
}
