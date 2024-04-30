<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class agencypayments extends Model
{
    protected $table = 'agency_payments';

    protected $fillable = [
        'user_id',
        'agency_id',
        'cost',
        'coins',
    ];
    public function user(){
        return $this->hasone(UserApp::class,"id","user_id");
    }
    
}
