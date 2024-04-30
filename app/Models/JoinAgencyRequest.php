<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinAgencyRequest extends Model
{
    protected $table = 'agencyjoinrequests';

    protected $fillable = [
        'user_id',
        'agancy_id',
        'status',
    ];

    
    public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }
}
