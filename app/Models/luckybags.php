<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class luckybags extends Model
{
    
    protected $table = 'luckybags';
    protected $fillable =[
        'user_id',
        'room_id',
        'coins',
    ];
}
