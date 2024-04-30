<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyRecordes extends Model
{
    protected $table = 'agency_recordes';

    protected $fillable = [
        'user_id',
        'agancy_id',
        'status',
        'karisma',
    ];
}
