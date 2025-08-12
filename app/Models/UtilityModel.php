<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilityModel extends Model
{
    protected $table = 'company_profile';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'logo'
    ];
}