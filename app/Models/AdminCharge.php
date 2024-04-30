<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AdminCharge extends Model
{
    protected $table = 'agency_charge';

    protected $fillable = [
        'agency_id',
        'admin',
        'user_id',
        'coins',
        'cost', 'reason'
    ];
    public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }
    public function Admin(){
        return $this->hasone(Admins::class,"id","admin");
    }

    public function Agency(){
        return $this->hasone(Agency::class,"id","agency_id");
    }
}
