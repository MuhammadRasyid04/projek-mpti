<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $primaryKey = 'id_users';

    protected $fillable = [
        'username',
        'password',
        'email',
        'nama',
        'pekerjaan',
        'no_hp',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'alamat',
        'foto',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
}
