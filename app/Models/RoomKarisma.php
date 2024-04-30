<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomKarisma extends Model
{
    protected $table = 'room_karismas';
 
    protected $fillable = [
        'user_id',
        'room_id',
        'karisma'
    ];
    
      public function user(){
        return $this->hasone(UserApp::class,"id","user_id") ;
    }
}
