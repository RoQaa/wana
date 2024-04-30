<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelPrize extends Model
{ protected $table = 'level_prizes';
    protected $fillable =[
        'user_id',
        'endlevel',
        
    ];
}
