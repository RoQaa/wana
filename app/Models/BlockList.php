<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockList extends Model
{
    protected $table = 'block_lists';

    protected $fillable = [
        'user_id',
        'sender_id',
    ];
}
