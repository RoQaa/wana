<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowRoom extends Model
{
    protected $table = 'follow_rooms';
    protected $fillable = [
        'user_id',
        'room_id',
    ];
       public function room(){
        return $this->hasone(Rooms::class,"id","room_id");
    }
     
}
