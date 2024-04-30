<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $table = 'messages';
 
    protected $fillable = [
        'message',
        'deletestate',
        'user_id',
        'sender_id',
        'Inboxroom_id',
        'status'
    ];
}
