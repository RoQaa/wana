<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exchangecoins extends Model
{
    protected $table = 'exchangecoins';

    protected $fillable = [
        'user_id',
        'beans',
        'coins',
    ];
}
