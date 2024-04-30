<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guessgame extends Model
{
    protected $table = 'guessgames';

    protected $fillable = [
        'Sender_id',
        'Sender_gueess',
        'Accept_id',
        'Accept_gueess',
        'Coins',
        'status'
    ];
       public function sender(){
        return $this->hasone(UserApp::class,"id","Sender_id");
    }
    public function player(){
        return $this->hasone(UserApp::class,"id","Accept_id");
    }
}
