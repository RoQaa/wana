<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VipCenter extends Model
{
    protected $table = 'vip_centers';

    protected $fillable = [
        'Gif',
        'vipicon',
        'Entry',
        'name',
        'Frame',
        'Level',
        'SpecialID',
        'ProfileEntry',
        'ColoredMessage',
        'Hidden',
        'status',
        'cost',
        'days',
        'background_image'
    ];
}
