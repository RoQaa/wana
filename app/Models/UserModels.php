<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModels extends Model
{
    protected $table = 'user_models';
 
    protected $fillable = [
        'user_id',
        'Achiveid',   
    ];
    public function model(){
        return $this->hasone(AchiveModels::class,"id","Achiveid");
         }
}
 