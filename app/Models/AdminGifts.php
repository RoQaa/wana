<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminGifts extends Model
{
    protected $table = 'admin_gifts';

    protected $fillable = [
        'image',
        'svga',
        'kind'
    ];
}
