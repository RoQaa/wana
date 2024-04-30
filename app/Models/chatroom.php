<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class chatroom extends Model
{
    protected $table = 'chatrooms';

    protected $fillable = [
        'room_id',
        'user_id',
        'content',
    ];
     
    public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }
}
