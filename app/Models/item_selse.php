<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class item_selse extends Model
{
    protected $table = 'item_selses';
    protected $fillable = [
        'item_id',
        'user_id',
    ];

}
