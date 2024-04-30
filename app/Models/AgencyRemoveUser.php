<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyRemoveUser extends Model
{
    protected $table = 'agency_remove_users';

    protected $fillable = [
        'user_id',
        'agancy_id',
        'Reason',
  
    ];
}
