<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelFrames extends Model
{
    protected $table = 'level_frames';
 
    protected $fillable = [
        'image',
        'name',
        'svga',
        'level_start',
        'level_end'
       
    ];
}
