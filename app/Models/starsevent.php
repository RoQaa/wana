<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class starsevent extends Model
{
    protected $table = 'starsevents';

    protected $fillable = [
        'Starttime',
        'Endtime',
        'image',
        'status'
      
    ];
    public function Gifts(){
        return  $this->hasmany(gift::class,'event_id','id');
    }


}
