<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    protected $table = 'payments';
    protected $fillable =[
        'txn_id',
        'user_id',
        'package_id',
        'method',
        'cost',
    ];
}
