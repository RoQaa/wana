<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MusicEntry extends Model
{
    protected $table = 'music_entries';
    protected $fillable =[
        'country',
        'music',
 
    ];
}
