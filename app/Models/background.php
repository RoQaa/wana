<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class background extends Model
{
    protected $table = 'backgrounds';
    protected $fillable = [
        'image',
        'status'
     ];
}
