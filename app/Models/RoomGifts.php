<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomGifts extends Model
{
    protected $table = 'room_gifts';
 
    protected $fillable = [
        'user_id',
        'gift_id',
        'quantity',
        'room_id',
        'state'
    ];

    public function gift(){
      
        return $this->hasone(gift::class,"id","gift_id");
 


}
}
