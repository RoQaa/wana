<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLikes extends Model
{
    protected $table = 'post_likes';
 
    protected $fillable = [
        'user_id',
        'post_id',
         
    ];
    public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }


}
