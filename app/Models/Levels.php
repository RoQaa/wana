<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Levels extends Model
{
    protected $table = 'levels';
    protected $fillable =[
        'frame_id',
        'entry_id',
        'startlevel',
        'endlevel',
        'icon'
        
    ];
    
     public function frame(){
        return $this->hasone(LevelGifts::class,"id","frame_id");
    }
      public function entry(){
        return $this->hasone(LevelGifts::class,"id","entry_id");
    }
}
