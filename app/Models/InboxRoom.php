<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboxRoom extends Model
{
    protected $table = 'inbox_rooms';

    protected $fillable = [
        'user_id',
        'last_message',
        'number_unread',
        'sender_id',
        'status'
    ];
  
    public function message(){

        return $this->hasmany(Messages::class,'Inboxroom_id','id');
         }

         public function user(){
            return $this->hasone(UserApp::class,"id","user_id");
        }  
        public function sender(){
            return $this->hasone(UserApp::class,"id","sender_id");
        }

}
