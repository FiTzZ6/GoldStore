<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Baki extends Model
{
    protected $table = 'baki';
    protected $primaryKey = 'kdbaki';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'kdbaki',
        'namabaki',
        'kdkategori',
    ];
}
