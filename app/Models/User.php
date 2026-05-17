<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'nomor_hp',
        'role',
        'id_admin',
    ];

    protected $hidden = ['password', 'remember_token'];
}