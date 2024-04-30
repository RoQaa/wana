<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    protected $table = 'leaderboards';
 
    protected $fillable = [
         'user_id',
         'coins',
         'status',
         'room_id',
         'family_id',
         'event_id',
         'gift_id','agency_id'
    ];
    public function user(){
        return $this->hasone(UserApp::class,"id","user_id"); 
       }
       
      public function Agency(){
        return $this->hasone(Agency::class,"id","agency_id"); 
       }
       
public function room(){
        return $this->hasone(Rooms::class,"id","room_id"); 
       }

       public function family(){
        return $this->hasone(Families::class,"id","family_id"); 
       }

}
