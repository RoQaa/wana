<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relations extends Model
{
    protected $table = 'relations';
 
    protected $fillable = [
        'sender_id', 
        'user_id', 
        'karisma', 
        'status',
        'Leaved',
        'Relation_id'
    ];
    
       public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
        
    }
    
      public function item(){
        return $this->hasone(ShopItem::class,"id","Relation_id");
        
    }
    
       public function anotheruser(){
        return $this->hasone(UserApp::class,"id","sender_id");
        
    }
    
}
