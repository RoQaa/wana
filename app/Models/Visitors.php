<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Visitors extends Model
{
    protected $table = 'visitors';
    protected $fillable =[
        'user_id',
        'visitor_id',
    ];
    public function user(){
        return $this->hasone(UserApp::class,"id","visitor_id");
    }
}
