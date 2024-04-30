<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisors extends Model
{
    protected $table = 'supervisors';

    protected $fillable = [
        'user_id',
        'room_id',
      
    ];
    public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }
   
}
