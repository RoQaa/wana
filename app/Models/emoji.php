<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class emoji extends Model
{
    protected $table = 'emoji';
    protected $fillable = [
        'emoji_svga',
        'emoji_Name',
        'status',
        'image','category_id'
    ];
}
