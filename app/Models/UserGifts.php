<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGifts extends Model
{
    protected $table = 'user_gifts';
 
    protected $fillable = [
         'user_id',
         'svga',
         'title',
         'message',  
         'image',
         'kind',
         'created_at'
       
    ];
}
