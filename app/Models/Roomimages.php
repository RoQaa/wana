<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roomimages extends Model
{
    protected $table = 'roomimages';
    protected $fillable =[
        'user_id',
        'image',
    ];
}
