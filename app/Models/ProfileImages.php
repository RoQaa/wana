<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileImages extends Model
{
    protected $table = 'profile_images';
 
    protected $fillable = [
        'user_id',
        'image',
         
    ];
}
