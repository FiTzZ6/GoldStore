<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $table = 'usertype';
    protected $primaryKey = 'usertypeid';
    public $timestamps = false;

    protected $fillable = ['usertype'];
}
