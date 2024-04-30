<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'name',
        'image',
        'frame',
        'password',
        'user_number',
        'admin_id', 
        'state',
        'stopeffect',
        'users',
        'rejected_ids',
        'assistant_id',
        'assistant2_id',
        'Locked',
        'Category',
        'city',
        'animateimage',
        'importance',
        'Token','agoratoken','RoomAds',
        'RoomID',
        'FamilyId'
        
    ];
      protected $hidden = ['fixed'];

    public function joinRoom(){
        return  $this->hasmany(Joinroom::class,'room_id','id')->where('state',0);
    }
    public function supervisors(){
        return  $this->hasmany(Supervisors::class,'room_id','id');
    }
    public function  chairs(){

        return  $this->hasmany(Chairs::class,'room_id','id');

    }
    public function admin(){
        return $this->hasone(UserApp::class,"id","admin_id");
    }


}
