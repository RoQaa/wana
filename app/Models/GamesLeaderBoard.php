<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamesLeaderBoard extends Model
{
    protected $table = 'games_leader_boards';
    protected $fillable = [
        'user_id',
        'status',
        'coins',
        'resultcoins',
        'game',
    ];
}
