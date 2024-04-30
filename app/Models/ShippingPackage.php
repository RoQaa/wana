<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingPackage extends Model
{
    protected $table = 'payments_packages';

    protected $fillable = [
        'cost',
        'coins',
        'status'
    ];
}
