<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Families extends Model
{
    protected $table = 'families';
    protected $fillable = [
        'name',
        'describtion',
        'image','user_id','nubmers',
        'status','model','Karisma','Familyid',
    ];
        public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }
    
        public function members(){
        return $this->hasmany(UserApp::class,"FamilyId","id")->limit(20);
    }
 
        public function admins(){
        return $this->hasmany(FamilyAdmins::class,"Family_id","id");
    }

    public function Rooms(){
        return $this->hasmany(Rooms::class,"FamilyId","id")->where('state',0);
    }
}
