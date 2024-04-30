<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Freinds extends Model
{
    protected $table = 'freinds';
    protected $fillable = [
        'user_id',
        'sener_id',
        'state'
    ];
    
    public function user(){
        return $this->hasone(UserApp::class,"id","sener_id");
    }

    public function users(){
        return $this->hasone(UserApp::class,"id","user_id");
    }


 
}
