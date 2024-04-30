<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StartBanner extends Model
{
    protected $table = 'start_banners';

    protected $fillable = [
        'room_id',
        'photo',
        'status'
      
    ];
}
