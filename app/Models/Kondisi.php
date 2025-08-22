<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kondisi extends Model
{
    protected $table = 'kondisi';
    protected $primaryKey = 'kdkondisi';
    public $timestamps = false;

    protected $fillable = ['kondisibarang'];
}
