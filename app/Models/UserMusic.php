<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMusic extends Model
{
    protected $table = 'user_music';
 
    protected $fillable = [
        'user_id',
        'name',
        'url'
        
    ];
}
