<?php

 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Families_Members extends Model
{
    protected $table = 'families__members';

    protected $fillable = [
        'Family_id',
        'user_id',
        'status',
    ];
          public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }
}
