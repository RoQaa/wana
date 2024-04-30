<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admins extends Model
{
    protected $table = 'admins';

    protected $fillable = [
        'name',
        'password',
        'role',
        'ban',
        'modia',
        'chat',
        'last_login',
        'charge',
        'users',
        'rooms',
        'agency',
        'banonly','nomonyagency',
        'agencyandusercharge',
    ];
}
