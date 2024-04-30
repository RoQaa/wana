<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftsTarking extends Model
{
    protected $table = 'gifts_tarkings';

    protected $fillable = [
        'sender_id',
        'reciver_id',
        'gift_id',
        'room_id',
        'karisma',
        'lucky'
    ];
        public function user(){
        return $this->hasone(UserApp::class,"id","sender_id"); 
       }
               public function reciuveuser(){
        return $this->hasone(UserApp::class,"id","reciver_id"); 
       }
}
