<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
 
class games extends Model
{
    protected $table = 'games';
    protected $fillable = [
        'name',
        'status',
        'oriantation',
        'path',
        'image',
        'usersnumber'
    ];
}
