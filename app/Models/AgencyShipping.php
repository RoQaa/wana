<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyShipping extends Model
{
    protected $table = 'agency_shippings';

    protected $fillable = [
        'agancy_id',
        'amount',
        'cost'
    ];
}
