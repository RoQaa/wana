<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComments extends Model
{
    protected $table = 'post_comments';
 
    protected $fillable = [
        'user_id',
        'post_id','Comment','CommentReplay','likes'];
        public function user(){
            return $this->hasone(UserApp::class,"id","user_id");
        }
}
