<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Joinroom extends Model
{
    protected $table = 'joinrooms';

    protected $fillable = [
        'user_id',
        'room_id',
        'index','state'
    ];

    public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }

}
