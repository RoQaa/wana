<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaypalPackage extends Model
{
    protected $table = 'paypal_packages';
 
    protected $fillable = [
        'coins',
        'price',
         
    ];
}
