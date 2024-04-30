<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kickedusers extends Model
{
    protected $table = 'kickedusers';

    protected $fillable = [
        'user_id',
        'room_id',
        'reason',
        'state'
    ];


    public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }

}
