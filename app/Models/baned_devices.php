<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class baned_devices extends Model
{
    protected $table = 'baned_devices';

    protected $fillable = [
        'user_id',
        'reason',
        'deviceid',
        'DeviceIp','kind'
    ];
}
