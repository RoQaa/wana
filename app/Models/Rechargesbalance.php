<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rechargesbalance extends Model
{
    protected $table = 'payments';
 
    protected $fillable = [
        'txn_id ',
        'user_id',
        'package_id',
        'method', 
        'cost',
    ];
    public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }
}
