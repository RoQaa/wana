<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelGifts extends Model
{
    protected $table = 'level_gifts';
 
    protected $fillable = [
         'user_id',
         'svga',
         'tittle',
         'days',
         'message',  
         'image',
         'kind',
       
    ];
}
