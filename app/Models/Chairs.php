<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chairs extends Model
{
    protected $table = 'chairs';
    protected $fillable = [
        'chair_id',
        'user_id',
        'room_id',
        'Lock','Karisma','adminleaved','joindate'
    ];


    public function user(){
        return $this->hasone(UserApp::class,"id","user_id");    }
}
