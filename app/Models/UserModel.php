<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'user'; // Sesuai nama tabel di database
    protected $primaryKey = 'kduser'; // Ganti sesuai primary key tabel

    public $timestamps = false; // Jika tabel tidak pakai created_at dan updated_at

    protected $fillable = [
        'name', 'username', 'password', 'usertype', 'kdtoko', 'createddate'
    ];

     public function userTypeData()
    {
        return $this->belongsTo(UserType::class, 'usertype', 'usertypeid');
    }
}

