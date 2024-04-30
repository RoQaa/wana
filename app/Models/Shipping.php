<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'payments';
    protected $fillable =[
        'user_id',
        'coins',
        'method',
        'cost',
    ];
}
