<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'membership';
    public $timestamps = false; // karena tabel tidak punya created_at/updated_at

    protected $fillable = [
        'poin', 'gr'
    ];
}
