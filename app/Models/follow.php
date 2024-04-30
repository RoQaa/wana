<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class follow extends Model
{
    protected $table = 'follows';
    protected $fillable = [
        'user_id',
        'sender_id',
        'status'
    ];

    public function user(){
        return $this->hasone(UserApp::class,"id","sender_id");
    }
    public function otheruser(){
        return $this->hasone(UserApp::class,"id","user_id");
    }
}
