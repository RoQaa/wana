<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuckyGiftsTrack extends Model
{
    protected $table = 'lucky_gifts_tracks';
    protected $fillable =[
        'user_id',
        'coins',
        'percentage',
        'wincoins',
        'quantity',
        'gift_id',
        'usercoins','beforeusercoins'
    ];
}
