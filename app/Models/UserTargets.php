<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTargets extends Model
{
   
    
      protected $table = 'targetsuser';
    protected $fillable = [
        'myappid ',
        'name ',
        'AgencyKarisma',
        'AgencyId',
        'appid'
     ];
        
     public function Agency(){
        return $this->hasone(Agency::class,"id","AgencyId");

    }
}
