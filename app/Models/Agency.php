<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $table = 'agencies';
    protected $fillable = [
        'name',
        'status',
        'description',
        'user_id',
        'coins',
        'AgencyKind',
        'model',
        'image',
        'user_number',
        'ban',
        'password',
        'importance',
        'phonenumber',
        'AdminGetter',
        'appid'
     ];

  public function Members2(){
        return $this->hasmany(UserTargets::class,"AgencyId","id")->orderBy('AgencyKarisma','desc');
    }
     public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }
    public function Members(){
        return $this->hasmany(UserApp::class,"AgencyId","id")->orderBy('AgencyKarisma','desc');
    }
     public function Agencypayments(){
        return $this->hasmany(agencypayments::class,"agency_id","id")->orderBy('created_at', 'desc');
    }
    
}
