<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyLeaverequest extends Model
{
    protected $table = 'agency_leaved';

    protected $fillable = [
        'user_id',
        'agancy_id',
        'Karisma'
    ];
}
