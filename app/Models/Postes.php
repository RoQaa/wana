<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postes extends Model
{
    protected $table = 'postes';
 
    protected $fillable = [
        'user_id',
        'content',
        'likes',
        'comments',
        'image',
        'status'
    ];

    public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }
    public function like()
    {
        return $this->hasMany(PostLikes::class,'post_id','id');
    }

    public function commentsuser()
    {
        return $this->hasMany(PostComments::class,'post_id','id');
    }
}
