<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTimeTarget extends Model
{
    protected $table = 'user_time_targets';
 
    protected $fillable = [
        'user_id',
        'min',
    ];
}
