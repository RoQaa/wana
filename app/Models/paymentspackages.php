<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class paymentspackages extends Model
{
    protected $table = 'payments_packages';
 
    protected $fillable = [
        'packageid',
        'cost',
        'coins',
        'status'
         ];
}
